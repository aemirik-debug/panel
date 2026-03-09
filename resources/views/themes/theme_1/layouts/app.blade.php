<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', $settings->site_name ?? 'Flattern')</title>
  <meta name="description" content="@yield('meta_description', $settings->meta_description ?? '')">
  <meta name="keywords" content="@yield('meta_keywords', $settings->meta_keywords ?? '')">

  <!-- Favicons -->
  @if($settings && $settings->favicon)
    <link href="{{ asset('storage/' . $settings->favicon) }}" rel="icon">
  @else
    <link href="{{ asset('themes/theme_1/assets/img/favicon.png') }}" rel="icon">
  @endif
  <link href="{{ asset('themes/theme_1/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('themes/theme_1/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/theme_1/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/theme_1/assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/theme_1/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/theme_1/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('themes/theme_1/assets/css/main.css') }}" rel="stylesheet">

  @stack('styles')

  <style>
    :root {
        @if($settings && $settings->primary_color)
            --accent-color: {{ $settings->primary_color }};
        @endif
        @if($settings && $settings->secondary_color)
            --contrast-color: {{ $settings->secondary_color }};
        @endif
    }
  </style>
</head>

<body class="@yield('body-class', 'index-page')">

  @include('themes.theme_1.components.announcements')

  @include('themes.theme_1.partials.header')

  <main class="main">
    @yield('content')
  </main>

  @include('themes.theme_1.partials.footer')

  <!-- WhatsApp Floating Button -->
  @include('themes.theme_1.partials.whatsapp')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('themes/theme_1/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('themes/theme_1/assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('themes/theme_1/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('themes/theme_1/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('themes/theme_1/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('themes/theme_1/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('themes/theme_1/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('themes/theme_1/assets/vendor/waypoints/noframework.waypoints.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('themes/theme_1/assets/js/main.js') }}"></script>

  @stack('scripts')

</body>
</html>
