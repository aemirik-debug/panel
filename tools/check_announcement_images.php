<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tenant;
use App\Models\Announcement;

$tenant = Tenant::find('musteri2');
if (!$tenant) {
    echo "musteri2 tenant bulunamadı\n";
    exit(1);
}

tenancy()->initialize($tenant);

echo "═══════════════════════════════════════════════════════\n";
echo "DUYURU RESİMLERİNİ KONTROL EDİYORUM (musteri2)\n";
echo "═══════════════════════════════════════════════════════\n\n";

$announcements = Announcement::all();

if ($announcements->isEmpty()) {
    echo "⚠️  Hiç duyuru bulunamadı\n";
    exit(0);
}

foreach ($announcements as $announcement) {
    echo "📢 Duyuru #{$announcement->id}: {$announcement->title}\n";
    echo "   Image field: " . ($announcement->image ?? '<null>') . "\n";
    echo "   Image URL: " . ($announcement->image_url ?? '<null>') . "\n";
    
    if ($announcement->image) {
        $fullPath = storage_path('app/public/' . $announcement->image);
        $exists = file_exists($fullPath);
        echo "   Dosya yolu: {$fullPath}\n";
        echo "   Dosya mevcut: " . ($exists ? '✅ EVET' : '❌ HAYIR') . "\n";
        
        if ($exists) {
            $size = filesize($fullPath);
            echo "   Dosya boyutu: " . round($size / 1024, 2) . " KB\n";
        }
    } else {
        echo "   ℹ️  Bu duyuruda resim yok\n";
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════════════════\n";
echo "Storage symlink kontrol:\n";
echo "═══════════════════════════════════════════════════════\n";
$publicStorage = public_path('storage');
echo "public/storage yolu: {$publicStorage}\n";
echo "Var mı: " . (file_exists($publicStorage) ? '✅ EVET' : '❌ HAYIR') . "\n";
if (file_exists($publicStorage)) {
    echo "Symlink mi: " . (is_link($publicStorage) ? '✅ EVET (symlink)' : '⚠️  HAYIR (gerçek dizin)') . "\n";
}
