@extends('layouts.site')

@section('content')
<main class="section" style="max-width:1100px;margin:auto;">
  <h1>Dev Licensing Portal</h1>
  <p>Domain target: <strong>dev.telpos-lite.app</strong>. Purchase software and modules, then receive a generated purchase code.</p>
  <div class="product-grid">
    @foreach($products as $product)
      <article class="product-card">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->description }}</p>
        <p><strong>${{ number_format($product->price,2) }}</strong></p>
        <form method="POST" action="{{ route('dev.checkout') }}">
          @csrf
          <input type="hidden" name="software_product_id" value="{{ $product->id }}" />
          <label>Buyer Name <input required name="customer_name" /></label>
          <label>Email <input required type="email" name="customer_email" /></label>
          <label>Phone <input name="customer_phone" /></label>
          <label>Install Domain/IP <input name="domain" placeholder="vfdpos-client.local" /></label>
          <label>License key format
            <select name="license_format" required>
              @foreach($licenseFormats as $format)
                <option value="{{ $format['value'] }}">{{ $format['label'] }} — {{ $format['description'] }}</option>
              @endforeach
            </select>
          </label>
          <fieldset>
            <legend>Modules</legend>
            @foreach($product->modules as $module)
            <label style="display:block"><input type="checkbox" name="modules[]" value="{{ $module->id }}" /> {{ $module->name }} (+${{ number_format($module->price,2) }})</label>
            @endforeach
          </fieldset>
          <button class="button primary" type="submit">Checkout & Generate Purchase Code</button>
        </form>
      </article>
    @endforeach
  </div>
</main>
@endsection
