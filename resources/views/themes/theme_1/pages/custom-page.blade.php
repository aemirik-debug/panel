@extends('themes.theme_1.layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' - ' . ($settings->site_name ?? 'Site'))
@section('meta_description', $page->meta_description ?: ($settings->meta_description ?? ''))
@section('body-class', 'custom-page')

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">{{ $page->title }}</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">{{ $page->title }}</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Content Section -->
<section id="page-content" class="about section">

  <div class="container">

    <div class="row gy-4">
      <!-- Left Column - Image & Title -->
      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
        <h3>{{ $page->title }}</h3>
        @if($page->featured_image)
          <img src="{{ asset('storage/' . $page->featured_image) }}" class="img-fluid rounded-4 mb-4" alt="{{ $page->title }}">
        @endif
        @if($page->excerpt)
          <p>{{ $page->excerpt }}</p>
        @endif
      </div>

      <!-- Right Column - Main Content -->
      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
        <div class="content ps-0 ps-lg-5">
          <div class="content-body">
            {!! $page->content !!}
          </div>
        </div>
      </div>
    </div>

  </div>

</section><!-- /Content Section -->

@if(isset($aboutAlbums) && $aboutAlbums->count() > 0)
<section id="about-albums" class="portfolio section pt-0">
  <div class="container section-title" data-aos="fade-up">
    <h2>Fotoğraf Galerisi</h2>
    <p>Hakkımızda bölümünde gösterilen albümler</p>
  </div>

  <div class="container">
    @foreach($aboutAlbums as $album)
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
                <a href="{{ asset('storage/' . $image) }}" title="{{ $album->title }}" data-gallery="about-album-{{ $album->id }}" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
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
