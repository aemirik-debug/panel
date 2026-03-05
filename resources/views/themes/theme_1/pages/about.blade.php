@extends('themes.theme_1.layouts.app')

@section('title', 'Hakkımızda - ' . ($settings->site_name ?? 'Hakkımızda'))
@section('body-class', 'about-page')

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Hakkımızda</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Hakkımızda</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<!-- About Section -->
<section id="about" class="about section">

  <div class="container">

    <div class="row gy-4">

      <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
        <p class="who-we-are">{{ $settings->about_title ?? 'Biz Kimiz' }}</p>
        <h3>{{ $settings->about_subtitle ?? 'Misyonumuz ve Vizyonumuz' }}</h3>
        <p class="fst-italic">
          {{ $settings->about_description ?? 'Sektörde uzun yıllara dayanan deneyimimiz ve profesyonel ekibimizle müşterilerimize en kaliteli hizmeti sunuyoruz.' }}
        </p>
        
        @if($settings && $settings->about_features)
          <ul>
            @foreach(json_decode($settings->about_features, true) ?? [] as $feature)
              <li><i class="bi bi-check-circle"></i> <span>{{ $feature }}</span></li>
            @endforeach
          </ul>
        @endif

        @if($settings && $settings->about_content)
          <div class="mt-4">
            {!! $settings->about_content !!}
          </div>
        @endif
      </div>

      <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
        @if($settings && $settings->about_image)
          <img src="{{ asset('storage/' . $settings->about_image) }}" class="img-fluid" alt="Hakkımızda">
        @else
          <img src="{{ asset('themes/theme_1/assets/img/about.jpg') }}" class="img-fluid" alt="Hakkımızda">
        @endif
      </div>

    </div>

  </div>

</section><!-- /About Section -->

<!-- Stats Section -->
@if($settings && $settings->show_stats)
<section id="stats" class="stats section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="{{ $settings->stat_clients ?? 232 }}" data-purecounter-duration="1" class="purecounter"></span>
          <p>Mutlu Müşteri</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="{{ $settings->stat_projects ?? 521 }}" data-purecounter-duration="1" class="purecounter"></span>
          <p>Tamamlanan Proje</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="{{ $settings->stat_hours ?? 1453 }}" data-purecounter-duration="1" class="purecounter"></span>
          <p>Destek Saati</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="{{ $settings->stat_team ?? 32 }}" data-purecounter-duration="1" class="purecounter"></span>
          <p>Ekip Üyesi</p>
        </div>
      </div><!-- End Stats Item -->

    </div>

  </div>

</section><!-- /Stats Section -->
@endif

<!-- Team Section -->
@if(isset($team) && $team->count() > 0)
<section id="team" class="team section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Ekibimiz</h2>
    <p>Profesyonel ekibimizle size en iyi hizmeti sunuyoruz.</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row gy-5">

      @foreach($team as $member)
        <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
          <div class="member-img">
            <img src="{{ asset('storage/' . $member->image) }}" class="img-fluid" alt="{{ $member->name }}">
            @if($member->social_links)
              <div class="social">
                @foreach(json_decode($member->social_links, true) ?? [] as $social => $link)
                  <a href="{{ $link }}"><i class="bi bi-{{ $social }}"></i></a>
                @endforeach
              </div>
            @endif
          </div>
          <div class="member-info text-center">
            <h4>{{ $member->name }}</h4>
            <span>{{ $member->position }}</span>
            @if($member->description)
              <p>{{ $member->description }}</p>
            @endif
          </div>
        </div><!-- End Team Member -->
      @endforeach

    </div>

  </div>

</section><!-- /Team Section -->
@endif

@endsection
