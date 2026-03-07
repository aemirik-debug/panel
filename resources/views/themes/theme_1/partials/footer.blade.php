<footer id="footer" class="footer dark-background">
  @php
    $socialMedia = \App\Models\SocialMedia::query()->first();

    $twitterUrl = $socialMedia->twitter ?? null;
    $facebookUrl = $socialMedia->facebook ?? null;
    $instagramUrl = $socialMedia->instagram ?? null;
    $linkedinUrl = $socialMedia->linkedin ?? null;
  @endphp

  <style>
    .footer .map-container iframe {
      width: 70%;
      height: 175px;
      margin: 0 auto;
      display: block;
      border: 0;
      border-radius: 8px;
    }
  </style>

  <div class="footer-top">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <span class="sitename" style="font-size: 1.5rem;">{{ $settings->site_name ?? 'Flattern' }}</span>
          </a>
          <div class="footer-contact pt-3">
            @if($settings && $settings->address)
              <p>{{ $settings->address }}</p>
            @endif
            @if($settings && $settings->phone)
              <p class="mt-3"><strong>Telefon:</strong> <span>{{ $settings->phone }}</span></p>
            @endif
            @if($settings && $settings->email)
              <p><strong>Email:</strong> <span>{{ $settings->email }}</span></p>
            @endif
          </div>
          <div class="social-links d-flex mt-4">
            @if($twitterUrl)
              <a href="{{ $twitterUrl }}"><i class="bi bi-twitter-x"></i></a>
            @endif
            @if($facebookUrl)
              <a href="{{ $facebookUrl }}"><i class="bi bi-facebook"></i></a>
            @endif
            @if($instagramUrl)
              <a href="{{ $instagramUrl }}"><i class="bi bi-instagram"></i></a>
            @endif
            @if($linkedinUrl)
              <a href="{{ $linkedinUrl }}"><i class="bi bi-linkedin"></i></a>
            @endif
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Hızlı Linkler</h4>
          <ul>
            <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
            <li><a href="{{ url('/hakkimizda') }}">Hakkımızda</a></li>
            <li><a href="{{ url('/hizmetler') }}">Hizmetler</a></li>
            <li><a href="{{ url('/urunler') }}">Ürünler</a></li>
            <li><a href="{{ route('photo-gallery.index') }}">Foto Galeri</a></li>
            <li><a href="{{ url('/blog') }}">Blog</a></li>
            <li><a href="{{ url('/iletisim') }}">İletişim</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Hizmetlerimiz</h4>
          <ul>
            @if(isset($services))
              @foreach($services->take(5) as $service)
                <li><a href="{{ url('/servis/' . $service->slug) }}">{{ $service->title }}</a></li>
              @endforeach
            @endif
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          @if(isset($footerMap) && $footerMap)
            <h4>{{ $footerMap->title }}</h4>
            <div class="map-container" style="position: relative; overflow: hidden; border-radius: 8px; margin-top: 15px;">
              {!! $footerMap->iframe_code !!}
            </div>
          @else
            <h4>Bültenimize Katılın</h4>
            <p>Son haberlerden haberdar olmak için bültenimize abone olun!</p>
            <form action="" method="post" class="php-email-form">
              <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Abone Ol"></div>
              <div class="loading">Yükleniyor</div>
              <div class="error-message"></div>
              <div class="sent-message">Abonelik talebiniz gönderildi. Teşekkürler!</div>
            </form>
          @endif
        </div>

      </div>
    </div>
  </div>

  <div class="copyright text-center">
    <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

      <div class="d-flex flex-column align-items-center align-items-lg-start">
        <div>
          © Copyright <strong><span>{{ $settings->site_name ?? 'Flattern' }}</span></strong>. Tüm Hakları Saklıdır
        </div>
        <div class="credits">
          {{ $footerCreditText ?? 'Designed by BootstrapMade' }}
        </div>
      </div>

      <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
        @if($twitterUrl)
          <a href="{{ $twitterUrl }}"><i class="bi bi-twitter-x"></i></a>
        @endif
        @if($facebookUrl)
          <a href="{{ $facebookUrl }}"><i class="bi bi-facebook"></i></a>
        @endif
        @if($instagramUrl)
          <a href="{{ $instagramUrl }}"><i class="bi bi-instagram"></i></a>
        @endif
        @if($linkedinUrl)
          <a href="{{ $linkedinUrl }}"><i class="bi bi-linkedin"></i></a>
        @endif
      </div>

    </div>
  </div>

</footer>
