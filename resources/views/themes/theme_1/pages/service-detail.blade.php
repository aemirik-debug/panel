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

        @if($service->short_description)
          <h4>{{ explode('.', $service->short_description)[0] ?? '' }}</h4>
          <p>{{ $service->short_description }}</p>
        @endif
      </div>

      <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
        @if($service->image)
          <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="img-fluid services-img">
        @endif
        
        <h3>{{ $service->title }}</h3>
        
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
            {!! $service->long_description !!}
          </div>
        @endif

      </div>

    </div>

  </div>

</section><!-- /Service Details Section -->

@endsection
