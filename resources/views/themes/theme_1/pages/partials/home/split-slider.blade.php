@if(($allSplitSlides ?? collect())->count() > 0)
<section id="split-slider" class="split-slider section">
  <div class="container" data-aos="fade-up">
    <div class="row g-3 align-items-stretch">
      <div class="col-lg-8">
        <div id="split-left-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4500">
          <div class="carousel-inner">
            @foreach($allSplitSlides as $index => $slide)
              <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <div class="split-left-slide">
                  <img src="{{ asset('storage/' . $slide['image']) }}" alt="{{ $slide['title'] ?? 'Slider' }}">
                  @if(!empty($slide['title']) || !empty($slide['subtitle']))
                    <div class="split-caption">
                      <div>{{ trim(($slide['title'] ?? '') . ' ' . ($slide['subtitle'] ?? '')) }}</div>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
          @if($allSplitSlides->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#split-left-carousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#split-left-carousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          @endif
        </div>
      </div>

      <div class="col-lg-4">
        @if(!empty($splitStaticSource?->right_top_image))
          <div class="split-right-card">
            <img src="{{ asset('storage/' . $splitStaticSource->right_top_image) }}" alt="Sag Ust Gorsel">
            @if(!empty($splitStaticSource->right_top_caption))
              <div class="split-caption">{{ $splitStaticSource->right_top_caption }}</div>
            @endif
          </div>
        @endif

        @if(!empty($splitStaticSource?->right_bottom_image))
          <div class="split-right-card mb-0">
            <img src="{{ asset('storage/' . $splitStaticSource->right_bottom_image) }}" alt="Sag Alt Gorsel">
            @if(!empty($splitStaticSource->right_bottom_caption))
              <div class="split-caption">{{ $splitStaticSource->right_bottom_caption }}</div>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endif
