@extends('themes.theme_1.layouts.app')

@section('title', $settings->site_name ?? 'Ana Sayfa')
@section('body-class', 'index-page')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
  /* Referanslar Carousel Styling */
  .referanslar-carousel {
    padding: 40px 0;
  }

  .referanslar-swiper {
    padding: 40px 0;
  }

  .reference-item {
    text-align: center;
    padding: 20px;
    height: 100%;
    transition: transform 0.3s ease;
  }

  .reference-item:hover {
    transform: translateY(-5px);
  }

  .reference-image {
    overflow: hidden;
    border-radius: 8px;
    height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
  }

  .reference-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .reference-info h4 {
    margin-top: 15px;
    margin-bottom: 8px;
    font-weight: 600;
    color: #37373f;
  }

  .reference-info p {
    font-size: 14px;
    line-height: 1.6;
  }

  .services .service-item {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .services .service-item .icon {
    margin: 0 auto 12px;
  }

  .services .service-item h3,
  .services .service-item p {
    text-align: center;
  }

  .split-slider {
    padding: 40px 0;
  }

  .split-left-slide {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    min-height: 440px;
  }

  .split-left-slide img {
    width: 100%;
    height: 440px;
    object-fit: cover;
  }

  .split-caption {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    padding: 12px 16px;
    font-weight: 500;
  }

  .split-right-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    min-height: 212px;
    margin-bottom: 16px;
  }

  .split-right-card img {
    width: 100%;
    height: 212px;
    object-fit: cover;
  }

  /* Swiper Navigation Styling */
  .swiper-button-next,
  .swiper-button-prev {
    color: #0ea5e9;
    background: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    top: 50%;
    transform: translateY(-50%);
  }

  .swiper-button-next:hover,
  .swiper-button-prev:hover {
    background: #0ea5e9;
    color: white;
    box-shadow: 0 4px 20px rgba(14, 165, 233, 0.4);
  }

  .swiper-button-next:after,
  .swiper-button-prev:after {
    font-size: 20px;
  }

  .swiper-button-next {
    right: -25px;
  }

  .swiper-button-prev {
    left: -25px;
  }

  /* Pagination Dots */
  .swiper-pagination-bullet {
    width: 12px;
    height: 12px;
    background: #0ea5e9;
    opacity: 0.5;
    margin: 0 6px !important;
  }

  .swiper-pagination-bullet-active {
    background: #0ea5e9;
    opacity: 1;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .swiper-button-next,
    .swiper-button-prev {
      width: 35px;
      height: 35px;
      display: none;
    }

    .referanslar-swiper {
      padding: 20px 0 40px 0;
    }

    .split-left-slide,
    .split-left-slide img {
      min-height: 300px;
      height: 300px;
    }

    .split-right-card,
    .split-right-card img {
      min-height: 180px;
      height: 180px;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const splitLeftCarousel = document.querySelector('#split-left-carousel');
    if (splitLeftCarousel) {
      new bootstrap.Carousel(splitLeftCarousel, {
        interval: 4500,
        ride: 'carousel'
      });
    }

    const swiperContainer = document.querySelector('.referanslar-swiper');
    if (swiperContainer) {
      const slideCount = swiperContainer.querySelectorAll('.swiper-slide').length;

      const referanslarSwiper = new Swiper('.referanslar-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: slideCount > 1,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 15
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 20
          },
          1280: {
            slidesPerView: 6,
            spaceBetween: 20
          }
        }
      });

      if (slideCount <= 1) {
        referanslarSwiper.autoplay.stop();
        const nextBtn = document.querySelector('.swiper-button-next');
        const prevBtn = document.querySelector('.swiper-button-prev');
        const pagination = document.querySelector('.swiper-pagination');

        if (nextBtn) nextBtn.style.display = 'none';
        if (prevBtn) prevBtn.style.display = 'none';
        if (pagination) pagination.style.display = 'none';
      }
    }
  });
</script>
@endpush

@section('content')

@php
  // Sadece aktif slider'ları model tiplerine göre ayır
  $fullWidthSliders = collect($sliders ?? [])
    ->filter(fn ($slider) => $slider->is_active && ($slider->slider_model ?? 'full_width') === 'full_width')
    ->values();

  $splitSliders = collect($sliders ?? [])
    ->filter(fn ($slider) => $slider->is_active && ($slider->slider_model ?? 'full_width') === 'split_layout')
    ->values();

  $splitStaticSource = $splitSliders->first();
@endphp

<!-- Hero Section -->
@php
  // Build full-width carousel items from either legacy slides[] or direct slider fields.
  $allFullWidthSlides = collect();
  foreach($fullWidthSliders as $slider) {
    if(!empty($slider->slides) && is_array($slider->slides)) {
      foreach($slider->slides as $slide) {
        if (!empty($slide['image'] ?? null)) {
          $allFullWidthSlides->push([
            'image' => $slide['image'],
            'title' => $slide['title'] ?? $slider->title,
            'subtitle' => $slide['subtitle'] ?? $slider->subtitle,
            'button_text' => $slide['button_text'] ?? $slider->button_text,
            'button_url' => $slide['button_url'] ?? $slider->button_url,
          ]);
        }
      }
    } elseif(!empty($slider->image)) {
      $allFullWidthSlides->push([
        'image' => $slider->image,
        'title' => $slider->title,
        'subtitle' => $slider->subtitle,
        'button_text' => $slider->button_text,
        'button_url' => $slider->button_url,
      ]);
    }
  }
@endphp

@if($allFullWidthSlides->count() > 0)
<section id="hero" class="hero section dark-background">

  <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

    @foreach($allFullWidthSlides as $index => $slide)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
          <img src="{{ asset('storage/' . $slide['image']) }}" alt="{{ $slide['title'] ?? '' }}">
          @if(!empty($slide['title']) || !empty($slide['subtitle']) || !empty($slide['button_url']))
            <div class="container">
              @if(!empty($slide['title']))
                <h2>{{ $slide['title'] }}</h2>
              @endif
              @if(!empty($slide['subtitle']))
                <p>{{ $slide['subtitle'] }}</p>
              @endif
              @if(!empty($slide['button_url']))
                <a href="{{ $slide['button_url'] }}" class="btn-get-started">{{ $slide['button_text'] ?? 'Başlayın' }}</a>
              @endif
            </div>
          @endif
        </div>
      @endforeach

    <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
    </a>

    <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
    </a>

    <ol class="carousel-indicators"></ol>

  </div>

</section><!-- /Hero Section -->
@endif

@php
  // Build split carousel items from either legacy slides[] or direct slider fields.
  $allSplitSlides = collect();
  foreach($splitSliders as $slider) {
    if(!empty($slider->slides) && is_array($slider->slides)) {
      foreach($slider->slides as $slide) {
        if (!empty($slide['image'] ?? null)) {
          $allSplitSlides->push([
            'image' => $slide['image'],
            'title' => $slide['title'] ?? $slider->title,
            'subtitle' => $slide['subtitle'] ?? $slider->subtitle,
          ]);
        }
      }
    } elseif(!empty($slider->image)) {
      $allSplitSlides->push([
        'image' => $slider->image,
        'title' => $slider->title,
        'subtitle' => $slider->subtitle,
      ]);
    }
  }
@endphp

@if($allSplitSlides->count() > 0)
<section id="split-slider" class="split-slider section">
  <div class="container" data-aos="fade-up">
    <div class="row g-3 align-items-stretch">
      <div class="col-lg-8">
        <div id="split-left-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4500">
          <div class="carousel-inner">
            @foreach($allSplitSlides as $index => $slide)
              <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <div class="split-left-slide">
                  <img src="{{ asset('storage/' . $slide['image']) }}" alt="{{ $slide['title'] ?? 'Slider' }}">
                  @if(!empty($slide['title']) || !empty($slide['subtitle']))
                    <div class="split-caption">
                      <div>{{ trim(($slide['title'] ?? '') . ' ' . ($slide['subtitle'] ?? '')) }}</div>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
          @if($allSplitSlides->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#split-left-carousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#split-left-carousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          @endif
        </div>
      </div>

      <div class="col-lg-4">
        @if(!empty($splitStaticSource?->right_top_image))
          <div class="split-right-card">
            <img src="{{ asset('storage/' . $splitStaticSource->right_top_image) }}" alt="Sag Ust Gorsel">
            @if(!empty($splitStaticSource->right_top_caption))
              <div class="split-caption">{{ $splitStaticSource->right_top_caption }}</div>
            @endif
          </div>
        @endif

        @if(!empty($splitStaticSource?->right_bottom_image))
          <div class="split-right-card mb-0">
            <img src="{{ asset('storage/' . $splitStaticSource->right_bottom_image) }}" alt="Sag Alt Gorsel">
            @if(!empty($splitStaticSource->right_bottom_caption))
              <div class="split-caption">{{ $splitStaticSource->right_bottom_caption }}</div>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endif

<!-- Services Section -->
<section id="services" class="services section">

  <div class="container section-title" data-aos="fade-up">
    <h2>{{ $settings->services_section_title ?? 'Hizmetlerimiz' }}</h2>
    <p>{{ $settings->services_description ?? 'Sizin için en iyi hizmetleri sunuyoruz' }}</p>
  </div>

  <div class="container">

    <div class="row gy-4">

      @if($services && $services->count() > 0)
        @foreach($services as $index => $service)
          @php
            $iconClass = trim((string) ($service->icon ?? ''));
            $iconClass = match ($iconClass) {
              'fa-solid fa-code' => 'bi bi-code-slash',
              'fa-solid fa-shirt' => 'bi bi-bag',
              'fa-solid fa-building' => 'bi bi-building',
              'fa-solid fa-stethoscope' => 'bi bi-heart-pulse',
              'fa-solid fa-truck' => 'bi bi-truck',
              'fa-solid fa-utensils' => 'bi bi-cup-hot',
              'fa-solid fa-store' => 'bi bi-shop',
              'fa-solid fa-chart-line' => 'bi bi-graph-up-arrow',
              default => $iconClass,
            };
            if ($iconClass === '') {
                $iconClass = 'bi bi-activity';
            } elseif (!str_contains($iconClass, 'bi ') && !str_contains($iconClass, 'bi-')) {
                $iconClass = 'bi bi-activity';
            } elseif (str_starts_with($iconClass, 'bi-')) {
                $iconClass = 'bi ' . $iconClass;
            }
          @endphp
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="{{ $iconClass }}"></i>
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

<!-- Referanslar (Testimonials) Carousel Section -->
<section id="referanslar-carousel" class="referanslar-carousel section">

  <div class="container section-title" data-aos="fade-up">
    <h2>{{ $settings->references_section_title ?? 'Referanslar ve Musterilerimiz' }}</h2>
    <p>{{ $settings->references_section_description ?? 'Calistigimiz basarili projelerimiz' }}</p>
  </div>

  <div class="container" data-aos="fade-up">
    
    @if(isset($references) && $references->count() > 0)
      <!-- Swiper Carousel -->
      <div class="swiper referanslar-swiper">
        <div class="swiper-wrapper">
          @foreach($references as $reference)
            <div class="swiper-slide">
              <div class="reference-item">
                <div class="reference-image">
                  @php
                    $referenceImage = $reference->image ?? $reference->featured_image;
                  @endphp
                  <img src="{{ asset('storage/' . $referenceImage) }}" alt="{{ $reference->title }}" class="img-fluid rounded">
                </div>
                <div class="reference-info text-center mt-3">
                  <h4>{{ $reference->title }}</h4>
                  <p class="text-muted">{{ Str::limit($reference->description, 80) }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Navigation Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>
      </div>
    @else
      <div class="text-center py-4">
        <p class="text-muted mb-0">Henüz referans eklenmedi.</p>
      </div>
    @endif

  </div>

</section><!-- /Referanslar Carousel Section -->

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
