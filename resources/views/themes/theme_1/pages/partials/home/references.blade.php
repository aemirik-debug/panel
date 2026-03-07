<section id="referanslar-carousel" class="referanslar-carousel section">
  <div class="container section-title" data-aos="fade-up">
    <h2>{{ $settings->references_section_title ?? 'Referanslar ve Musterilerimiz' }}</h2>
    <p>{{ $settings->references_section_description ?? 'Calistigimiz basarili projelerimiz' }}</p>
  </div>

  <div class="container" data-aos="fade-up">
    @if(isset($references) && $references->count() > 0)
      <div class="swiper referanslar-swiper">
        <div class="swiper-wrapper">
          @foreach($references as $reference)
            <div class="swiper-slide">
              <div class="reference-item">
                <div class="reference-image">
                  @php
                    $referenceImage = $reference->image ?? $reference->featured_image;
                  @endphp
                  <img src="{{ asset('storage/' . $referenceImage) }}" alt="{{ $reference->title }}" class="img-fluid rounded">
                </div>
                <div class="reference-info text-center mt-3">
                  <h4>{{ $reference->title }}</h4>
                  <p class="text-muted">{{ Str::limit($reference->description, 80) }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
      </div>
    @else
      <div class="text-center py-4">
        <p class="text-muted mb-0">Henuz referans eklenmedi.</p>
      </div>
    @endif
  </div>
</section>
