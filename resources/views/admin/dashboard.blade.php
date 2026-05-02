<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>CMS Dashboard | Lake Zone Chemicals</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
  </head>
  <body class="admin-body">
    <header class="admin-top">
      <a class="brand" href="{{ route('home') }}">
        <span class="brand-mark">LZ</span>
        <span><strong>Lake Zone CMS</strong><small>Marketing control center</small></span>
      </a>
      <form method="post" action="{{ route('admin.logout') }}">
        @csrf
        <button class="button secondary" type="submit">Sign Out</button>
      </form>
    </header>

    <main class="admin-shell">
      <aside class="admin-sidebar">
        <a href="#company">Company</a>
        <a href="#seo">SEO</a>
        <a href="#products">Products</a>
        <a href="#industries">Industries</a>
        <a href="#insights">Insights</a>
      </aside>

      <section class="admin-panel">
        @if(session('status'))<div class="status">{{ session('status') }}</div>@endif
        @if($errors->any())
          <div class="errors">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
          </div>
        @endif

        <article class="admin-card" id="company">
          <h2>Company settings</h2>
          <form method="post" action="{{ route('admin.company.update') }}">
            @csrf
            @method('put')
            <div class="form-grid">
              <label>Company name<input name="name" value="{{ old('name', $company['name'] ?? '') }}" required></label>
              <label>Tagline<input name="tagline" value="{{ old('tagline', $company['tagline'] ?? '') }}" required></label>
              <label>Phone<input name="phone" value="{{ old('phone', $company['phone'] ?? '') }}" required></label>
              <label>WhatsApp<input name="whatsapp" value="{{ old('whatsapp', $company['whatsapp'] ?? '') }}" required></label>
              <label>Email<input type="email" name="email" value="{{ old('email', $company['email'] ?? '') }}" required></label>
              <label>Location<input name="location" value="{{ old('location', $company['location'] ?? '') }}" required></label>
              <label class="wide">Google Maps URL<input name="map_url" value="{{ old('map_url', $company['map_url'] ?? '') }}"></label>
              <label class="wide">About<textarea name="about" rows="5" required>{{ old('about', $company['about'] ?? '') }}</textarea></label>
              <label>Products stat<input name="products_stat" value="{{ old('products_stat', $company['products_stat'] ?? '') }}" required></label>
              <label>Regions stat<input name="regions_stat" value="{{ old('regions_stat', $company['regions_stat'] ?? '') }}" required></label>
              <label>Support stat<input name="support_stat" value="{{ old('support_stat', $company['support_stat'] ?? '') }}" required></label>
            </div>
            <p><button class="button primary" type="submit">Save Company</button></p>
          </form>
        </article>

        <article class="admin-card" id="seo">
          <h2>Power SEO</h2>
          <form method="post" action="{{ route('admin.seo.update') }}">
            @csrf
            @method('put')
            <div class="form-grid">
              <label class="wide">SEO title<input name="title" maxlength="70" value="{{ old('title', $seo['title'] ?? '') }}" required></label>
              <label class="wide">Meta description<textarea name="description" maxlength="170" rows="3" required>{{ old('description', $seo['description'] ?? '') }}</textarea></label>
              <label class="wide">Keywords<input name="keywords" value="{{ old('keywords', $seo['keywords'] ?? '') }}"></label>
              <label>Canonical URL<input name="canonical_url" value="{{ old('canonical_url', $seo['canonical_url'] ?? '') }}" required></label>
              <label>Open Graph image URL<input name="image_url" value="{{ old('image_url', $seo['image_url'] ?? '') }}"></label>
            </div>
            <p><button class="button primary" type="submit">Save SEO</button></p>
          </form>
        </article>

        <article class="admin-card" id="products">
          <h2>Products</h2>
          <form method="post" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="form-grid">
              <label>Name<input name="name" required></label>
              <label>Category<input name="category" required></label>
              <label>Grade<input name="grade" required></label>
              <label>Packaging<input name="packaging" required></label>
              <label>Sort order<input type="number" name="sort_order" value="{{ $products->count() + 1 }}" min="0" required></label>
              <label><span>Active</span><input type="checkbox" name="is_active" value="1" checked></label>
              <label class="wide">Description<textarea name="description" rows="3" required></textarea></label>
            </div>
            <p><button class="button secondary" type="submit">Add Product</button></p>
          </form>
          <div class="admin-list">
            @foreach($products as $product)
              <article class="admin-card">
                <form method="post" action="{{ route('admin.products.update', $product) }}">
                  @csrf
                  @method('put')
                  <div class="form-grid">
                    <label>Name<input name="name" value="{{ $product->name }}" required></label>
                    <label>Category<input name="category" value="{{ $product->category }}" required></label>
                    <label>Grade<input name="grade" value="{{ $product->grade }}" required></label>
                    <label>Packaging<input name="packaging" value="{{ $product->packaging }}" required></label>
                    <label>Sort order<input type="number" name="sort_order" value="{{ $product->sort_order }}" min="0" required></label>
                    <label><span>Active</span><input type="checkbox" name="is_active" value="1" @checked($product->is_active)></label>
                    <label class="wide">Description<textarea name="description" rows="3" required>{{ $product->description }}</textarea></label>
                  </div>
                  <p><button class="button primary" type="submit">Update</button></p>
                </form>
                <form method="post" action="{{ route('admin.products.destroy', $product) }}">
                  @csrf
                  @method('delete')
                  <button class="button danger" type="submit">Delete</button>
                </form>
              </article>
            @endforeach
          </div>
        </article>

        <article class="admin-card" id="industries">
          <h2>Industries</h2>
          <form method="post" action="{{ route('admin.industries.store') }}">
            @csrf
            <div class="form-grid">
              <label>Title<input name="title" required></label>
              <label>Sort order<input type="number" name="sort_order" value="{{ $industries->count() + 1 }}" min="0" required></label>
              <label><span>Active</span><input type="checkbox" name="is_active" value="1" checked></label>
              <label class="wide">Description<textarea name="description" rows="3" required></textarea></label>
            </div>
            <p><button class="button secondary" type="submit">Add Industry</button></p>
          </form>
          <div class="admin-list">
            @foreach($industries as $industry)
              <article class="admin-card">
                <form method="post" action="{{ route('admin.industries.update', $industry) }}">
                  @csrf
                  @method('put')
                  <div class="form-grid">
                    <label>Title<input name="title" value="{{ $industry->title }}" required></label>
                    <label>Sort order<input type="number" name="sort_order" value="{{ $industry->sort_order }}" min="0" required></label>
                    <label><span>Active</span><input type="checkbox" name="is_active" value="1" @checked($industry->is_active)></label>
                    <label class="wide">Description<textarea name="description" rows="3" required>{{ $industry->description }}</textarea></label>
                  </div>
                  <p><button class="button primary" type="submit">Update</button></p>
                </form>
                <form method="post" action="{{ route('admin.industries.destroy', $industry) }}">
                  @csrf
                  @method('delete')
                  <button class="button danger" type="submit">Delete</button>
                </form>
              </article>
            @endforeach
          </div>
        </article>

        <article class="admin-card" id="insights">
          <h2>Insights</h2>
          <form method="post" action="{{ route('admin.insights.store') }}">
            @csrf
            <div class="form-grid">
              <label>Title<input name="title" required></label>
              <label>Label<input name="label" required></label>
              <label>Sort order<input type="number" name="sort_order" value="{{ $insights->count() + 1 }}" min="0" required></label>
              <label><span>Active</span><input type="checkbox" name="is_active" value="1" checked></label>
              <label class="wide">Body<textarea name="body" rows="3" required></textarea></label>
            </div>
            <p><button class="button secondary" type="submit">Add Insight</button></p>
          </form>
          <div class="admin-list">
            @foreach($insights as $insight)
              <article class="admin-card">
                <form method="post" action="{{ route('admin.insights.update', $insight) }}">
                  @csrf
                  @method('put')
                  <div class="form-grid">
                    <label>Title<input name="title" value="{{ $insight->title }}" required></label>
                    <label>Label<input name="label" value="{{ $insight->label }}" required></label>
                    <label>Sort order<input type="number" name="sort_order" value="{{ $insight->sort_order }}" min="0" required></label>
                    <label><span>Active</span><input type="checkbox" name="is_active" value="1" @checked($insight->is_active)></label>
                    <label class="wide">Body<textarea name="body" rows="3" required>{{ $insight->body }}</textarea></label>
                  </div>
                  <p><button class="button primary" type="submit">Update</button></p>
                </form>
                <form method="post" action="{{ route('admin.insights.destroy', $insight) }}">
                  @csrf
                  @method('delete')
                  <button class="button danger" type="submit">Delete</button>
                </form>
              </article>
            @endforeach
          </div>
        </article>
      </section>
    </main>
  </body>
</html>
