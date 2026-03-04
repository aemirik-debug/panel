<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>
        @yield('title', $settings?->meta_title ?? $settings?->site_name ?? 'Kurumsal Site')
    </title>

    <meta name="description"
          content="@yield('meta_description', $settings?->meta_description ?? 'Kurumsal web sitesi')">

    {{-- Favicon --}}
    @if(!empty($settings?->favicon))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: {{ $settings->primary_color ?? '#0d6efd' }};
        --secondary-color: {{ $settings->secondary_color ?? '#6c757d' }};
    }

    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .btn-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .social-icon:hover {
        color: var(--primary-color) !important;
    }
</style>
@php
    $settings = \App\Models\Setting::first();
@endphp

@php
    $settings = \App\Models\Setting::first();
@endphp

<style>
    :root {
        --primary-color: {{ $settings->primary_color ?? '#0d6efd' }};
        --secondary-color: {{ $settings->secondary_color ?? '#212529' }};
    }

    /* ===== PRIMARY ===== */
    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .btn-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    .btn-primary:hover {
        opacity: 0.9;
    }

    /* ===== SECONDARY ===== */
    .bg-secondary {
        background-color: var(--secondary-color) !important;
    }

    .text-secondary-theme {
        color: var(--secondary-color) !important;
    }

    /* ===== NAVBAR ===== */
    .navbar {
        background-color: var(--secondary-color) !important;
    }

    .navbar .nav-link {
        color: #ffffff !important;
    }

    .navbar .nav-link:hover {
        color: var(--primary-color) !important;
    }

    /* ===== FOOTER ===== */
    footer {
        background-color: var(--secondary-color) !important;
    }

    footer a:hover {
        color: var(--primary-color) !important;
    }

    /* ===== LINKS ===== */
    a {
        transition: 0.3s ease;
    }

    a:hover {
        color: var(--primary-color) !important;
    }

    /* ===== SOCIAL ICONS ===== */
    .social-icon:hover {
        color: var(--primary-color) !important;
    }
</style>

</head>

<body class="bg-light text-dark d-flex flex-column min-vh-100">

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- CONTENT --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
