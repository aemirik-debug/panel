@extends('themes.theme_1.layouts.app')

@section('title', 'İletişim - ' . ($settings->site_name ?? 'İletişim'))
@section('body-class', 'contact-page')

@push('styles')
<style>
  .map-container iframe {
    width: 100%;
    height: 300px;
    border: 0;
    border-radius: 8px;
  }
</style>
@endpush

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">İletişim</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">İletişim</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- Contact Section -->
<section id="contact" class="contact section">

  @if(isset($maps) && $maps->count() > 1)
    {{-- Birden fazla harita varsa üstte 3'lü grid olarak göster --}}
    <div class="container mb-5" data-aos="fade">
      <div class="row gy-4">
        @foreach($maps as $map)
          <div class="col-lg-4 col-md-6">
            <h5 class="mb-3">{{ $map->title }}</h5>
            <div class="map-container" style="position: relative; overflow: hidden; border-radius: 8px;">
              {!! $map->iframe_code !!}
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <div class="container" data-aos="fade">

    <div class="row gy-5 gx-lg-5">

      <div class="col-lg-4">

        <div class="info">
          <h3>İletişime Geç</h3>
          <p>{{ $settings->contact_description ?? 'Bizimle iletişime geçmek için aşağıdaki bilgileri kullanabilirsiniz.' }}</p>

          @if($settings && $settings->address)
            <div class="info-item d-flex">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h4>Adres:</h4>
                <p>{{ $settings->address }}</p>
              </div>
            </div>
          @endif

          @if($settings && $settings->email)
            <div class="info-item d-flex">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h4>Email:</h4>
                <p>{{ $settings->email }}</p>
              </div>
            </div>
          @endif

          @if($settings && $settings->phone)
            <div class="info-item d-flex">
              <i class="bi bi-phone flex-shrink-0"></i>
              <div>
                <h4>Telefon:</h4>
                <p>{{ $settings->phone }}</p>
              </div>
            </div>
          @endif

          @if(isset($maps) && $maps->count() === 1)
            {{-- Tek harita varsa buraya yerleştir --}}
            <div class="mt-4">
              <h5 class="mb-3">{{ $maps->first()->title }}</h5>
              <div class="map-container" style="position: relative; overflow: hidden; border-radius: 8px;">
                {!! $maps->first()->iframe_code !!}
              </div>
            </div>
          @endif

        </div>

      </div>

      <div class="col-lg-8">
        <form action="{{ url('/iletisim') }}" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
          @csrf

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              Lütfen form alanlarını kontrol edin.
            </div>
          @endif

          <div class="row gy-4">

            <div class="col-md-6">
              <input type="text" name="name" class="form-control" placeholder="Adınız" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6">
              <input type="email" class="form-control" name="email" placeholder="Email Adresiniz" value="{{ old('email') }}" required>
            </div>

            <div class="col-md-12">
              <input type="text" class="form-control" name="phone" placeholder="Telefon (opsiyonel)" value="{{ old('phone') }}">
            </div>

            <div class="col-md-12">
              <input type="text" class="form-control" name="subject" placeholder="Konu" value="{{ old('subject') }}" required>
            </div>

            <div class="col-md-12">
              <textarea class="form-control" name="message" rows="8" placeholder="Mesajınız" required>{{ old('message') }}</textarea>
            </div>

            <div class="col-md-12 text-center">
              <div class="loading">Yükleniyor</div>
              <div class="error-message"></div>
              <div class="sent-message">Mesajınız gönderildi. Teşekkürler!</div>

              <button type="submit">Mesaj Gönder</button>
            </div>

          </div>
        </form>
      </div><!-- End Contact Form -->

    </div>

  </div>

</section><!-- /Contact Section -->

@endsection
