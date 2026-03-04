@extends('layouts.app')

@section('content')

{{-- SLIDER VARSA SLIDER --}}
@if(isset($sliders) && $sliders->count() > 0)

<div id="mainSlider" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">

        @foreach($sliders as $key => $slider)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $slider->image) }}"
                     class="d-block w-100"
                     alt="{{ $slider->title }}"
                     style="height: 70vh; object-fit: cover;">

                <div class="carousel-caption d-none d-md-block">
                    <div class="bg-dark bg-opacity-50 rounded p-4">
                        <h2 class="display-4 fw-bold">{{ $slider->title }}</h2>
                        <p class="lead">{{ $slider->subtitle }}</p>

                        @if($slider->button_url)
                            <a href="{{ $slider->button_url }}"
                               class="btn btn-primary px-4 py-2 rounded-pill">
                                {{ $slider->button_text ?? 'İncele' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

{{-- SLIDER YOKSA HERO GÖSTER --}}
@else

<section class="hero-section text-white text-center d-flex align-items-center"
    style="
        min-height: 70vh;
        background: url('{{ $settings?->hero_background ? asset('storage/' . $settings->hero_background) : '' }}') center/cover no-repeat;
    ">

    <div class="container position-relative z-2">

        <h1 class="display-4 fw-bold mb-3">
            {{ $settings?->hero_title ?? 'Kurumsal Web Çözümleri' }}
        </h1>

        <p class="lead mb-4">
            {{ $settings?->hero_subtitle ?? 'Dijital dünyada güçlü bir başlangıç yapın.' }}
        </p>

        @if(!empty($settings?->hero_button_text))
            <a href="{{ $settings->hero_button_link ?? '#' }}"
               class="btn btn-primary btn-lg px-4 rounded-pill">
                {{ $settings->hero_button_text }}
            </a>
        @endif

    </div>
</section>

@endif


{{-- HİZMETLER --}}
<div class="container py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold">Hizmetlerimiz</h2>
        <div class="bg-primary mx-auto" style="height: 3px; width: 60px;"></div>
    </div>

    <div class="row g-4">

        @foreach($services as $service)
            <div class="col-md-4">

                <div class="card h-100 shadow-sm border-0 service-card">

                    <div class="card-body text-center p-4">

                        <div class="text-primary mb-3" style="font-size: 2.5rem;">
                            <i class="{{ $service->icon ?? 'fas fa-cog' }}"></i>
                        </div>

                        <h4 class="card-title h5 fw-bold">
                            {{ $service->title }}
                        </h4>

                        <p class="card-text text-muted small">
                            {{ $service->short_description }}
                        </p>

                    </div>

                    <div class="card-footer bg-transparent border-0 pb-4 text-center">
                        <a href="{{ url('/servis/' . $service->slug) }}"
                           class="btn btn-outline-primary btn-sm px-4 rounded-pill">
                            Detaylı Bilgi
                        </a>
                    </div>

                </div>

            </div>
        @endforeach

    </div>
</div>


<style>
.hero-section {
    position: relative;
}

.hero-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
}

.service-card {
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
</style>

@endsection
