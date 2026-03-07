@php
  $homeGalleryImages = collect($homeAlbums ?? [])->flatMap(function ($album) {
      return collect($album->images ?? [])->map(function ($image) use ($album) {
          return [
              'image' => $image,
              'title' => $album->title,
          ];
      });
  });

  $showHomeGalleryButton = (bool) ($settings->show_home_gallery_button ?? true);
@endphp

@if($homeGalleryImages->isNotEmpty())
<section id="portfolio" class="portfolio section">
  <div class="container" data-aos="fade-up">
    <div class="swiper home-gallery-swiper">
      <div class="swiper-wrapper">
        @foreach($homeGalleryImages as $galleryItem)
          <div class="swiper-slide">
            <div class="home-gallery-card">
              <img src="{{ asset('storage/' . $galleryItem['image']) }}" alt="Galeri Gorseli">
              <a href="{{ asset('storage/' . $galleryItem['image']) }}" title="{{ $galleryItem['title'] }}" data-gallery="home-gallery-stream" class="home-gallery-zoom glightbox">
                <i class="bi bi-zoom-in"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    @if($showHomeGalleryButton)
      <div class="home-gallery-action text-center mt-4">
        <a href="{{ route('photo-gallery.index') }}" class="btn btn-outline-primary">Tum galeriyi goruntule</a>
      </div>
    @endif
  </div>
</section>
@endif
