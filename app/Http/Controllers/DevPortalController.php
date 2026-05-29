<?php

namespace App\Http\Controllers;

use App\Models\LicenseOrder;
use App\Models\PurchaseCode;
use App\Models\SoftwareProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevPortalController extends Controller
{
    public function index()
    {
        return view('dev.portal', [
            'products' => SoftwareProduct::query()->with(['modules' => fn ($q) => $q->where('is_active', true)])->where('is_active', true)->get(),
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'software_product_id' => ['required', 'exists:software_products,id'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['integer', 'exists:software_modules,id'],
            'customer_name' => ['required', 'string', 'max:160'],
            'customer_email' => ['required', 'email', 'max:160'],
            'customer_phone' => ['nullable', 'string', 'max:60'],
            'domain' => ['nullable', 'string', 'max:120'],
        ]);

        $product = SoftwareProduct::query()->with('modules')->findOrFail($data['software_product_id']);
        $selectedModules = $product->modules->whereIn('id', $data['modules'] ?? []);
        $amount = (float) $product->price + $selectedModules->sum('price');

        $purchaseCode = DB::transaction(function () use ($data, $product, $amount) {
            $order = LicenseOrder::query()->create([
                'order_ref' => 'ORD-'.strtoupper(Str::random(10)),
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
                'code' => 'TELPOS-'.strtoupper(Str::random(6)).'-'.strtoupper(Str::random(6)),
                'activation_secret' => hash('sha256', Str::random(64)),
                'max_activations' => 3,
                'is_active' => true,
            ]);
        });

        return redirect()->route('dev.order.complete', $purchaseCode->code);
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
                'app_slug' => $data['app_slug'],
                'activation_token' => hash_hmac('sha256', $purchase->code.'|'.$data['app_slug'], config('app.key')),
            ],
        ]);
    }
}
