<header id="header" class="header sticky-top">

  <div class="topbar d-flex align-items-center light-background">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        @if($settings && $settings->email)
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></i>
        @endif
        @if($settings && $settings->phone)
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>{{ $settings->phone }}</span></i>
        @endif
      </div>
      <div class="social-links d-none d-md-flex align-items-center">
        @if($settings && $settings->twitter_url)
          <a href="{{ $settings->twitter_url }}" class="twitter"><i class="bi bi-twitter-x"></i></a>
        @endif
        @if($settings && $settings->facebook_url)
          <a href="{{ $settings->facebook_url }}" class="facebook"><i class="bi bi-facebook"></i></a>
        @endif
        @if($settings && $settings->instagram_url)
          <a href="{{ $settings->instagram_url }}" class="instagram"><i class="bi bi-instagram"></i></a>
        @endif
        @if($settings && $settings->linkedin_url)
          <a href="{{ $settings->linkedin_url }}" class="linkedin"><i class="bi bi-linkedin"></i></a>
        @endif
      </div>
    </div>
  </div><!-- End Top Bar -->

  <div class="branding d-flex align-items-cente">

    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="{{ url('/') }}" class="logo d-flex align-items-center">
        @if($settings && $settings->logo)
          <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_name ?? 'Logo' }}">
        @endif
        <h1 class="sitename">{{ $settings->site_name ?? 'Flattern' }}</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Ana Sayfa</a></li>
          <li><a href="{{ url('/hakkimizda') }}" class="{{ Request::is('hakkimizda') ? 'active' : '' }}">Hakkımızda</a></li>
          <li><a href="{{ url('/hizmetler') }}" class="{{ Request::is('hizmetler*') ? 'active' : '' }}">Hizmetler</a></li>
          <li><a href="{{ url('/referanslar') }}" class="{{ Request::is('referanslar') ? 'active' : '' }}">Referanslar</a></li>
          <li><a href="{{ url('/portfolyo') }}" class="{{ Request::is('portfolyo*') ? 'active' : '' }}">Portfolyo</a></li>
          <li><a href="{{ url('/blog') }}" class="{{ Request::is('blog*') ? 'active' : '' }}">Blog</a></li>
          <li><a href="{{ url('/iletisim') }}" class="{{ Request::is('iletisim') ? 'active' : '' }}">İletişim</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>

  </div>

</header>
