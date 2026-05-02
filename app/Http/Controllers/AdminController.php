<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\Insight;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $validated = $request->validate(['password' => ['required', 'string']]);
        $configured = (string) config('app.admin_password', env('ADMIN_PASSWORD', 'change-this-strong-password'));

        $valid = str_starts_with($configured, '$2y$')
            ? Hash::check($validated['password'], $configured)
            : hash_equals($configured, $validated['password']);

        if (! $valid) {
            return back()->withErrors(['password' => 'The admin password is incorrect.']);
        }

        $request->session()->regenerate();
        $request->session()->put('lake_zone_admin', true);

        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'company' => Setting::valueFor('company', []),
            'seo' => Setting::valueFor('seo', []),
            'products' => Product::query()->orderBy('sort_order')->get(),
            'industries' => Industry::query()->orderBy('sort_order')->get(),
            'insights' => Insight::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function updateCompany(Request $request): RedirectResponse
    {
        Setting::put('company', $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'tagline' => ['required', 'string', 'max:220'],
            'phone' => ['required', 'string', 'max:60'],
            'whatsapp' => ['required', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:160'],
            'location' => ['required', 'string', 'max:180'],
            'map_url' => ['nullable', 'url', 'max:500'],
            'about' => ['required', 'string', 'max:1200'],
            'products_stat' => ['required', 'string', 'max:30'],
            'regions_stat' => ['required', 'string', 'max:30'],
            'support_stat' => ['required', 'string', 'max:30'],
        ]));

        return back()->with('status', 'Company settings updated.');
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        Setting::put('seo', $request->validate([
            'title' => ['required', 'string', 'max:70'],
            'description' => ['required', 'string', 'max:170'],
            'keywords' => ['nullable', 'string', 'max:300'],
            'canonical_url' => ['required', 'url', 'max:250'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]));

        return back()->with('status', 'SEO settings updated.');
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        Product::query()->create($this->productData($request));

        return back()->with('status', 'Product added.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->productData($request));

        return back()->with('status', 'Product updated.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    public function storeIndustry(Request $request): RedirectResponse
    {
        Industry::query()->create($this->industryData($request));

        return back()->with('status', 'Industry added.');
    }

    public function updateIndustry(Request $request, Industry $industry): RedirectResponse
    {
        $industry->update($this->industryData($request));

        return back()->with('status', 'Industry updated.');
    }

    public function destroyIndustry(Industry $industry): RedirectResponse
    {
        $industry->delete();

        return back()->with('status', 'Industry deleted.');
    }

    public function storeInsight(Request $request): RedirectResponse
    {
        Insight::query()->create($this->insightData($request));

        return back()->with('status', 'Insight added.');
    }

    public function updateInsight(Request $request, Insight $insight): RedirectResponse
    {
        $insight->update($this->insightData($request));

        return back()->with('status', 'Insight updated.');
    }

    public function destroyInsight(Insight $insight): RedirectResponse
    {
        $insight->delete();

        return back()->with('status', 'Insight deleted.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('lake_zone_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function productData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:80'],
            'grade' => ['required', 'string', 'max:120'],
            'packaging' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return $data + ['slug' => Str::slug($data['name']), 'is_active' => $request->boolean('is_active')];
    }

    private function industryData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return $data + ['slug' => Str::slug($data['title']), 'is_active' => $request->boolean('is_active')];
    }

    private function insightData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'label' => ['required', 'string', 'max:80'],
            'body' => ['required', 'string', 'max:700'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return $data + ['slug' => Str::slug($data['title']), 'is_active' => $request->boolean('is_active')];
    }
}
