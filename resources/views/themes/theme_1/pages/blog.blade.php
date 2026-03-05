@extends('themes.theme_1.layouts.app')

@section('title', 'Blog - ' . ($settings->site_name ?? 'Blog'))
@section('body-class', 'blog-page')

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
              <div class="col-12">
                <article>

                  @if($post->image_path)
                    <div class="post-img">
                      <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="img-fluid">
                    </div>
                  @endif

                  <h2 class="title">
                    <a href="{{ url('/blog/' . $post->slug) }}">{{ $post->title }}</a>
                  </h2>

                  <div class="meta-top">
                    <ul>
                      <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="#">{{ $post->author ?? 'Admin' }}</a></li>
                      <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="#"><time datetime="{{ $post->created_at->format('Y-m-d') }}">{{ $post->created_at->format('d M Y') }}</time></a></li>
                      @if($post->category)
                        <li class="d-flex align-items-center"><i class="bi bi-folder2"></i> <a href="#">{{ $post->category->name }}</a></li>
                      @endif
                    </ul>
                  </div>

                  <div class="content">
                    <p>{{ Str::limit(strip_tags($post->content), 300) }}</p>
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

        <!-- Categories Widget -->
        @if(isset($categories) && $categories->count() > 0)
        <div class="categories-widget widget-item">
          <h3 class="widget-title">Kategoriler</h3>
          <ul class="mt-3">
            @foreach($categories as $category)
              <li><a href="{{ url('/blog?category=' . $category->slug) }}">{{ $category->name }} <span>({{ $category->posts_count ?? 0 }})</span></a></li>
            @endforeach
          </ul>
        </div>
        @endif

        <!-- Recent Posts Widget -->
        @if(isset($recentPosts) && $recentPosts->count() > 0)
        <div class="recent-posts-widget widget-item">
          <h3 class="widget-title">Son Yazılar</h3>
          @foreach($recentPosts as $recent)
            <div class="post-item">
              <img src="{{ asset('storage/' . $recent->image_path) }}" alt="{{ $recent->title }}" class="flex-shrink-0">
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
