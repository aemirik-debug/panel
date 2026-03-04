<footer class="text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row gy-4">

            {{-- 1. Kolon - Hakkımızda --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3">
                    {{ $settings->site_name ?? 'Kurumsal Site' }}
                </h5>

                <p class="text-secondary small">
                    {{ $settings->address ?? 'Adres bilgisi girilmemiş.' }}
                </p>
            </div>

            {{-- 2. Kolon - Menü --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Hızlı Menü</h6>

                <ul class="list-unstyled">
                    @foreach($menus as $menu)
                        <li class="mb-2">
                            <a href="{{ $menu->url }}" class="text-decoration-none text-secondary small footer-link">
                                {{ $menu->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 3. Kolon - İletişim --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">İletişim</h6>

                @if($settings?->phone)
                    <p class="small text-secondary mb-2">
                        <i class="fas fa-phone me-2"></i>
                        {{ $settings?->phone ?? 'Telefon Yok'}}
                    </p>
                @endif

                @if($settings?->email)
                    <p class="small text-secondary mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        {{ $settings?->email }}
                    </p>
                @endif

                <div class="mt-3">
                    @if($settings?->facebook)
                        <a href="{{ $settings->facebook }}" class="text-white me-3 social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif

                    @if($settings?->instagram)
                        <a href="{{ $settings->instagram }}" class="text-white me-3 social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif

                    @if($settings?->twitter)
                        <a href="{{ $settings->twitter }}" class="text-white social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- 4. Kolon - Newsletter --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Bültene Abone Ol</h6>

                <form method="POST" action="#">
                    @csrf
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email adresiniz" required>
                        <button class="btn btn-primary">Gönder</button>
                    </div>
                </form>

                <div class="mt-3">
                    {{-- Google Maps Embed --}}
                    <iframe 
                        src="https://maps.google.com/maps?q={{ urlencode($settings->address ?? 'Istanbul') }}&output=embed" 
                        width="100%" 
                        height="120" 
                        style="border:0; border-radius: 8px;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>

        </div>

        <hr class="border-secondary mt-4">

        <div class="text-center small text-secondary">
            © {{ date('Y') }} {{ $settings->site_name ?? 'Site Adı' }} | Tüm Hakları Saklıdır.
        </div>
    </div>
</footer>

<style>
    .footer-link:hover {
        color: #ffffff !important;
        padding-left: 6px;
        transition: 0.3s ease;
    }

    .social-icon:hover {
        color: #0d6efd !important;
        transition: 0.3s ease;
    }
</style>
