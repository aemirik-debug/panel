<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ImageOptimizationService;

class ProductObserver
{
    protected ImageOptimizationService $imageOptimizer;

    public function __construct(ImageOptimizationService $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }

    /**
     * Product kaydedilmeden önce içerikteki görselleri optimize et
     */
    public function saving(Product $product): void
    {
        if ($product->isDirty('description') && !empty($product->description)) {
            $product->description = $this->imageOptimizer->optimizeRichEditorImages($product->description);
        }
    }
}
