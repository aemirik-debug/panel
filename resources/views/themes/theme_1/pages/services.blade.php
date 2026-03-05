@extends('themes.theme_1.layouts.app')

@section('title', 'Hizmetlerimiz - ' . ($settings->site_name ?? 'Hizmetler'))
@section('body-class', 'services-page')

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
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="service-item position-relative">
            <div class="icon">
              <i class="bi {{ $service->icon ?? 'bi-activity' }}"></i>
            </div>
            <a href="{{ url('/hizmet/' . $service->slug) }}" class="stretched-link">
              <h3>{{ $service->title }}</h3>
            </a>
            <p>{{ Str::limit(strip_tags($service->description), 120) }}</p>
          </div>
        </div><!-- End Service Item -->
      @empty
        <div class="col-12 text-center">
          <p>Henüz hizmet eklenmedi.</p>
        </div>
      @endforelse

    </div>

  </div>

</section><!-- /Services Section -->

@endsection
