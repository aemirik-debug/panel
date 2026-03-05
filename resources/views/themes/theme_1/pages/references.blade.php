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
}

.testimonials .testimonial-item .testimonial-img {
  width: 90px;
  border-radius: 50%;
  margin: 0 auto 20px;
  display: block;
}

.testimonials .testimonial-item h3 {
  font-size: 18px;
  font-weight: bold;
  margin: 10px 0 5px 0;
  color: #111;
  text-align: center;
}

.testimonials .testimonial-item h4 {
  font-size: 14px;
  color: #999;
  margin: 0 0 15px 0;
  text-align: center;
}

.testimonials .testimonial-item .stars {
  margin-bottom: 15px;
  text-align: center;
}

.testimonials .testimonial-item .stars i {
  color: #ffc107;
  margin: 0 1px;
}

.testimonials .testimonial-item .quote-icon-left,
.testimonials .testimonial-item .quote-icon-right {
  color: #e1e1e1;
  font-size: 26px;
}

.testimonials .testimonial-item .quote-icon-left {
  display: inline-block;
  left: -5px;
  position: relative;
}

.testimonials .testimonial-item .quote-icon-right {
  display: inline-block;
  right: -5px;
  position: relative;
  top: 10px;
}

.testimonials .testimonial-item p {
  font-style: italic;
  margin: 0;
  text-align: center;
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
    <h2>Müşteri Yorumları</h2>
    <p>Müşterilerimizin bizim hakkımızda söyledikleri</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row gy-4">

      @foreach($testimonials as $index => $testimonial)
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
          <div class="testimonial-item">
            @if($testimonial->image)
              <img src="{{ asset('storage/' . $testimonial->image) }}" class="testimonial-img" alt="{{ $testimonial->name }}">
            @endif
            <h3>{{ $testimonial->name }}</h3>
            <h4>{{ $testimonial->position }}</h4>
            @if($testimonial->rating)
              <div class="stars">
                @for($i = 0; $i < $testimonial->rating; $i++)
                  <i class="bi bi-star-fill"></i>
                @endfor
              </div>
            @endif
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>{{ $testimonial->content }}</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->
      @endforeach

    </div>

  </div>

</section><!-- /Testimonials Section -->
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
