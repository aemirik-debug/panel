@extends('themes.theme_1.layouts.app')

@section('title', 'Foto Galeri - ' . ($settings->site_name ?? 'Foto Galeri'))
@section('body-class', 'photo-gallery-page')

@push('styles')
<style>
  .album-card {
    display: block;
    border: 1px solid #e6e8ec;
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    color: inherit;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    height: 100%;
  }

  .album-card:hover {
    transform: translateY(-4px);
    border-color: #0ea5e9;
    box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
  }

  .album-card.is-active {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2);
  }

  .album-cover {
    position: relative;
    aspect-ratio: 4 / 3;
    background: #f2f4f8;
  }

  .album-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .album-count {
    position: absolute;
    right: 10px;
    bottom: 10px;
    background: rgba(15, 23, 42, 0.8);
    color: #fff;
    font-size: 12px;
    border-radius: 999px;
    padding: 5px 10px;
  }

  .album-card-body {
    padding: 12px 14px;
  }

  .album-card-title {
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    color: #111827;
  }

  .album-card-desc {
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 13px;
    line-height: 1.45;
  }

  .gallery-photo {
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    background: #f3f4f6;
  }

  .gallery-photo img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    transition: transform 0.25s ease;
  }

  .gallery-photo:hover img {
    transform: scale(1.03);
  }

  .gallery-photo .zoom-btn {
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
    background: rgba(15, 23, 42, 0.78);
  }
</style>
@endpush

@section('content')

<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Foto Galeri</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Foto Galeri</li>
      </ol>
    </nav>
  </div>
</div>

<section class="portfolio section">
  <div class="container" data-aos="fade-up">
    @if(isset($albums) && $albums->count() > 0)
      <div class="container section-title pt-0" data-aos="fade-up">
        <h2>Albümler</h2>
        <p>Bir albüm seçin, fotoğrafları büyük ekranda inceleyin</p>
      </div>

      <div class="row g-3 mb-5">
        @foreach($albums as $album)
          @php
            $cover = $album->cover_image ?: (($album->images ?? [])[0] ?? null);
            $isActiveAlbum = optional($selectedAlbum)->id === $album->id;
          @endphp
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('photo-gallery.index', ['album' => $album->slug]) }}" class="album-card {{ $isActiveAlbum ? 'is-active' : '' }}">
              <div class="album-cover">
                @if($cover)
                  <img src="{{ asset('storage/' . $cover) }}" alt="{{ $album->title }}">
                @endif
                <span class="album-count">{{ count($album->images ?? []) }} foto</span>
              </div>
              <div class="album-card-body">
                <p class="album-card-title">{{ $album->title }}</p>
                @if(!empty($album->description))
                  <p class="album-card-desc">{{ \Illuminate\Support\Str::limit($album->description, 70) }}</p>
                @endif
              </div>
            </a>
          </div>
        @endforeach
      </div>

      @if($selectedAlbum)
        <h3 class="mb-3">{{ $selectedAlbum->title }}</h3>
        @if(!empty($selectedAlbum->description))
          <p class="text-muted mb-4">{{ $selectedAlbum->description }}</p>
        @endif
        <div class="row gy-4">
          @foreach(($selectedAlbum->images ?? []) as $image)
            <div class="col-lg-3 col-md-4 col-6 portfolio-item">
              <div class="gallery-photo">
                <img src="{{ asset('storage/' . $image) }}" alt="{{ $selectedAlbum->title }}">
                <a href="{{ asset('storage/' . $image) }}" title="{{ $selectedAlbum->title }}" data-gallery="photo-gallery-selected" class="zoom-btn glightbox">
                  <i class="bi bi-zoom-in"></i>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    @else
      <div class="text-center py-5">
        <p class="text-muted mb-0">Henüz aktif bir albüm bulunmuyor.</p>
      </div>
    @endif
  </div>
</section>

@endsection
