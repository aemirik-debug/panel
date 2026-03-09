<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\ImageOptimizationService;

class PostObserver
{
    protected ImageOptimizationService $imageOptimizer;

    public function __construct(ImageOptimizationService $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }

    /**
     * Post kaydedilmeden önce içerikteki görselleri optimize et
     */
    public function saving(Post $post): void
    {
        if ($post->isDirty('content') && !empty($post->content)) {
            $post->content = $this->imageOptimizer->optimizeRichEditorImages($post->content);
        }
    }
}
