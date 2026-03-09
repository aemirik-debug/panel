<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizationService
{
    protected ?ImageManager $manager = null;
    
    protected function getManager(): ImageManager
    {
        if ($this->manager === null) {
            $this->manager = new ImageManager(new Driver());
        }
        return $this->manager;
    }

    /**
     * RichEditor içindeki tüm görselleri optimize et
     * 
     * @param string|null $content HTML içerik
     * @return string Optimize edilmiş içerik
     */
    public function optimizeRichEditorImages(?string $content): ?string
    {
        if (empty($content)) {
            return $content;
        }

        // İçerikteki tüm img etiketlerini bul
        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
        
        if (empty($matches[1])) {
            return $content;
        }

        foreach ($matches[1] as $imageUrl) {
            // Base64 görselleri atla
            if (strpos($imageUrl, 'data:image') === 0) {
                continue;
            }

            // External URL'leri atla
            if (strpos($imageUrl, 'http') === 0 && strpos($imageUrl, config('app.url')) === false) {
                continue;
            }

            // Storage path'ini çıkar
            $path = $this->extractStoragePath($imageUrl);
            
            if ($path && Storage::disk('public')->exists($path)) {
                // Görseli optimize et
                $this->optimizeImage($path);
            }
        }

        return $content;
    }

    /**
     * Tek bir görseli optimize et
     * 
     * @param string $path Storage path
     * @param int $maxWidth Maksimum genişlik
     * @param int|null $maxHeight Maksimum yükseklik (null ise orantılı)
     * @return bool
     */
    public function optimizeImage(string $path, int $maxWidth = 1200, ?int $maxHeight = null): bool
    {
        try {
            $disk = Storage::disk('public');
            
            if (!$disk->exists($path)) {
                return false;
            }

            $fullPath = $disk->path($path);
            $image = $this->getManager()->read($fullPath);
            
            // Eğer görsel zaten küçükse ve dosya boyutu 500KB'den küçükse optimize etme
            if ($image->width() <= $maxWidth && filesize($fullPath) < 500000) {
                return true;
            }

            // Görseli resize et (aspect ratio koruyarak)
            if ($image->width() > $maxWidth || ($maxHeight && $image->height() > $maxHeight)) {
                $image->scale(width: $maxWidth, height: $maxHeight);
            }

            // Optimize ederek kaydet (quality: 85)
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['jpg', 'jpeg'])) {
                $image->toJpeg(85)->save($fullPath);
            } elseif ($extension === 'png') {
                // PNG için web optimize et
                $image->toPng()->save($fullPath);
            } elseif ($extension === 'webp') {
                $image->toWebp(85)->save($fullPath);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Görsel optimizasyon hatası: ' . $e->getMessage(), [
                'path' => $path
            ]);
            return false;
        }
    }

    /**
     * URL'den storage path'ini çıkar
     * 
     * @param string $url
     * @return string|null
     */
    protected function extractStoragePath(string $url): ?string
    {
        // /storage/ ile başlayan path'i bul
        if (preg_match('#/storage/(.+)$#', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Bir klasördeki tüm görselleri optimize et
     * 
     * @param string $directory
     * @param int $maxWidth
     * @return int Optimize edilen görsel sayısı
     */
    public function optimizeDirectory(string $directory, int $maxWidth = 1200): int
    {
        $disk = Storage::disk('public');
        $files = $disk->allFiles($directory);
        $count = 0;

        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                if ($this->optimizeImage($file, $maxWidth)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Büyük dosyaları tespit et
     * 
     * @param int $minSizeKb Minimum dosya boyutu (KB)
     * @return array
     */
    public function findLargeImages(int $minSizeKb = 500): array
    {
        $disk = Storage::disk('public');
        $allFiles = $disk->allFiles();
        $largeFiles = [];

        foreach ($allFiles as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $size = $disk->size($file);
                
                if ($size > ($minSizeKb * 1024)) {
                    $largeFiles[] = [
                        'path' => $file,
                        'size' => round($size / 1024, 2) . ' KB',
                        'size_bytes' => $size,
                    ];
                }
            }
        }

        return $largeFiles;
    }
}
