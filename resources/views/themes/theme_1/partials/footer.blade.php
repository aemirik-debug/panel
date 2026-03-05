<footer id="footer" class="footer dark-background">

  <div class="footer-top">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <span class="sitename">{{ $settings->site_name ?? 'Flattern' }}</span>
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
            @if($settings && $settings->twitter_url)
              <a href="{{ $settings->twitter_url }}"><i class="bi bi-twitter-x"></i></a>
            @endif
            @if($settings && $settings->facebook_url)
              <a href="{{ $settings->facebook_url }}"><i class="bi bi-facebook"></i></a>
            @endif
            @if($settings && $settings->instagram_url)
              <a href="{{ $settings->instagram_url }}"><i class="bi bi-instagram"></i></a>
            @endif
            @if($settings && $settings->linkedin_url)
              <a href="{{ $settings->linkedin_url }}"><i class="bi bi-linkedin"></i></a>
            @endif
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Hızlı Linkler</h4>
          <ul>
            <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
            <li><a href="{{ url('/hakkimizda') }}">Hakkımızda</a></li>
            <li><a href="{{ url('/hizmetler') }}">Hizmetler</a></li>
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
          <h4>Bültenimize Katılın</h4>
          <p>Son haberlerden haberdar olmak için bültenimize abone olun!</p>
          <form action="" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Abone Ol"></div>
            <div class="loading">Yükleniyor</div>
            <div class="error-message"></div>
            <div class="sent-message">Abonelik talebiniz gönderildi. Teşekkürler!</div>
          </form>
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
          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
      </div>

      <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
        @if($settings && $settings->twitter_url)
          <a href="{{ $settings->twitter_url }}"><i class="bi bi-twitter-x"></i></a>
        @endif
        @if($settings && $settings->facebook_url)
          <a href="{{ $settings->facebook_url }}"><i class="bi bi-facebook"></i></a>
        @endif
        @if($settings && $settings->instagram_url)
          <a href="{{ $settings->instagram_url }}"><i class="bi bi-instagram"></i></a>
        @endif
        @if($settings && $settings->linkedin_url)
          <a href="{{ $settings->linkedin_url }}"><i class="bi bi-linkedin"></i></a>
        @endif
      </div>

    </div>
  </div>

</footer>
