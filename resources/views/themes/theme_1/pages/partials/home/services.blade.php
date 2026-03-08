<section id="services" class="services section">
  <div class="container section-title" data-aos="fade-up">
    <h2>{{ $settings->services_section_title ?? 'Hizmetlerimiz' }}</h2>
    <p>{{ $settings->services_description ?? 'Sizin icin en iyi hizmetleri sunuyoruz' }}</p>
  </div>

  <div class="container">
    <div class="row gy-4">
      @if(($services ?? collect())->count() > 0)
        @foreach($services as $index => $service)
          @php
            $iconClass = trim((string) ($service->icon ?? ''));
            $iconClass = match ($iconClass) {
              'fa-solid fa-code' => 'bi bi-code-slash',
              'fa-solid fa-shirt' => 'bi bi-bag',
              'fa-solid fa-building' => 'bi bi-building',
              'fa-solid fa-stethoscope' => 'bi bi-heart-pulse',
              'fa-solid fa-truck' => 'bi bi-truck',
              'fa-solid fa-utensils' => 'bi bi-cup-hot',
              'fa-solid fa-store' => 'bi bi-shop',
              'fa-solid fa-chart-line' => 'bi bi-graph-up-arrow',
              default => $iconClass,
            };

            if ($iconClass === '') {
                $iconClass = 'bi bi-activity';
            } elseif (!str_contains($iconClass, 'bi ') && !str_contains($iconClass, 'bi-')) {
                $iconClass = 'bi bi-activity';
            } elseif (str_starts_with($iconClass, 'bi-')) {
                $iconClass = 'bi ' . $iconClass;
            }
          @endphp

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="{{ $iconClass }}"></i>
              </div>
              <a href="{{ url('/servis/' . $service->slug) }}" class="stretched-link">
                <h3>{{ $service->title }}</h3>
              </a>
              <p>{{ $service->short_description }}</p>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-12 text-center">
          <p>Henuz hizmet eklenmedi.</p>
        </div>
      @endif
    </div>
  </div>
</section>
