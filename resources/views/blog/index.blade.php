@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">

        {{-- SOL TARAF: BLOG LİSTE --}}
        <div class="col-md-8">

            <h1 class="mb-4">Blog</h1>

            @foreach($posts as $post)
                <div class="card mb-4">

                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top">
                    @endif

                    <div class="card-body">
                        <h5>{{ $post->title }}</h5>
                        <p>{{ $post->excerpt }}</p>

                        <a href="{{ route('blog.show', $post->slug) }}"
                           class="btn btn-primary">
                           Devamını Oku
                        </a>
                    </div>
                </div>
            @endforeach

            {{ $posts->withQueryString()->links() }}

        </div>

        {{-- SAĞ TARAF: KATEGORİLER --}}
        <div class="col-md-4">

            <h4>Kategoriler</h4>

            <ul class="list-group">
                <li class="list-group-item {{ !$categorySlug ? 'active' : '' }}">
                    <a href="{{ route('blog.index') }}"
                       class="{{ !$categorySlug ? 'text-white' : '' }}">
                        Tümü
                    </a>
                </li>

                @foreach($categories as $category)
                    <li class="list-group-item {{ $categorySlug === $category->slug ? 'active' : '' }}">
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                           class="{{ $categorySlug === $category->slug ? 'text-white' : '' }}">
                            {{ $category->name }} ({{ $category->posts_count }})
                        </a>
                    </li>
                @endforeach
            </ul>

        </div>

    </div>
</div>
@endsection