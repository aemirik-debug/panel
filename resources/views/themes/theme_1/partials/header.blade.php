<header id="header" class="header sticky-top">
  @php
    $socialMedia = \App\Models\SocialMedia::query()->first();

    $twitterUrl = $socialMedia->twitter ?? null;
    $facebookUrl = $socialMedia->facebook ?? null;
    $instagramUrl = $socialMedia->instagram ?? null;
    $linkedinUrl = $socialMedia->linkedin ?? null;
  @endphp

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
        @if($twitterUrl)
          <a href="{{ $twitterUrl }}" class="twitter"><i class="bi bi-twitter-x"></i></a>
        @endif
        @if($facebookUrl)
          <a href="{{ $facebookUrl }}" class="facebook"><i class="bi bi-facebook"></i></a>
        @endif
        @if($instagramUrl)
          <a href="{{ $instagramUrl }}" class="instagram"><i class="bi bi-instagram"></i></a>
        @endif
        @if($linkedinUrl)
          <a href="{{ $linkedinUrl }}" class="linkedin"><i class="bi bi-linkedin"></i></a>
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
      </a>

      <nav id="navmenu" class="navmenu">
        @php
          $resolveMenuUrl = function ($menu) {
              // Eğer menüye bağlı bir sayfa varsa, sayfa slug'ını kullan
              if ($menu->page && $menu->page->slug) {
                  return route('pages.show', ['slug' => $menu->page->slug]);
              }

              if (filled($menu->menu_type) && ! in_array($menu->menu_type, ['custom_page', 'custom_url'], true)) {
                // Named routes
                $routeMap = [
                  'home' => 'home',
                  'about' => 'pages.show',
                  'services' => 'services.index',
                  'references' => 'references.index',
                  'portfolio' => 'portfolio.index',
                  'blog' => 'blog.index',
                  'contact' => 'contact.index',
                ];
                
                if (isset($routeMap[$menu->menu_type])) {
                  try {
                    if ($menu->menu_type === 'about') {
                      return route($routeMap[$menu->menu_type], ['slug' => 'hakkimizda']);
                    }
                    return route($routeMap[$menu->menu_type]);
                  } catch (\Exception $e) {
                    // Route bulunamadı, fallback yap
                  }
                }
              }

            $normalizedSlug = \Illuminate\Support\Str::slug((string) ($menu->slug ?: $menu->title));
            $moduleRouteMap = [
              'referanslar' => '/referanslar',
              'hizmetler' => '/hizmetler',
              'iletisim' => '/iletisim',
              'hakkimizda' => '/hakkimizda',
              'blog' => '/blog',
              'projeler' => '/projeler',
              'portfolyo' => '/projeler',
            ];

            if (blank($menu->url) && isset($moduleRouteMap[$normalizedSlug])) {
              return $moduleRouteMap[$normalizedSlug];
            }

              $rawUrl = $menu->url;
              if (blank($rawUrl)) {
                  return '#';
              }

              if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) {
                  return $rawUrl;
              }

              return $rawUrl;
          };
        @endphp

        <ul>
          @if(isset($menus) && $menus->count())
            @foreach($menus as $menu)
              @php
                $menuUrl = $resolveMenuUrl($menu);
                // URL'den path'i al (slug varsa slugu kullan, yoksa URL'den)
                $menuPath = $menu->page && $menu->page->slug ? ltrim($menu->page->slug, '/') : ltrim((string) ($menu->url ?: ($menu->slug ?? '')), '/');
                $isExternal = str_starts_with((string) $menu->url, 'http://') || str_starts_with((string) $menu->url, 'https://');
                $isActive = !$isExternal && (($menuPath === '' && request()->is('/')) || ($menuPath !== '' && request()->is($menuPath . '*')));
              @endphp

              @if($menu->children->count())
                <li class="dropdown">
                  <a href="{{ $menuUrl }}" class="{{ $isActive ? 'active' : '' }}" @if($isExternal) target="_blank" rel="noopener noreferrer" @endif>
                    <span>
                      @if(!empty($menu->icon))
                        <i class="bi bi-{{ $menu->icon }} me-1"></i>
                      @endif
                      {{ $menu->title }}
                    </span>
                    <i class="bi bi-chevron-down toggle-dropdown"></i>
                  </a>
                  <ul>
                    @foreach($menu->children as $child)
                      @php
                        $childUrl = $resolveMenuUrl($child);
                        $childPath = $child->page && $child->page->slug ? ltrim($child->page->slug, '/') : ltrim((string) ($child->url ?: ($child->slug ?? '')), '/');
                        $childExternal = str_starts_with((string) $child->url, 'http://') || str_starts_with((string) $child->url, 'https://');
                        $childActive = !$childExternal && (($childPath === '' && request()->is('/')) || ($childPath !== '' && request()->is($childPath . '*')));
                      @endphp
                      <li>
                        <a href="{{ $childUrl }}" class="{{ $childActive ? 'active' : '' }}" @if($childExternal) target="_blank" rel="noopener noreferrer" @endif>
                          @if(!empty($child->icon))
                            <i class="bi bi-{{ $child->icon }} me-1"></i>
                          @endif
                          {{ $child->title }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @else
                <li>
                  <a href="{{ $menuUrl }}" class="{{ $isActive ? 'active' : '' }}" @if($isExternal) target="_blank" rel="noopener noreferrer" @endif>
                    @if(!empty($menu->icon))
                      <i class="bi bi-{{ $menu->icon }} me-1"></i>
                    @endif
                    {{ $menu->title }}
                  </a>
                </li>
              @endif
            @endforeach
          @else
            <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Ana Sayfa</a></li>
          @endif
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>

  </div>

</header>
