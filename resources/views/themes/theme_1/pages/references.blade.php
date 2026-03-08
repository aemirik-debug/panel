@extends('themes.theme_1.layouts.app')

@section('title', 'Referanslarımız - ' . ($settings->site_name ?? 'Referanslar'))
@section('body-class', 'testimonials-page')

@push('styles')
<style>
.clients .client-logo {
  padding: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  background: #fff;
  height: 120px;
  border-right: 1px solid #ebebeb;
  border-bottom: 1px solid #ebebeb;
  transition: 0.3s;
}

.clients .client-logo:hover {
  transform: scale(1.05);
}

.clients .client-logo img {
  max-height: 60px;
  max-width: 100%;
  filter: grayscale(100);
  transition: 0.3s;
}

.clients .client-logo:hover img {
  filter: none;
}

.clients-wrap {
  border-top: 1px solid #ebebeb;
  border-left: 1px solid #ebebeb;
}

.testimonials .testimonial-item {
  background-color: #f4f4f4;
  box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
  padding: 30px;
  position: relative;
  border-radius: 8px;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.testimonials .testimonial-item:hover {
  box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.15);
  transform: translateY(-5px);
}

.testimonials .testimonial-item .testimonial-img {
  width: 250px;
  height: 250px;
  border-radius: 12px;
  margin: 0 auto 15px;
  display: block;
  object-fit: cover;
  border: 3px solid #fff;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

.testimonials .testimonial-item .testimonial-img-placeholder {
  width: 250px;
  height: 250px;
  border-radius: 12px;
  margin: 0 auto 15px;
  display: flex !important;
  align-items: center;
  justify-content: center;
}

.testimonials .testimonial-item h3 {
  font-size: 18px;
  font-weight: bold;
  margin: 0 0 5px 0;
  color: #111;
  text-align: center;
}

.testimonials .testimonial-item h4 {
  font-size: 14px;
  color: #999;
  margin: 0 0 15px 0;
  text-align: center;
}

.testimonials .testimonial-item .testimonial-text {
  font-style: italic;
  margin: 0;
  text-align: center;
  flex-grow: 1;
  font-size: 14px;
  color: #555;
  line-height: 1.6;
}

.testimonials .testimonial-item .quote-icon-left,
.testimonials .testimonial-item .quote-icon-right {
  color: #e1e1e1;
  font-size: 20px;
}

.testimonials .testimonial-item .quote-icon-left {
  display: inline-block;
  margin-right: 5px;
}

.testimonials .testimonial-item .quote-icon-right {
  display: inline-block;
  margin-left: 5px;
}
</style>
@endpush

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Referanslarımız</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Referanslar</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Clients Section -->
@if(isset($clients) && $clients->count() > 0)
<section id="clients" class="clients section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Çözüm Ortaklarımız</h2>
    <p>Birlikte çalıştığımız değerli firmalar</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row g-0 clients-wrap">

      @foreach($clients as $client)
        <div class="col-xl-3 col-md-4 col-6">
          <div class="client-logo">
            <img src="{{ asset('storage/' . $client->image) }}" class="img-fluid" alt="{{ $client->title }}">
          </div>
        </div><!-- End Client Item -->
      @endforeach

    </div>

  </div>

</section><!-- /Clients Section -->
@endif

<!-- Testimonials Section -->
@if(isset($testimonials) && $testimonials->count() > 0)
<section id="testimonials" class="testimonials section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>{{ $settings->references_section_title ?? 'Müşteri Referansları' }}</h2>
    <p>{{ $settings->references_section_description ?? 'Bizimle çalışan müşterilerimizin değerlendirmeleri' }}</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row gy-4 g-lg-4">

      @foreach($testimonials as $index => $testimonial)
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
          <div class="testimonial-item h-100">
            @if($testimonial->image)
              <img src="{{ asset('storage/' . $testimonial->image) }}" class="testimonial-img" alt="{{ $testimonial->name_surname }}">
            @else
              <div class="testimonial-img-placeholder bg-secondary d-flex align-items-center justify-content-center">
                <i class="bi bi-person-circle" style="font-size: 3rem; color: #fff;"></i>
              </div>
            @endif
            
            <h3 class="mt-3">{{ $testimonial->name_surname }}</h3>
            
            @if($testimonial->position)
              <h4 class="text-muted">{{ $testimonial->position }}</h4>
            @endif
            
            <p class="testimonial-text mt-3">
              <i class="bi bi-quote quote-icon-left"></i>
              <span>{{ Str::limit($testimonial->comment, 120) }}</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>

            <div class="mt-3">
              @if(strlen($testimonial->comment) > 120)
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#referenceModal{{ $testimonial->id }}">
                  <i class="bi bi-arrow-right"></i> Devamını Oku
                </button>
              @endif
            </div>
          </div>
        </div><!-- End testimonial item -->
      @endforeach

    </div>

  </div>

</section><!-- /Testimonials Section -->

<!-- Reference Detail Modals -->
@foreach($testimonials as $testimonial)
  <div class="modal fade" id="referenceModal{{ $testimonial->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $testimonial->name_surname }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3 text-center mb-3">
              @if($testimonial->image)
                <img src="{{ asset('storage/' . $testimonial->image) }}" class="img-fluid rounded" style="width: 250px; height: 250px; object-fit: cover;" alt="{{ $testimonial->name_surname }}">
              @else
                <div class="d-flex align-items-center justify-content-center" style="width: 250px; height: 250px; background: #e9ecef; border-radius: 12px; margin: 0 auto;">
                  <i class="bi bi-person-circle" style="font-size: 4rem; color: #999;"></i>
                </div>
              @endif
              @if($testimonial->position)
                <p class="text-muted mt-2 small">{{ $testimonial->position }}</p>
              @endif
            </div>
            <div class="col-md-9">
              <p class="lead">{{ $testimonial->comment }}</p>
              <small class="text-muted d-block mt-3">
                <i class="bi bi-calendar"></i> {{ $testimonial->created_at->format('d.m.Y') }}
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
@endif

@if((!isset($clients) || $clients->count() == 0) && (!isset($testimonials) || $testimonials->count() == 0))
<section class="section">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center">
        <p>Henüz referans eklenmedi.</p>
      </div>
    </div>
  </div>
</section>
@endif

@endsection
