@extends('themes.theme_1.layouts.app')

@section('title', $settings->site_name ?? 'Ana Sayfa')
@section('body-class', 'index-page')

@section('content')

<!-- Hero Section -->
<section id="hero" class="hero section dark-background">

  <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

    @if($sliders && $sliders->count() > 0)
      @foreach($sliders as $index => $slider)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
          <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}">
          <div class="container">
            <h2>{{ $slider->title }}</h2>
            <p>{{ $slider->subtitle }}</p>
            @if($slider->button_url)
              <a href="{{ $slider->button_url }}" class="btn-get-started">{{ $slider->button_text ?? 'Başlayın' }}</a>
            @endif
          </div>
        </div>
      @endforeach
    @else
      <div class="carousel-item active">
        <img src="{{ asset('themes/theme_1/assets/img/hero-carousel/hero-carousel-1.jpg') }}" alt="">
        <div class="container">
          <h2>{{ $settings->hero_title ?? 'We are Professional' }}</h2>
          <p>{{ $settings->hero_subtitle ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit' }}</p>
          <a href="#featured-services" class="btn-get-started">Başlayın</a>
        </div>
      </div>
    @endif

    <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
    </a>

    <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
    </a>

    <ol class="carousel-indicators"></ol>

  </div>

</section><!-- /Hero Section -->

<!-- Services Section -->
<section id="services" class="services section">

  <div class="container section-title" data-aos="fade-up">
    <h2>Hizmetlerimiz</h2>
    <p>{{ $settings->services_description ?? 'Sizin için en iyi hizmetleri sunuyoruz' }}</p>
  </div>

  <div class="container">

    <div class="row gy-4">

      @if($services && $services->count() > 0)
        @foreach($services as $index => $service)
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="{{ $service->icon ?? 'bi bi-activity' }}"></i>
              </div>
              <a href="{{ url('/servis/' . $service->slug) }}" class="stretched-link">
                <h3>{{ $service->title }}</h3>
              </a>
              <p>{{ $service->short_description }}</p>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-12 text-center">
          <p>Henüz hizmet eklenmedi.</p>
        </div>
      @endif

    </div>

  </div>

</section><!-- /Services Section -->

<!-- Call To Action Section -->
<section id="call-to-action" class="call-to-action section light-background">

  <div class="container">

    <div class="row" data-aos="zoom-in" data-aos-delay="100">
      <div class="col-xl-9 text-center text-xl-start">
        <h3>{{ $settings->cta_title ?? 'Harekete Geç' }}</h3>
        <p>{{ $settings->cta_description ?? 'Bizimle çalışmaya başlamak için hemen iletişime geçin' }}</p>
      </div>
      <div class="col-xl-3 cta-btn-container text-center">
        <a class="cta-btn align-middle" href="{{ url('/iletisim') }}">İletişim</a>
      </div>
    </div>

  </div>

</section><!-- /Call To Action Section -->

<!-- Portfolio Section -->
@if(isset($galleries) && $galleries->count() > 0)
<section id="portfolio" class="portfolio section">

  <div class="container section-title" data-aos="fade-up">
    <h2>Portfolyo</h2>
    <p>Son çalışmalarımızı görün</p>
  </div>

  <div class="container">

    <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

      <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

        @foreach($galleries->take(8) as $index => $gallery)
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item">
            <img src="{{ asset('storage/' . $gallery->image) }}" class="img-fluid" alt="{{ $gallery->title }}">
            <div class="portfolio-info">
              <h4>{{ $gallery->title }}</h4>
              <p>{{ $gallery->description }}</p>
              <a href="{{ asset('storage/' . $gallery->image) }}" title="{{ $gallery->title }}" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
            </div>
          </div>
        @endforeach

      </div>

    </div>

  </div>

</section><!-- /Portfolio Section -->
@endif

<!-- Recent Posts Section -->
@if(isset($posts) && $posts->count() > 0)
<section id="recent-posts" class="recent-posts section">

  <div class="container section-title" data-aos="fade-up">
    <h2>Son Yazılar</h2>
    <p>Blog'umuzdan son haberler</p>
  </div>

  <div class="container">

    <div class="row gy-4">

      @foreach($posts->take(3) as $index => $post)
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
          <article>

            <div class="post-img">
              <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="img-fluid">
            </div>

            <p class="post-category">{{ $post->category->name ?? 'Genel' }}</p>

            <h2 class="title">
              <a href="{{ url('/blog/' . $post->slug) }}">{{ $post->title }}</a>
            </h2>

            <div class="d-flex align-items-center">
              <div class="post-meta">
                <p class="post-date">
                  <time datetime="{{ $post->created_at->format('Y-m-d') }}">{{ $post->created_at->format('d M Y') }}</time>
                </p>
              </div>
            </div>

          </article>
        </div>
      @endforeach

    </div>

  </div>

</section><!-- /Recent Posts Section -->
@endif

@endsection
