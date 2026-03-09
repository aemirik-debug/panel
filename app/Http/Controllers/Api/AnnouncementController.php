<?php

namespace App\Http\Controllers\Api;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;

class AnnouncementController
{
    /**
     * Paket türüne göre izin verilen duyuru sayısını döndür
     */
    private function getPackageDuyuruLimit(): int
    {
        $tenant = tenant();
        $limits = [
            'baslangic' => 1,
            'kurumsal' => 5,
            'pro' => 999999, // Unlimited
            'profesyonel' => 999999, // Backward compatibility
        ];
        return $limits[$tenant->package] ?? 1;
    }

    /**
     * Aktif duyuruları getir (paket limitine saygılı)
     */
    public function index(): JsonResponse
    {
        $limit = $this->getPackageDuyuruLimit();
        
        $announcements = Announcement::active()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'image' => $announcement->image_url,
                    'button_text' => $announcement->button_text,
                    'button_url' => $announcement->button_url,
                    'type' => $announcement->type,
                    'color_scheme' => $announcement->color_scheme,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $announcements,
        ]);
    }

    /**
     * Belirli bir duyuruyu getir
     */
    public function show(Announcement $announcement): JsonResponse
    {
        if (!$announcement->is_active) {
            return response()->json(['success' => false, 'message' => 'Duyuru bulunamadı'], 404);
        }

        // Görüntüleme sayısını arttır
        $announcement->incrementViewCount();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'content' => $announcement->content,
                'image' => $announcement->image_url,
                'button_text' => $announcement->button_text,
                'button_url' => $announcement->button_url,
                'type' => $announcement->type,
                'color_scheme' => $announcement->color_scheme,
            ],
        ]);
    }

    /**
     * Duyurunun görüntülenmesini kaydet
     */
    public function view(Announcement $announcement): JsonResponse
    {
        if ($announcement->is_active) {
            $announcement->incrementViewCount();
        }

        return response()->json(['success' => true]);
    }
}
