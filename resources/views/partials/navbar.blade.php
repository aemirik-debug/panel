{{-- TOP BAR --}}
<div class="bg-dark text-white py-2 small">
    <div class="container d-flex justify-content-between">
        <div>
            @if($settings->phone)
                <span class="me-3">
                    <i class="fas fa-phone"></i> {{ $settings->phone }}
                </span>
            @endif

            @if($settings->email)
                <span>
                    <i class="fas fa-envelope"></i> {{ $settings->email }}
                </span>
            @endif
        </div>

        <div>
            @if($settings->facebook)
                <a href="{{ $settings->facebook }}" class="text-white me-2">
                    <i class="fab fa-facebook-f"></i>
                </a>
            @endif

            @if($settings->instagram)
                <a href="{{ $settings->instagram }}" class="text-white me-2">
                    <i class="fab fa-instagram"></i>
                </a>
            @endif

            @if($settings->twitter)
                <a href="{{ $settings->twitter }}" class="text-white">
                    <i class="fab fa-twitter"></i>
                </a>
            @endif
        </div>
    </div>
</div>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/">
            {{ $settings->site_name ?? 'LOGO' }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                @foreach($menus as $menu)
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ $menu->url }}">
                            {{ $menu->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
