<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize 
                            {--directory= : Belirli bir klasörü optimize et}
                            {--find-large : Büyük dosyaları listele}
                            {--min-size=500 : Minimum dosya boyutu (KB)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yüklenen görselleri optimize eder ve boyutlarını küçültür';

    protected ImageOptimizationService $optimizer;

    public function __construct(ImageOptimizationService $optimizer)
    {
        parent::__construct();
        $this->optimizer = $optimizer;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Büyük dosyaları listele
        if ($this->option('find-large')) {
            return $this->findLargeImages();
        }

        // Belirli bir klasörü optimize et
        if ($directory = $this->option('directory')) {
            return $this->optimizeDirectory($directory);
        }

        // Tüm klasörleri optimize et
        return $this->optimizeAllDirectories();
    }

    /**
     * Büyük görselleri listele
     */
    protected function findLargeImages(): int
    {
        $minSize = (int) $this->option('min-size');
        
        $this->info("🔍 {$minSize}KB'den büyük görseller aranıyor...");
        
        $largeFiles = $this->optimizer->findLargeImages($minSize);

        if (empty($largeFiles)) {
            $this->info("✅ {$minSize}KB'den büyük görsel bulunamadı!");
            return self::SUCCESS;
        }

        $this->newLine();
        $this->table(
            ['Dosya Yolu', 'Boyut'],
            array_map(fn($file) => [$file['path'], $file['size']], $largeFiles)
        );

        $this->newLine();
        $this->info("📊 Toplam " . count($largeFiles) . " büyük görsel bulundu.");
        $this->comment("💡 Bu görselleri optimize etmek için: php artisan images:optimize");

        return self::SUCCESS;
    }

    /**
     * Belirli bir klasörü optimize et
     */
    protected function optimizeDirectory(string $directory): int
    {
        $this->info("🚀 '{$directory}' klasörü optimize ediliyor...");
        
        $count = $this->optimizer->optimizeDirectory($directory);

        $this->newLine();
        if ($count > 0) {
            $this->info("✅ Toplam {$count} görsel optimize edildi!");
        } else {
            $this->comment("ℹ️ Optimize edilecek görsel bulunamadı.");
        }

        return self::SUCCESS;
    }

    /**
     * Tüm yaygın klasörleri optimize et
     */
    protected function optimizeAllDirectories(): int
    {
        $directories = [
            'sliders',
            'services',
            'posts',
            'pages',
            'products',
            'products/gallery',
            'portfolios',
            'portfolios/gallery',
            'galleries',
            'galleries/albums',
            'settings',
            'referrals',
            'rich-editor/services',
            'rich-editor/posts',
            'rich-editor/pages',
            'rich-editor/products',
        ];

        $this->info("🚀 Tüm görsel klasörleri optimize ediliyor...");
        $this->newLine();

        $totalOptimized = 0;
        
        $progressBar = $this->output->createProgressBar(count($directories));
        $progressBar->start();

        foreach ($directories as $directory) {
            $count = $this->optimizer->optimizeDirectory($directory);
            $totalOptimized += $count;
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->newLine(2);
        if ($totalOptimized > 0) {
            $this->info("✅ Toplam {$totalOptimized} görsel optimize edildi!");
            $this->comment("💾 Disk tasarrufu sağlandı ve site performansı artırıldı.");
        } else {
            $this->comment("ℹ️  Optimize edilecek görsel bulunamadı. Tüm görseller zaten optimize edilmiş!");
        }

        return self::SUCCESS;
    }
}
