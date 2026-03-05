<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service; 
use App\Http\Controllers\BlogController;

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');


// 1. ANA SAYFA (Central - Fallback to theme_1)
Route::get('/', function () {
    $theme = 'theme_1'; // Central domain için fallback tema
    
    $services = \App\Models\Service::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();
    
    $sliders = \App\Models\Slider::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();
    
    $settings = \App\Models\Setting::first();
    
    $galleries = \App\Models\Gallery::where('is_active', true)->get();
    $posts = \App\Models\Post::where('is_active', true)->orderBy('created_at', 'desc')->get();
    
    return view("themes.{$theme}.pages.welcome", compact('services', 'sliders', 'settings', 'galleries', 'posts'));
});

// 2. HİZMET DETAY
Route::get('/servis/{slug}', function ($slug) {
    $theme = 'theme_1';
    
    $service = \App\Models\Service::where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    $settings = \App\Models\Setting::first();
    $services = \App\Models\Service::where('is_active', true)->get();

    return view("themes.{$theme}.pages.service-detail", compact('service', 'settings', 'services'));
})->name('service.detail');

// 3. HİZMETLER LİSTESİ
Route::get('/hizmetler', function () {
    $theme = 'theme_1';
    
    $services = \App\Models\Service::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();
    
    $settings = \App\Models\Setting::first();
    
    return view("themes.{$theme}.pages.services", compact('services', 'settings'));
})->name('services.index');

// 4. PORTFOLYO
Route::get('/portfolyo', function () {
    $theme = 'theme_1';
    
    $galleries = \App\Models\Gallery::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();
    
    $settings = \App\Models\Setting::first();
    
    return view("themes.{$theme}.pages.portfolio", compact('galleries', 'settings'));
})->name('portfolio.index');

// 5. İLETİŞİM
Route::get('/iletisim', function () {
    $theme = 'theme_1';
    
    $settings = \App\Models\Setting::first();
    
    return view("themes.{$theme}.pages.contact", compact('settings'));
})->name('contact.index');

Route::post('/iletisim', function () {
    $validated = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);
    
    \App\Models\Contact::create($validated);
    
    return back()->with('success', 'Mesajınız başarıyla gönderildi!');
})->name('contact.store');

// 6. HAKKIMIZDA
Route::get('/hakkimizda', function () {
    $theme = 'theme_1';
    
    $settings = \App\Models\Setting::first();
    
    return view("themes.{$theme}.pages.about", compact('settings'));
})->name('about');

// 7. REFERANSLAR
Route::get('/referanslar', function () {
    $theme = 'theme_1';
    
    $clients = \App\Models\Gallery::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();
    
    $testimonials = \App\Models\Comment::where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();
    
    $settings = \App\Models\Setting::first();
    
    return view("themes.{$theme}.pages.references", compact('clients', 'testimonials', 'settings'));
})->name('references.index');