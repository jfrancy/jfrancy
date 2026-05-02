@php
  $siteTitle = $seo['title'] ?? ($company['name'] ?? config('app.name'));
  $description = $seo['description'] ?? '';
  $canonical = $seo['canonical_url'] ?? url('/');
  $image = $seo['image_url'] ?? '';
  $keywords = $seo['keywords'] ?? '';
  $phoneClean = preg_replace('/[^\d+]/', '', $company['phone'] ?? '');
  $whatsappClean = ltrim(preg_replace('/[^\d+]/', '', $company['whatsapp'] ?? $company['phone'] ?? ''), '+');
  $mapUrl = $company['map_url'] ?? 'https://maps.google.com/?q='.urlencode($company['location'] ?? 'Mwanza, Tanzania');
@endphp
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <meta name="theme-color" content="#11674f">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $siteTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    @if($image)<meta property="og:image" content="{{ $image }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://images.unsplash.com">
    <link rel="dns-prefetch" href="https://images.unsplash.com">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": @json($company['name'] ?? config('app.name')),
        "description": @json($description),
        "url": @json($canonical),
        "telephone": @json($company['phone'] ?? ''),
        "email": @json($company['email'] ?? ''),
        "address": {
          "@type": "PostalAddress",
          "addressLocality": @json($company['location'] ?? 'Mwanza, Tanzania'),
          "addressCountry": "TZ"
        },
        "areaServed": ["Mwanza", "Geita", "Shinyanga", "Mara", "Kagera", "Simiyu", "Tanzania"],
        "knowsAbout": ["Industrial chemicals", "Water treatment chemicals", "Laboratory reagents", "Mining chemicals", "Agricultural chemicals"]
      }
    </script>
  </head>
  <body>
    <header class="site-header">
      <div class="header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ $company['name'] ?? config('app.name') }} home">
          <span class="brand-mark">LZ</span>
          <span><strong>Lake Zone Chemicals</strong><small>Limited</small></span>
        </a>
        <div class="header-right">
          <address class="top-contacts">
            <a href="tel:{{ $phoneClean }}">{{ $company['phone'] ?? '' }}</a>
            <a href="mailto:{{ $company['email'] ?? '' }}">{{ $company['email'] ?? '' }}</a>
            <a href="{{ $mapUrl }}" target="_blank" rel="noopener">{{ $company['location'] ?? '' }}</a>
          </address>
          <nav class="site-nav" aria-label="Primary navigation">
            <a href="{{ route('home') }}#products">Products</a>
            <a href="{{ route('home') }}#industries">Industries</a>
            <a href="{{ route('home') }}#quality">Quality</a>
            <a href="{{ route('home') }}#insights">Insights</a>
            <a href="{{ route('home') }}#contact">Contact</a>
          </nav>
        </div>
      </div>
    </header>

    @yield('content')

    <div class="floating-actions" aria-label="Quick contact actions">
      <a class="float-button whatsapp" href="https://wa.me/{{ $whatsappClean }}?text={{ urlencode('Hello '.($company['name'] ?? 'Lake Zone Chemicals').', I would like chemical supply information.') }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">WA</a>
      <a class="float-button call" href="tel:{{ $phoneClean }}" aria-label="Call Lake Zone Chemicals">Call</a>
    </div>

    <footer class="site-footer">
      <p>© {{ date('Y') }} {{ $company['name'] ?? config('app.name') }}. All rights reserved.</p>
      <span>Chemical supplier in Mwanza, Tanzania</span>
    </footer>

    <script src="{{ asset('js/site.js') }}" defer></script>
  </body>
</html>
