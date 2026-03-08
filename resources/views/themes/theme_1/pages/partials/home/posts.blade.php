@if(isset($posts) && $posts->count() > 0)
<section id="recent-posts" class="recent-posts section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Son Yazilar</h2>
    <p>Blog'dan son haberler</p>
  </div>

  <div class="container">
    <div class="row gy-4">
      @foreach($posts->take(3) as $index => $post)
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
          <article>
            <div class="post-img">
              <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="img-fluid">
            </div>

            <p class="post-category">{{ $post->category->name ?? 'Genel' }}</p>

            <h2 class="title">
              <a href="{{ url('/blog/' . $post->slug) }}">{{ $post->title }}</a>
            </h2>

            <div class="d-flex align-items-center">
              <div class="post-meta">
                <p class="post-date">
                  <time datetime="{{ $post->created_at->format('Y-m-d') }}">{{ $post->created_at->format('d M Y') }}</time>
                </p>
              </div>
            </div>
          </article>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif
