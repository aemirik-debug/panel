@extends('themes.theme_1.layouts.app')

@section('title', 'Portfolyo - ' . ($settings->site_name ?? 'Portfolyo'))
@section('body-class', 'portfolio-page')

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Portfolyo</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Portfolyo</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Portfolio Section -->
<section id="portfolio" class="portfolio section">

  <div class="container">

    <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

      <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

        @forelse($galleries as $index => $gallery)
          <div class="col-lg-4 col-md-6 portfolio-item isotope-item">
            <img src="{{ asset('storage/' . $gallery->image) }}" class="img-fluid" alt="{{ $gallery->title }}">
            <div class="portfolio-info">
              <h4>{{ $gallery->title }}</h4>
              <a href="{{ asset('storage/' . $gallery->image) }}" title="{{ $gallery->title }}" data-gallery="portfolio-gallery" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
            </div>
          </div><!-- End Portfolio Item -->
        @empty
          <div class="col-12 text-center">
            <p>Henüz portfolyo eklenmedi.</p>
          </div>
        @endforelse

      </div><!-- End Portfolio Container -->

    </div>

  </div>

</section><!-- /Portfolio Section -->

@endsection
