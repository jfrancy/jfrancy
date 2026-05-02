<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login | Lake Zone Chemicals</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
  </head>
  <body class="admin-body">
    <main class="login-wrap">
      <form class="login-card" method="post" action="{{ route('admin.authenticate') }}">
        @csrf
        <a class="brand" href="{{ route('home') }}">
          <span class="brand-mark">LZ</span>
          <span><strong>Lake Zone CMS</strong><small>Secure admin</small></span>
        </a>
        <h1>Admin access</h1>
        <p class="lead">Enter the admin password configured in your Laravel environment.</p>
        @error('password')<div class="errors">{{ $message }}</div>@enderror
        <label>Password<input type="password" name="password" required autofocus></label>
        <button class="button primary" type="submit">Open CMS</button>
      </form>
    </main>
  </body>
</html>
