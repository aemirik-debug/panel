<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service; 
use App\Http\Controllers\BlogController;

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');


// 1. ANA SAYFA
Route::get('/', function () {
   return;
    
});

// 2. HİZMET DETAY (Sıralama önemli, detay rotası altta kalsın)
Route::get('/servis/{slug}', function ($slug) {
    // Modelin tam yolunu kullanarak çağırmak her zaman daha garantidir
    $service = \App\Models\Service::where('slug', $slug)
        ->where('is_active', true) // Pasif hizmetlere girişi engelleriz
        ->firstOrFail();

    return view('service-detail', compact('service'));
})->name('service.detail');