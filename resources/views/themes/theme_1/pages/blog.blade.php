@extends('themes.theme_1.layouts.app')

@section('title', 'Blog - ' . ($settings->site_name ?? 'Blog'))
@section('body-class', 'blog-page')

@push('styles')
<style>
  .blog-posts .blog-card {
    overflow: hidden;
  }

  .blog-posts .blog-card .post-img {
    margin: 0 0 16px 0;
    max-height: 260px;
    border-radius: 10px;
    overflow: hidden;
  }

  .blog-posts .blog-card .post-img img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    display: block;
  }

  .blog-posts .blog-card .title {
    margin: 12px 0;
    font-size: 22px;
  }
</style>
@endpush

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Blog</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Blog</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<div class="container">
  <div class="row">

    <div class="col-lg-8">

      <!-- Blog Posts Section -->
      <section id="blog-posts" class="blog-posts section">

        <div class="container">

          <div class="row gy-4">

            @forelse($posts as $post)
              <div class="col-md-6">
                <article class="blog-card h-100 p-3 border rounded-3 shadow-sm bg-white">

                  @if($post->featured_image)
                    <div class="post-img">
                      <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid">
                    </div>
                  @endif

                  <h2 class="title">
                    <a href="{{ url('/blog/' . $post->slug) }}">{{ $post->title }}</a>
                  </h2>

                  <div class="meta-top">
                    <ul>
                      <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="#"><time datetime="{{ $post->created_at->format('Y-m-d') }}">{{ $post->created_at->format('d M Y') }}</time></a></li>
                    </ul>
                  </div>

                  <div class="content">
                    <p>{{ Str::limit(strip_tags($post->content), 160) }}</p>
                    <div class="read-more">
                      <a href="{{ url('/blog/' . $post->slug) }}">Devamını Oku</a>
                    </div>
                  </div>

                </article>
              </div>
            @empty
              <div class="col-12 text-center">
                <p>Henüz blog yazısı yayınlanmamış.</p>
              </div>
            @endforelse

          </div>

          <!-- Pagination -->
          @if($posts->hasPages())
            <div class="pagination d-flex justify-content-center mt-5">
              {{ $posts->links() }}
            </div>
          @endif

        </div>

      </section><!-- /Blog Posts Section -->

    </div>

    <div class="col-lg-4 sidebar">

      <!-- Sidebar Widgets -->
      <div class="widgets-container">

        <!-- Search Widget -->
        <div class="search-widget widget-item">
          <h3 class="widget-title">Ara</h3>
          <form action="{{ url('/blog') }}" method="GET">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Arama...">
            <button type="submit"><i class="bi bi-search"></i></button>
          </form>
        </div>

        <!-- Recent Posts Widget -->
        @if(isset($recentPosts) && $recentPosts->count() > 0)
        <div class="recent-posts-widget widget-item">
          <h3 class="widget-title">Son Yazılar</h3>
          @foreach($recentPosts as $recent)
            <div class="post-item">
              @if($recent->featured_image)
                <img src="{{ asset('storage/' . $recent->featured_image) }}" alt="{{ $recent->title }}" class="flex-shrink-0">
              @endif
              <div>
                <h4><a href="{{ url('/blog/' . $recent->slug) }}">{{ $recent->title }}</a></h4>
                <time datetime="{{ $recent->created_at->format('Y-m-d') }}">{{ $recent->created_at->format('d M Y') }}</time>
              </div>
            </div>
          @endforeach
        </div>
        @endif

      </div>

    </div>

  </div>
</div>

@endsection
