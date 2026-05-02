<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\Insight;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Response;

class SiteController extends Controller
{
    public function home()
    {
        return view('home', $this->siteData());
    }

    public function products()
    {
        return redirect(route('home').'#products', 301);
    }

    public function industries()
    {
        return redirect(route('home').'#industries', 301);
    }

    public function insights()
    {
        return redirect(route('home').'#insights', 301);
    }

    public function contact()
    {
        return redirect(route('home').'#contact', 301);
    }

    public function sitemap(): Response
    {
        return response()
            ->view('sitemap', ['url' => rtrim(config('app.url'), '/')])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $url = rtrim(config('app.url'), '/');

        return response("User-agent: *\nAllow: /\nDisallow: /lake-zone-control/\nDisallow: /admin\n\nSitemap: {$url}/sitemap.xml\n")
            ->header('Content-Type', 'text/plain');
    }

    private function siteData(): array
    {
        return [
            'company' => Setting::valueFor('company', []),
            'seo' => Setting::valueFor('seo', []),
            'products' => Product::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'industries' => Industry::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'insights' => Insight::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ];
    }
}
