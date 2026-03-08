@extends('themes.theme_1.layouts.app')

@section('title', 'Hizmetlerimiz - ' . ($settings->site_name ?? 'Hizmetler'))
@section('body-class', 'services-page')

@push('styles')
<style>
  .services .service-item {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
  }

  .services .service-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(to right, #37373f, #0ea5e9);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
  }

  .services .service-item:hover {
    border-color: #0ea5e9;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-5px);
  }

  .services .service-item:hover::before {
    transform: scaleX(1);
  }

  .services .service-item .icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: rgba(14, 165, 233, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .services .service-item .icon i {
    font-size: 36px;
    color: #0ea5e9;
    transition: all 0.3s ease;
  }

  .services .service-item:hover .icon i {
    font-size: 42px;
    color: #37373f;
  }

  .services .service-item h3 {
    font-size: 18px;
    font-weight: 700;
    margin: 15px 0;
    color: #37373f;
    transition: color 0.3s ease;
  }

  .services .service-item:hover h3 {
    color: #0ea5e9;
  }

  .services .service-item p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    flex-grow: 1;
    margin: 0 0 15px 0;
  }

  .services .service-item .btn-group {
    display: flex;
    gap: 8px;
    margin-top: auto;
  }

  .services .service-item .btn-sm {
    flex: 1;
  }
</style>
@endpush

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Hizmetlerimiz</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Hizmetler</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Services Section -->
<section id="services" class="services section">

  <div class="container">

    <div class="row gy-4">

      @forelse($services as $service)
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
        <div class="col-lg-4 col-md-6" data-aos="fade-up">
          <div class="service-item">
            <div class="icon">
              <i class="{{ $iconClass }}"></i>
            </div>
            <h3>{{ $service->title }}</h3>
            <p>{{ Str::limit(strip_tags($service->description ?? $service->short_description), 100) }}</p>
            
            <div class="btn-group">
              <a href="{{ url('/servis/' . $service->slug) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-right"></i> Detay
              </a>
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                <i class="bi bi-eye"></i> Önizle
              </button>
            </div>
          </div>
        </div><!-- End Service Item -->
      @empty
        <div class="col-12 text-center">
          <p class="text-muted">Henüz hizmet eklenmedi.</p>
        </div>
      @endforelse

    </div>

  </div>

</section><!-- /Services Section -->

<!-- Service Preview Modals -->
@forelse($services as $service)
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
  <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $service->title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            @if($service->image)
              <div class="col-md-4 mb-3">
                <img src="{{ asset('storage/' . $service->image) }}" class="img-fluid rounded" alt="{{ $service->title }}">
              </div>
              <div class="col-md-8">
            @else
              <div class="col-12">
            @endif
              <div class="icon mb-3">
                <i class="{{ $iconClass }}" style="font-size: 2rem; color: #0ea5e9;"></i>
              </div>
              @if($service->short_description)
                <p class="lead">{{ $service->short_description }}</p>
              @endif
              <div class="service-description">
                {!! $service->description !!}
              </div>
              @if($service->image)
              </div>
            @else
              </div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <a href="{{ url('/servis/' . $service->slug) }}" class="btn btn-primary">
            Tam Sayfaya Git <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
@empty
@endforelse

@if(isset($serviceAlbums) && $serviceAlbums->count() > 0)
  <section id="service-albums" class="portfolio section pt-0">
    <div class="container section-title" data-aos="fade-up">
      <h2>Hizmet Galerisi</h2>
      <p>Hizmetler bölümünde gösterilen albümler</p>
    </div>

    <div class="container">
      @foreach($serviceAlbums as $album)
        @php
          $albumImages = collect($album->images ?? [])->take(8);
        @endphp

        @if($albumImages->isNotEmpty())
          <h4 class="mb-3">{{ $album->title }}</h4>
          <div class="row gy-4 mb-5">
            @foreach($albumImages as $image)
              <div class="col-lg-3 col-md-4 col-6 portfolio-item">
                <img src="{{ asset('storage/' . $image) }}" class="img-fluid" alt="{{ $album->title }}">
                <div class="portfolio-info">
                  <h5>{{ $album->title }}</h5>
                  <a href="{{ asset('storage/' . $image) }}" title="{{ $album->title }}" data-gallery="service-album-{{ $album->id }}" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      @endforeach
    </div>
  </section>
@endif

@endsection
