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

  .home-gallery-swiper {
    padding: 6px 0 12px;
  }

  .home-gallery-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #f3f4f6;
    aspect-ratio: 1 / 1;
  }

  .home-gallery-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .25s ease;
  }

  .home-gallery-card:hover img {
    transform: scale(1.03);
  }

  .home-gallery-zoom {
    position: absolute;
    right: 10px;
    bottom: 10px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: rgba(17, 24, 39, 0.75);
  }

  .home-gallery-action .btn {
    border-radius: 999px;
    padding: 10px 20px;
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

    const homeGallerySwiperContainer = document.querySelector('.home-gallery-swiper');
    if (homeGallerySwiperContainer) {
      const homeGallerySlideCount = homeGallerySwiperContainer.querySelectorAll('.swiper-slide').length;

      const homeGallerySwiper = new Swiper('.home-gallery-swiper', {
        slidesPerView: 2,
        spaceBetween: 12,
        loop: homeGallerySlideCount > 3,
        speed: 650,
        autoplay: {
          delay: 2800,
          disableOnInteraction: false,
        },
        breakpoints: {
          576: {
            slidesPerView: 3,
            spaceBetween: 12,
          },
          768: {
            slidesPerView: 4,
            spaceBetween: 14,
          },
          1024: {
            slidesPerView: 6,
            spaceBetween: 14,
          },
          1280: {
            slidesPerView: 8,
            spaceBetween: 14,
          },
        },
      });

      if (homeGallerySlideCount <= 3) {
        homeGallerySwiper.autoplay.stop();
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

@php
  $homeSectionDefaults = \App\Models\Setting::getDefaultHomeSections();
  $homeSectionConfig = \App\Models\Setting::normalizeHomeSections($settings->home_sections ?? $homeSectionDefaults);
  $homeSectionMap = collect($homeSectionConfig)->keyBy('key');
@endphp

@foreach($homeSectionConfig as $sectionConfig)
  @php
    $sectionKey = $sectionConfig['key'] ?? null;
    $sectionVisible = (bool) ($sectionConfig['is_visible'] ?? true);
  @endphp

  @continue(! $sectionVisible || blank($sectionKey))

  @switch($sectionKey)
    @case('split_slider')
      @include('themes.theme_1.pages.partials.home.split-slider')
      @break

    @case('services')
      @include('themes.theme_1.pages.partials.home.services')
      @break

    @case('cta')
      @include('themes.theme_1.pages.partials.home.cta')
      @break

    @case('references')
      @include('themes.theme_1.pages.partials.home.references')
      @break

    @case('gallery')
      @include('themes.theme_1.pages.partials.home.gallery')
      @break

    @case('posts')
      @include('themes.theme_1.pages.partials.home.posts')
      @break
  @endswitch
@endforeach

@endsection
