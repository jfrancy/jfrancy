<?php

namespace App\Http\Controllers;

use App\Enums\LicenseFormat;
use App\Models\LicenseOrder;
use App\Models\PurchaseCode;
use App\Models\SoftwareProduct;
use App\Services\LicenseKeyGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DevPortalController extends Controller
{
    public function __construct(private readonly LicenseKeyGenerator $licenseKeyGenerator)
    {
    }

    public function index()
    {
        return view('dev.portal', [
            'licenseFormats' => LicenseFormat::options(),
            'products' => SoftwareProduct::query()
                ->with(['modules' => fn ($query) => $query->where('is_active', true)])
                ->where('is_active', true)
                ->get(),
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $data = $this->validatedLicenseRequest($request);
        $purchaseCode = $this->createPurchaseCode($data);

        return redirect()->route('dev.order.complete', $purchaseCode->code);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedLicenseRequest($request);
        $purchaseCode = $this->createPurchaseCode($data);

        return response()->json([
            'purchase_code' => $purchaseCode->code,
            'license_format' => $purchaseCode->license_format,
            'verification_endpoint' => url('/api/license/verify'),
        ], 201);
    }

    public function complete(string $code)
    {
        return view('dev.complete', ['purchase' => PurchaseCode::query()->where('code', $code)->firstOrFail()]);
    }

    public function verify(Request $request): JsonResponse
    {
        $data = $request->validate([
            'purchase_code' => ['required', 'string'],
            'app_slug' => ['required', 'string'],
            'machine_hash' => ['nullable', 'string', 'max:255'],
        ]);

        $purchase = PurchaseCode::query()->where('code', $data['purchase_code'])->first();
        if (! $purchase || ! $purchase->is_active || ($purchase->expires_at && $purchase->expires_at->isPast())) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired purchase code'], 422);
        }

        if ($purchase->activations_count >= $purchase->max_activations) {
            return response()->json(['valid' => false, 'message' => 'Activation limit reached'], 429);
        }

        $purchase->increment('activations_count');
        $purchase->update(['last_verified_at' => now()]);

        return response()->json([
            'valid' => true,
            'license' => [
                'purchase_code' => $purchase->code,
                'license_format' => $purchase->license_format,
                'app_slug' => $data['app_slug'],
                'activation_token' => hash_hmac('sha256', $purchase->code.'|'.$data['app_slug'], config('app.key')),
            ],
        ]);
    }

    private function validatedLicenseRequest(Request $request): array
    {
        if ($request->has('licenseFormat') && ! $request->has('license_format')) {
            $request->merge(['license_format' => $request->input('licenseFormat')]);
        }

        $validated = Validator::make($request->all(), [
            'software_product_id' => ['required', 'exists:software_products,id'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['integer', 'exists:software_modules,id'],
            'customer_name' => ['required', 'string', 'max:160'],
            'customer_email' => ['required', 'email', 'max:160'],
            'customer_phone' => ['nullable', 'string', 'max:60'],
            'domain' => ['nullable', 'string', 'max:120'],
            'license_format' => ['nullable', Rule::in(LicenseFormat::values())],
        ])->validate();

        $validated['license_format'] = $validated['license_format'] ?? LicenseFormat::Default->value;

        return $validated;
    }

    private function createPurchaseCode(array $data): PurchaseCode
    {
        $product = SoftwareProduct::query()->with('modules')->findOrFail($data['software_product_id']);
        $selectedModules = $product->modules->whereIn('id', $data['modules'] ?? []);
        $amount = (float) $product->price + $selectedModules->sum('price');

        return DB::transaction(function () use ($data, $product, $amount): PurchaseCode {
            $order = LicenseOrder::query()->create([
                'order_ref' => $this->uniqueOrderReference(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'domain' => $data['domain'] ?? null,
                'amount' => $amount,
                'status' => 'paid',
            ]);

            return PurchaseCode::query()->create([
                'license_order_id' => $order->id,
                'software_product_id' => $product->id,
                'code' => $this->uniquePurchaseCode($data['license_format']),
                'license_format' => $data['license_format'],
                'activation_secret' => hash('sha256', bin2hex(random_bytes(32))),
                'max_activations' => 3,
                'is_active' => true,
            ]);
        });
    }

    private function uniquePurchaseCode(string $format): string
    {
        do {
            $code = $this->licenseKeyGenerator->generate($format);
        } while (PurchaseCode::query()->where('code', $code)->exists());

        return $code;
    }

    private function uniqueOrderReference(): string
    {
        do {
            $reference = 'ORD-'.$this->licenseKeyGenerator->generateDefaultKey();
        } while (LicenseOrder::query()->where('order_ref', $reference)->exists());

        return $reference;
    }
}
