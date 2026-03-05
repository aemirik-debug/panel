<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // ANA SAYFA
    Route::get('/', function () {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        $sliders = \App\Models\Slider::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        $settings = \App\Models\Setting::first();
        
        // Galleries (opsiyonel)
        $galleries = \App\Models\Gallery::where('is_active', true)->get();
        
        // Posts (opsiyonel)
        $posts = \App\Models\Post::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view("themes.{$theme}.pages.welcome", compact('services', 'sliders', 'settings', 'galleries', 'posts'));
    });
    
    // SERVİS DETAY
    Route::get('/servis/{slug}', function ($slug) {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $service = \App\Models\Service::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $settings = \App\Models\Setting::first();
        $services = \App\Models\Service::where('is_active', true)->get();
        
        return view("themes.{$theme}.pages.service-detail", compact('service', 'settings', 'services'));
    })->name('service.detail');
    
    // HİZMETLER LİSTESİ
    Route::get('/hizmetler', function () {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        $settings = \App\Models\Setting::first();
        
        return view("themes.{$theme}.pages.services", compact('services', 'settings'));
    })->name('services.index');
    
    // BLOG LİSTESİ
    Route::get('/blog', function () {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $query = \App\Models\Post::where('is_active', true);
        
        if (request('q')) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . request('q') . '%')
                  ->orWhere('content', 'like', '%' . request('q') . '%');
            });
        }
        
        if (request('category')) {
            $query->whereHas('category', function($q) {
                $q->where('slug', request('category'));
            });
        }
        
        $posts = $query->orderBy('created_at', 'desc')->paginate(9);
        $recentPosts = \App\Models\Post::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $settings = \App\Models\Setting::first();
        
        return view("themes.{$theme}.pages.blog", compact('posts', 'recentPosts', 'settings'));
    })->name('blog.index');
    
    // BLOG DETAY
    Route::get('/blog/{slug}', function ($slug) {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $post = \App\Models\Post::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $recentPosts = \App\Models\Post::where('is_active', true)
            ->where('id', '!=', $post->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $settings = \App\Models\Setting::first();
        
        return view("themes.{$theme}.pages.blog-detail", compact('post', 'recentPosts', 'settings'));
    })->name('blog.detail');
    
    // PORTFOLYO
    Route::get('/portfolyo', function () {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $galleries = \App\Models\Gallery::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        $settings = \App\Models\Setting::first();
        
        return view("themes.{$theme}.pages.portfolio", compact('galleries', 'settings'));
    })->name('portfolio.index');
    
    // İLETİŞİM
    Route::get('/iletisim', function () {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $settings = \App\Models\Setting::first();
        
        return view("themes.{$theme}.pages.contact", compact('settings'));
    })->name('contact.index');
    
    // İLETİŞİM FORM
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
    
    // HAKKIMIZDA
    Route::get('/hakkimizda', function () {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $settings = \App\Models\Setting::first();
        
        return view("themes.{$theme}.pages.about", compact('settings'));
    })->name('about');
    
    // REFERANSLAR
    Route::get('/referanslar', function () {
        $tenant = tenant();
        $theme = $tenant->theme ?? 'theme_1';
        
        $clients = \App\Models\Gallery::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        $testimonials = \App\Models\Comment::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $settings = \App\Models\Setting::first();
        
        return view("themes.{$theme}.pages.references", compact('clients', 'testimonials', 'settings'));
    })->name('references.index');
});
