<section id="call-to-action" class="call-to-action section light-background">
  <div class="container">
    <div class="row" data-aos="zoom-in" data-aos-delay="100">
      <div class="col-xl-9 text-center text-xl-start">
        <h3>{{ $settings->cta_title ?? 'Harekete Gec' }}</h3>
        <p>{{ $settings->cta_description ?? 'Bizimle calismaya baslamak icin hemen iletisime gecin' }}</p>
      </div>
      <div class="col-xl-3 cta-btn-container text-center">
        <a class="cta-btn align-middle" href="{{ url('/iletisim') }}">Iletisim</a>
      </div>
    </div>
  </div>
</section>
