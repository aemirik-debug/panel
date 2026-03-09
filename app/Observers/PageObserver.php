<?php

namespace App\Observers;

use App\Models\Page;
use App\Services\ImageOptimizationService;

class PageObserver
{
    protected ImageOptimizationService $imageOptimizer;

    public function __construct(ImageOptimizationService $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }

    /**
     * Page kaydedilmeden önce içerikteki görselleri optimize et
     */
    public function saving(Page $page): void
    {
        if ($page->isDirty('content') && !empty($page->content)) {
            $page->content = $this->imageOptimizer->optimizeRichEditorImages($page->content);
        }
    }
}
