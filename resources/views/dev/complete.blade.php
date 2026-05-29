@extends('layouts.site')

@section('content')
<main class="section" style="max-width:800px;margin:auto;">
  <h1>Purchase Completed</h1>
  <p>Your purchase code has been generated. Share it with the client installer.</p>
  <div class="hero-panel">
    <div><strong>Purchase Code</strong><span style="font-family:monospace">{{ $purchase->code }}</span></div>
    <div><strong>Max Activations</strong><span>{{ $purchase->max_activations }}</span></div>
  </div>
  <p>Activation endpoint for Vfd-POS integrations: <code>{{ url('/api/license/verify') }}</code></p>
</main>
@endsection
