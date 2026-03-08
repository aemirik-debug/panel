@extends('themes.theme_1.layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->content), 160))
@section('body-class', 'blog-details-page')

@section('content')

<!-- Page Title -->
<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">{{ $post->title }}</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li><a href="{{ url('/blog') }}">Blog</a></li>
        <li class="current">{{ Str::limit($post->title, 40) }}</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<div class="container">
  <div class="row">

    <div class="col-lg-8">

      <!-- Blog Details Section -->
      <section id="blog-details" class="blog-details section">
        <div class="container">

          <article class="article">

            <h2 class="title">{{ $post->title }}</h2>

            <div class="meta-top">
              <ul>
                <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="#"><time datetime="{{ $post->created_at->format('Y-m-d') }}">{{ $post->created_at->format('d M Y') }}</time></a></li>
              </ul>
            </div><!-- End meta top -->

            <div class="row g-4 align-items-start mb-4">
              @if($post->featured_image)
                <div class="col-md-5">
                  <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid rounded">
                </div>
              @endif
              <div class="{{ $post->featured_image ? 'col-md-7' : 'col-12' }}">
                <p class="mb-0">
                  {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 280) }}
                </p>
              </div>
            </div>

            <div class="content">
              {!! $post->content !!}
            </div><!-- End post content -->

          </article>

        </div>
      </section><!-- /Blog Details Section -->

      <!-- Comments Section (Opsiyonel) -->
      @if(isset($comments) && $comments->count() > 0)
      <section id="blog-comments" class="blog-comments section">
        <div class="container">
          <h4 class="comments-count">{{ $comments->count() }} Yorum</h4>

          @foreach($comments as $comment)
            <div id="comment-{{ $comment->id }}" class="comment">
              <div class="d-flex">
                <div class="comment-img"><img src="{{ asset('themes/theme_1/assets/img/blog/comments-1.jpg') }}" alt=""></div>
                <div>
                  <h5><a href="#">{{ $comment->name }}</a></h5>
                  <time datetime="{{ $comment->created_at->format('Y-m-d') }}">{{ $comment->created_at->format('d M Y') }}</time>
                  <p>{{ $comment->content }}</p>
                </div>
              </div>
            </div>
          @endforeach

        </div>
      </section>
      @endif

    </div>

    <div class="col-lg-4 sidebar">

      <div class="widgets-container">

        <!-- Recent Posts Widget -->
        @if(isset($recentPosts) && $recentPosts->count() > 0)
        <div class="recent-posts-widget widget-item">
          <h3 class="widget-title">Diğer Blog Yazıları</h3>
          <ul class="list-unstyled mt-3 mb-0">
          @foreach($recentPosts as $recent)
            <li class="mb-2">
              <a href="{{ url('/blog/' . $recent->slug) }}">{{ $recent->title }}</a>
            </li>
          @endforeach
          </ul>
        </div>
        @endif

      </div>

    </div>

  </div>
</div>

@endsection
