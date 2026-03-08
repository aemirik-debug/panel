@extends('themes.theme_1.layouts.app')

@section('title', 'Projelerimiz - ' . ($settings->site_name ?? 'Portfolyo'))
@section('body-class', 'portfolio-page')

@push('styles')
<style>
.portfolio-items {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 30px;
  padding: 20px 0;
}

.portfolio-item {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  background: #fff;
  cursor: pointer;
}

.portfolio-item:hover {
  box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.2);
  transform: translateY(-5px);
}

.portfolio-item .portfolio-image {
  width: 100%;
  height: 300px;
  object-fit: cover;
  display: block;
}

.portfolio-item .portfolio-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 20px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.portfolio-item:hover .portfolio-overlay {
  opacity: 1;
}

.portfolio-item .portfolio-title {
  color: #fff;
  font-size: 20px;
  font-weight: bold;
  margin: 0 0 10px 0;
}

.portfolio-item .portfolio-detail-btn {
  background: var(--accent-color, #0ea2bd);
  color: #fff;
  border: none;
  padding: 8px 20px;
  border-radius: 5px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  display: inline-block;
  text-align: center;
}

.portfolio-item .portfolio-detail-btn:hover {
  background: #0d8fa8;
  transform: translateY(-2px);
}

.portfolio-modal .modal-dialog {
  max-width: 900px;
}

.portfolio-modal .modal-header {
  background: linear-gradient(135deg, var(--accent-color, #0ea2bd) 0%, #0d8fa8 100%);
  color: #fff;
  border-bottom: none;
}

.portfolio-modal .modal-title {
  font-weight: bold;
  font-size: 24px;
}

.portfolio-modal .btn-close {
  filter: brightness(0) invert(1);
}

.portfolio-modal .carousel-item img {
  max-height: 500px;
  width: 100%;
  object-fit: contain;
  background: #f4f4f4;
}

.portfolio-modal .carousel-control-prev,
.portfolio-modal .carousel-control-next {
  width: 50px;
  height: 50px;
  background: rgba(0, 0, 0, 0.5);
  border-radius: 50%;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0.7;
  transition: opacity 0.3s;
}

.portfolio-modal .carousel-control-prev:hover,
.portfolio-modal .carousel-control-next:hover {
  opacity: 1;
}

.portfolio-modal .carousel-control-prev {
  left: 10px;
}

.portfolio-modal .carousel-control-next {
  right: 10px;
}

.portfolio-modal .portfolio-description {
  padding: 20px 0;
  font-size: 15px;
  line-height: 1.8;
  color: #555;
}

.portfolio-empty {
  text-align: center;
  padding: 60px 20px;
  color: #999;
}

.portfolio-empty i {
  font-size: 64px;
  margin-bottom: 20px;
  color: #ddd;
}
</style>
@endpush

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">{{ $settings->portfolio_section_title ?? 'Projelerimiz' }}</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Projeler</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Portfolio Section -->
<section id="portfolio" class="portfolio section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>{{ $settings->portfolio_section_title ?? 'Projelerimiz' }}</h2>
    <p>{{ $settings->portfolio_section_description ?? 'Gerçekleştirdiğimiz başarılı projeler ve çalışmalarımız' }}</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    @if(isset($portfolios) && $portfolios->count() > 0)
      <div class="portfolio-items">
        @foreach($portfolios as $portfolio)
          <div class="portfolio-item" data-bs-toggle="modal" data-bs-target="#portfolioModal{{ $portfolio->id }}">
            @if($portfolio->featured_image)
              <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="portfolio-image">
            @else
              <div class="portfolio-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-image" style="font-size: 48px; color: rgba(255,255,255,0.7);"></i>
              </div>
            @endif
            
            <div class="portfolio-overlay">
              <h3 class="portfolio-title">{{ $portfolio->title }}</h3>
              <button class="portfolio-detail-btn">Detaylı İncele</button>
            </div>
          </div>

          <!-- Modal for Portfolio -->
          <div class="modal fade portfolio-modal" id="portfolioModal{{ $portfolio->id }}" tabindex="-1" aria-labelledby="portfolioModalLabel{{ $portfolio->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="portfolioModalLabel{{ $portfolio->id }}">{{ $portfolio->title }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  
                  @if($portfolio->images && count($portfolio->images) > 0)
                    <!-- Carousel for Gallery Images -->
                    <div id="carousel{{ $portfolio->id }}" class="carousel slide" data-bs-ride="carousel">
                      <div class="carousel-indicators">
                        @foreach($portfolio->images as $index => $image)
                          <button type="button" data-bs-target="#carousel{{ $portfolio->id }}" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                      </div>
                      
                      <div class="carousel-inner">
                        @foreach($portfolio->images as $index => $image)
                          <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="{{ $portfolio->title }} - Görsel {{ $index + 1 }}">
                          </div>
                        @endforeach
                      </div>
                      
                      @if(count($portfolio->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $portfolio->id }}" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $portfolio->id }}" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Next</span>
                        </button>
                      @endif
                    </div>
                  @elseif($portfolio->featured_image)
                    <!-- Show Featured Image if No Gallery -->
                    <div class="text-center">
                      <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" style="max-width: 100%; height: auto; border-radius: 8px;">
                    </div>
                  @else
                    <div class="text-center text-muted py-5">
                      <i class="bi bi-images" style="font-size: 48px;"></i>
                      <p class="mt-3">Bu proje için galeri görseli eklenmemiş.</p>
                    </div>
                  @endif

                  @if($portfolio->description)
                    <div class="portfolio-description">
                      <h6 style="font-weight: bold; color: #333; margin-bottom: 10px;">Proje Hakkında</h6>
                      <p>{{ $portfolio->description }}</p>
                    </div>
                  @endif
                  
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="portfolio-empty">
        <i class="bi bi-folder-x"></i>
        <h4>Henüz proje eklenmemiş</h4>
        <p>Yakında burada projelerimizi görebileceksiniz.</p>
      </div>
    @endif

  </div>

</section><!-- End Portfolio Section -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Prevent modal from closing when clicking on carousel controls
  document.querySelectorAll('.portfolio-modal .carousel-control-prev, .portfolio-modal .carousel-control-next').forEach(function(control) {
    control.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  });
});
</script>
@endpush
