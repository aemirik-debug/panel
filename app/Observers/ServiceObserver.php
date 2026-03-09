<?php

namespace App\Observers;

use App\Models\Service;
use App\Services\ImageOptimizationService;

class ServiceObserver
{
    protected ImageOptimizationService $imageOptimizer;

    public function __construct(ImageOptimizationService $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }

    /**
     * Service kaydedilmeden önce içerikteki görselleri optimize et
     */
    public function saving(Service $service): void
    {
        if ($service->isDirty('description') && !empty($service->description)) {
            $service->description = $this->imageOptimizer->optimizeRichEditorImages($service->description);
        }
    }
}
