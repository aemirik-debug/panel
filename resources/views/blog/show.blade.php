@extends('layouts.app')

@section('title')
    {{ $post->meta_title ?? $post->title }}
@endsection

@section('meta_description')
    {{ $post->meta_description ?? $post->excerpt }}
@endsection

@section('content')
<div class="container py-5">

    <h1>{{ $post->title }}</h1>

    @if($post->featured_image)
        <img src="{{ asset('storage/' . $post->featured_image) }}"
             class="img-fluid mb-4">
    @endif

    <div class="mb-4">
        {!! $post->content !!}
    </div>

</div>
@endsection
