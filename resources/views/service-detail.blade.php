@extends('layouts.app')

@section('title')
    {{ $service->meta_title ?? $service->title }}
@endsection

@section('meta_description')
    {{ $service->meta_description ?? $service->short_description }}
@endsection

@section('content')
<div class="container" style="max-width: 1100px; margin: 40px auto; padding: 0 20px; font-family: sans-serif;">
    <nav style="margin-bottom: 20px; font-size: 0.9rem; color: #888;">
        <ul style="list-style: none; padding: 0; display: flex; gap: 8px; align-items: center;">
            <li>
                <a href="{{ url('/') }}" style="color: #007bff; text-decoration: none;">Ana Sayfa</a>
            </li>
            <li style="color: #ccc;">/</li>
            <li>
                <span style="color: #888;">Hizmetler</span>
            </li>
            <li style="color: #ccc;">/</li>
            <li style="color: #333; font-weight: bold;">
                {{ $service->title }}
            </li>
        </ul>
    </nav>
    <div style="display: flex; flex-wrap: wrap; gap: 40px; align-items: flex-start;">
        
        {{-- Sol Taraf: Görsel --}}
        @if($service->image)
        <div style="flex: 1; min-width: 300px;">
            <img src="{{ asset('storage/' . $service->image) }}" 
                 alt="{{ $service->title }}" 
                 style="width: 100%; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        </div>
        @endif

        {{-- Sağ Taraf: İçerik --}}
        <div style="flex: 1.5; min-width: 300px;">
            <h1 style="font-size: 2.5rem; color: #222; margin-top: 0;">{{ $service->title }}</h1>
            
            @if($service->short_description)
                <p style="font-size: 1.2rem; color: #555; line-height: 1.6; font-style: italic; border-left: 4px solid #007bff; padding-left: 15px; margin: 20px 0;">
                    {{ $service->short_description }}
                </p>
            @endif

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <div style="font-size: 1.1rem; color: #444; line-height: 1.8;">
                {!! nl2br(e($service->description)) !!}
            </div>

            <div style="margin-top: 30px;">
                <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 20px; background: #222; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;">
                    ← Diğer Hizmetler
                </a>
            </div>
        </div>

    </div>
</div>
@endsection