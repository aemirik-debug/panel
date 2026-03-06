<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service; 
use App\Http\Controllers\BlogController;

Route::get('/blog', function () {
    $theme = 'theme_1';

    $query = \App\Models\Post::query()->where('is_published', true);

    if (request('q')) {
        $query->where(function ($q) {
            $q->where('title', 'like', '%' . request('q') . '%')
                ->orWhere('content', 'like', '%' . request('q') . '%');
        });
    }

    $posts = $query->orderBy('published_at', 'desc')->paginate(9);
    $recentPosts = \App\Models\Post::where('is_published', true)
        ->orderBy('published_at', 'desc')
        ->limit(5)
        ->get();

    $settings = \App\Models\Setting::first();

    return view("themes.{$theme}.pages.blog", compact('posts', 'recentPosts', 'settings'));
})->name('blog.index');

Route::get('/blog/{slug}', function ($slug) {
    $theme = 'theme_1';

    $post = \App\Models\Post::where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();

    $recentPosts = \App\Models\Post::where('is_published', true)
        ->where('id', '!=', $post->id)
        ->orderBy('published_at', 'desc')
        ->limit(5)
        ->get();

    $settings = \App\Models\Setting::first();

    return view("themes.{$theme}.pages.blog-detail", compact('post', 'recentPosts', 'settings'));
})->name('blog.show');


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
    
    $galleries = \App\Models\Gallery::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();

    $references = $galleries->isNotEmpty()
        ? $galleries
        : \App\Models\Portfolio::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

    $posts = \App\Models\Post::where('is_active', true)->orderBy('created_at', 'desc')->get();
    
    return view("themes.{$theme}.pages.welcome", compact('services', 'sliders', 'settings', 'galleries', 'references', 'posts'));
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

// 3.1 URUNLER LISTESI
Route::get('/urunler', function () {
    $theme = 'theme_1';

    $activeCategorySlug = request('category');

    $categories = \App\Models\Category::query()
        ->where('is_active', true)
        ->whereNull('parent_id')
        ->with([
            'children' => fn ($query) => $query->where('is_active', true)->orderBy('name'),
        ])
        ->orderBy('name')
        ->get();

    $filterCategoryIds = [];
    if ($activeCategorySlug) {
        $selectedCategory = \App\Models\Category::query()
            ->where('slug', $activeCategorySlug)
            ->where('is_active', true)
            ->with('children:id,parent_id')
            ->first();

        if ($selectedCategory) {
            $filterCategoryIds = array_merge(
                [$selectedCategory->id],
                $selectedCategory->children->pluck('id')->all(),
            );
        }
    }

    $products = \App\Models\Product::query()
        ->where('is_active', true)
        ->when(!empty($filterCategoryIds), function ($query) use ($filterCategoryIds) {
            $query->whereHas('categories', function ($categoryQuery) use ($filterCategoryIds) {
                $categoryQuery->whereIn('categories.id', $filterCategoryIds);
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    $settings = \App\Models\Setting::first();

    return view("themes.{$theme}.pages.products", compact('products', 'categories', 'activeCategorySlug', 'settings'));
})->name('products.index');

Route::get('/urunler/{slug}', function ($slug) {
    $theme = 'theme_1';

    $product = \App\Models\Product::query()
        ->where('slug', $slug)
        ->where('is_active', true)
        ->with(['categories', 'reviews' => fn ($query) => $query->where('is_active', true)])
        ->firstOrFail();

    $relatedProducts = \App\Models\Product::query()
        ->where('is_active', true)
        ->where('id', '!=', $product->id)
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

    $categories = \App\Models\Category::query()
        ->where('is_active', true)
        ->whereNull('parent_id')
        ->with([
            'children' => fn ($query) => $query->where('is_active', true)->orderBy('name'),
        ])
        ->orderBy('name')
        ->get();

    $activeCategorySlug = optional($product->categories->first())->slug;
    $reviews = $product->reviews;
    $averageRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;

    $settings = \App\Models\Setting::first();

    return view("themes.{$theme}.pages.product-detail", compact('product', 'relatedProducts', 'categories', 'activeCategorySlug', 'reviews', 'averageRating', 'settings'));
})->name('products.show');

// 4. PORTFOLYO
// 4. PORTFOLYO (redirect to /projeler)
Route::get('/portfolyo', function () {
    return redirect('/projeler', 301);
});

// 5. İLETİŞİM
Route::get('/iletisim', function () {
    $theme = 'theme_1';
    
    $settings = \App\Models\Setting::first();
    $maps = collect();

    if (\Illuminate\Support\Facades\Schema::hasTable('maps')) {
        $maps = \App\Models\Map::query()
            ->where('is_active', true)
            ->where('page', 'iletisim')
            ->orderBy('id', 'asc')
            ->get();
    }
    
    return view("themes.{$theme}.pages.contact", compact('settings', 'maps'));
})->name('contact.index');

Route::post('/iletisim', function () {
    $validated = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:50',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    $validated['form_name'] = 'İletişim Formu';
    
    $contact = \App\Models\Contact::create($validated);

    $settings = \App\Models\Setting::first();

    if (
        $settings?->send_contact_notifications &&
        filled($settings->contact_notification_email)
    ) {
        try {
            \Illuminate\Support\Facades\Mail::to($settings->contact_notification_email)
                ->send(new \App\Mail\ContactFormSubmittedMail($contact, null));
        } catch (\Throwable $e) {
            report($e);
        }
    }
    
    // AJAX request'ler için "OK" cevabı, normal post'lar için redirect with session
    if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
        return response('OK');
    }
    
    return back()->with('success', 'Mesajınız başarıyla gönderildi!');
})->name('contact.store');

// 6. PROJELER
Route::get('/projeler', function () {
    $tenant = tenant();
    $theme = $tenant->theme ?? 'theme_1';
    
    $portfolios = \App\Models\Portfolio::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();
    
    $settings = \App\Models\Setting::first();
    
    return view("themes.{$theme}.pages.projeler", compact('portfolios', 'settings'));
})->name('portfolio.index');

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

// 8. ÖZEL SAYFALAR (Menüden bağlanan içerik sayfaları)
Route::get('/{slug}', function (string $slug) {
    $theme = 'theme_1';

    if (!\Illuminate\Support\Facades\Schema::hasTable('pages')) {
        abort(404);
    }

    $page = \App\Models\Page::where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    $settings = \App\Models\Setting::first();

    return view("themes.{$theme}.pages.custom-page", compact('page', 'settings'));
})->where('slug', '^(?!admin|panel|storage).*$')->name('pages.show');