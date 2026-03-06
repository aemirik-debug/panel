@extends('themes.theme_1.layouts.app')

@section('title', $service->meta_title ?? $service->title)
@section('meta_description', $service->meta_description ?? $service->short_description)
@section('body-class', 'service-details-page')

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">{{ $service->title }}</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li><a href="{{ url('/hizmetler') }}">Hizmetler</a></li>
        <li class="current">{{ $service->title }}</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Service Details Section -->
<section id="service-details" class="service-details section">

  <div class="container">

    <div class="row gy-4">

      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <h3 class="sidebar-title mb-3">Hizmetlerimiz</h3>
        <div class="services-list">
          @php
            $allServices = \App\Models\Service::where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();
          @endphp
          
          @foreach($allServices as $srv)
            <a href="{{ url('/servis/' . $srv->slug) }}" class="{{ $srv->id == $service->id ? 'active' : '' }}">
              {{ $srv->title }}
            </a>
          @endforeach
        </div>
      </div>

      <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
        <h3>{{ $service->title }}</h3>

        <!-- Image and Short Description Row -->
        @if($service->image || $service->short_description)
          <div class="row mb-4">
            @if($service->image)
              <div class="col-md-6 d-flex align-items-start">
                <div style="width: 100%; max-width: 400px; height: 400px; overflow: hidden; border-radius: 8px;">
                  <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" style="width: 100%; height: 100%; object-fit: cover;" class="services-img">
                </div>
              </div>
            @endif
            
            @if($service->short_description)
              <div class="col-md-6 d-flex align-items-start">
                <div>
                  <p class="text-muted">{{ $service->short_description }}</p>
                </div>
              </div>
            @endif
          </div>
        @endif
        
        <div class="service-content">
          {!! $service->description !!}
        </div>

        @if($service->features && is_array(json_decode($service->features)))
          <ul>
            @foreach(json_decode($service->features) as $feature)
              <li><i class="bi bi-check-circle"></i> <span>{{ $feature }}</span></li>
            @endforeach
          </ul>
        @endif
        
        @if($service->long_description)
          <div class="mt-4">
            <h4>Detaylı Açıklama</h4>
            {!! $service->long_description !!}
          </div>
        @endif

      </div>

    </div>

  </div>

</section><!-- /Service Details Section -->

@endsection
