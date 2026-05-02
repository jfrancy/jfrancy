@extends('layouts.site')

@section('content')
  <main id="top">
    <section class="hero">
      <div class="hero-content">
        <p class="eyebrow">{{ $company['tagline'] ?? 'Chemical sourcing, storage, and supply across Tanzania' }}</p>
        <h1>{{ $company['name'] ?? 'LAKE ZONE CHEMICALS LIMITED' }}</h1>
        <p class="hero-copy">
          Reliable chemical supply for manufacturers, water treatment plants, laboratories, mines, farms, and hospitality operators working across the Lake Zone and beyond.
        </p>
        <div class="hero-actions">
          <a class="button primary" href="#contact">Request a Quote</a>
          <a class="button secondary" href="#products">View Catalogue</a>
        </div>
      </div>
      <aside class="hero-panel" aria-label="Company highlights">
        <div><strong>{{ $company['products_stat'] ?? '120+' }}</strong><span>chemical lines</span></div>
        <div><strong>{{ $company['regions_stat'] ?? '8' }}</strong><span>regions served</span></div>
        <div><strong>{{ $company['support_stat'] ?? '24h' }}</strong><span>quote response</span></div>
      </aside>
    </section>

    <section class="section intro" id="about">
      <div>
        <p class="eyebrow">About the company</p>
        <h2>Precise sourcing. Careful handling. Dependable delivery.</h2>
      </div>
      <p class="lead">{{ $company['about'] ?? '' }}</p>
    </section>

    <section class="section catalogue" id="products">
      <div class="section-heading">
        <p class="eyebrow">Product catalogue</p>
        <h2>Chemicals for daily operations and specialist work</h2>
      </div>
      <div class="filter-bar" role="tablist" aria-label="Product categories">
        <button class="filter is-active" data-filter="all" type="button">All</button>
        @foreach($products->pluck('category')->unique() as $category)
          <button class="filter" data-filter="{{ $category }}" type="button">{{ $category }}</button>
        @endforeach
      </div>
      <div class="product-grid">
        @foreach($products as $product)
          <article class="product-card" data-category="{{ $product->category }}">
            <span class="pill">{{ $product->category }}</span>
            <h3>{{ $product->name }}</h3>
            <p>{{ $product->description }}</p>
            <dl>
              <div><dt>Grade</dt><dd>{{ $product->grade }}</dd></div>
              <div><dt>Pack</dt><dd>{{ $product->packaging }}</dd></div>
            </dl>
          </article>
        @endforeach
      </div>
    </section>

    <section class="band" id="quality">
      <div class="band-copy">
        <p class="eyebrow">Safety and quality</p>
        <h2>Built for buyers who need documentation, traceability, and technical confidence.</h2>
        <p>Every supply conversation includes grade confirmation, safety data availability, storage advice, and clear packaging options.</p>
      </div>
      <div class="quality-list">
        <article><span>01</span><h3>Verified sourcing</h3><p>Supplier checks, grade confirmation, and consistent product naming.</p></article>
        <article><span>02</span><h3>Document support</h3><p>SDS, COA, and handling notes prepared where required by the product.</p></article>
        <article><span>03</span><h3>Responsible logistics</h3><p>Packaging and dispatch planning suitable for sensitive chemical cargo.</p></article>
      </div>
    </section>

    <section class="section industries" id="industries">
      <div class="section-heading">
        <p class="eyebrow">Industries served</p>
        <h2>Supply coverage for the region's most active sectors</h2>
      </div>
      <div class="industry-grid">
        @foreach($industries as $industry)
          <article class="industry-card"><h3>{{ $industry->title }}</h3><p>{{ $industry->description }}</p></article>
        @endforeach
      </div>
    </section>

    <section class="section split">
      <div class="image-story" role="img" aria-label="Chemical drums and laboratory glassware"></div>
      <div class="story-copy">
        <p class="eyebrow">Procurement support</p>
        <h2>From single drums to recurring supply contracts.</h2>
        <p>Send your product name, grade, quantity, target delivery city, and preferred packaging. The Lake Zone Chemicals team can respond with availability, lead time, documentation, and a quotation path.</p>
        <a class="text-link" href="#contact">Start a supply request</a>
      </div>
    </section>

    <section class="section insights" id="insights">
      <div class="section-heading">
        <p class="eyebrow">Market notes</p>
        <h2>Useful updates for chemical buyers</h2>
      </div>
      <div class="insight-grid">
        @foreach($insights as $insight)
          <article class="insight-card"><span>{{ $insight->label }}</span><h3>{{ $insight->title }}</h3><p>{{ $insight->body }}</p></article>
        @endforeach
      </div>
    </section>

    <section class="contact" id="contact">
      <div class="contact-copy">
        <p class="eyebrow">Contact</p>
        <h2>Request pricing, availability, or technical support.</h2>
        <p>Share what you need and the team will prepare a clear response with product options, package sizes, and delivery timing.</p>
        <ul class="contact-list">
          <li><strong>Phone</strong><a href="tel:{{ preg_replace('/[^\d+]/', '', $company['phone'] ?? '') }}">{{ $company['phone'] ?? '' }}</a></li>
          <li><strong>Email</strong><a href="mailto:{{ $company['email'] ?? '' }}">{{ $company['email'] ?? '' }}</a></li>
          <li><strong>Location</strong><span>{{ $company['location'] ?? '' }}</span></li>
        </ul>
      </div>
      <form class="quote-form" id="quoteForm" data-email="{{ $company['email'] ?? '' }}">
        <label>Name<input name="name" autocomplete="name" required></label>
        <label>Company<input name="company" autocomplete="organization"></label>
        <label>Product needed<input name="product" required></label>
        <label>Quantity and delivery city<textarea name="details" rows="4" required></textarea></label>
        <button class="button primary" type="submit">Prepare Email Request</button>
        <p class="form-note">Your email app will open with the request details.</p>
      </form>
    </section>
  </main>
@endsection
