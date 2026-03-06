@extends('themes.theme_1.layouts.app')

@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($product->short_description ?? $product->description ?? ''), 160))
@section('body-class', 'product-details-page')

@push('styles')
<style>
  .product-details-page .category-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    padding: 16px;
    position: sticky;
    top: 90px;
  }

  .product-details-page .category-title {
    font-weight: 700;
    margin-bottom: 12px;
  }

  .product-details-page .category-tree,
  .product-details-page .category-tree ul {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .product-details-page .category-tree > li {
    margin-bottom: 6px;
  }

  .product-details-page .category-tree a {
    display: inline-block;
    color: #37373f;
    text-decoration: none;
    padding: 4px 0;
  }

  .product-details-page .category-tree a.active {
    color: #0ea5e9;
    font-weight: 700;
  }

  .product-details-page .category-tree ul {
    margin-left: 14px;
    border-left: 1px dashed #d9d9d9;
    padding-left: 10px;
  }

  .product-details-page .product-main-image {
    width: 400px;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e8e8e8;
    display: block;
    max-width: 100%;
  }

  .product-details-page .details-tabs .nav-link {
    color: #37373f;
    font-weight: 600;
  }

  .product-details-page .details-tabs .nav-link.active {
    color: #0ea5e9;
  }

  .product-details-page .rating-stars {
    color: #f59e0b;
    letter-spacing: 1px;
  }
</style>
@endpush

@section('content')

<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">{{ $product->name }}</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li><a href="{{ route('products.index') }}">Urunler</a></li>
        <li class="current">{{ $product->name }}</li>
      </ol>
    </nav>
  </div>
</div>

<section id="product-details" class="product-details section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3">
        <aside class="category-card">
          <div class="category-title">Kategoriler</div>
          <ul class="category-tree">
            <li>
              <a href="{{ route('products.index') }}" class="{{ empty($activeCategorySlug) ? 'active' : '' }}">Tumu</a>
            </li>
            @foreach($categories ?? [] as $category)
              <li>
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="{{ ($activeCategorySlug ?? null) === $category->slug ? 'active' : '' }}">{{ $category->name }}</a>
                @if($category->children->isNotEmpty())
                  <ul>
                    @foreach($category->children as $child)
                      <li>
                        <a href="{{ route('products.index', ['category' => $child->slug]) }}" class="{{ ($activeCategorySlug ?? null) === $child->slug ? 'active' : '' }}">{{ $child->name }}</a>
                      </li>
                    @endforeach
                  </ul>
                @endif
              </li>
            @endforeach
          </ul>
        </aside>
      </div>

      <div class="col-lg-9">
        <div class="row g-4">
          <div class="col-lg-6">
            @if($product->main_image)
              <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-main-image">
            @else
              <img src="{{ asset('themes/theme_1/assets/img/portfolio/portfolio-1.jpg') }}" alt="{{ $product->name }}" class="product-main-image">
            @endif
          </div>

          <div class="col-lg-6">
            <h2 class="mb-3">{{ $product->name }}</h2>

            <div class="mb-3">
              @if(!is_null($product->price))
                <span class="h4 text-primary">{{ number_format((float) $product->price, 2, ',', '.') }} TL</span>
              @endif
              @if(!is_null($product->old_price))
                <span class="text-muted text-decoration-line-through ms-2">{{ number_format((float) $product->old_price, 2, ',', '.') }} TL</span>
              @endif
            </div>

            @if($product->short_description)
              <p class="text-muted">{{ $product->short_description }}</p>
            @endif

            @if($product->categories && $product->categories->isNotEmpty())
              <div class="mb-3">
                <strong>Kategoriler:</strong>
                @foreach($product->categories as $category)
                  <span class="badge bg-light text-dark border ms-1">{{ $category->name }}</span>
                @endforeach
              </div>
            @endif

            @if($product->sku)
              <p class="mb-1"><strong>Stok Kodu:</strong> {{ $product->sku }}</p>
            @endif
            <p class="mb-0"><strong>Stok:</strong> {{ (int) $product->stock }}</p>
          </div>
        </div>

        <div class="details-tabs mt-4">
          <ul class="nav nav-tabs" id="productDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-pane" type="button" role="tab" aria-controls="description-pane" aria-selected="true">Aciklama</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab" aria-controls="reviews-pane" aria-selected="false">Urun Yorumlari</button>
            </li>
          </ul>

          <div class="tab-content border border-top-0 rounded-bottom p-3" id="productDetailTabsContent">
            <div class="tab-pane fade show active" id="description-pane" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
              @if($product->description)
                <div>{!! $product->description !!}</div>
              @else
                <p class="text-muted mb-0">Bu urun icin aciklama eklenmemis.</p>
              @endif
            </div>

            <div class="tab-pane fade" id="reviews-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
              <div class="mb-3">
                <strong>Ortalama Puan:</strong>
                <span class="rating-stars">
                  @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($averageRating ?? 0))
                      <i class="bi bi-star-fill"></i>
                    @else
                      <i class="bi bi-star"></i>
                    @endif
                  @endfor
                </span>
                <span class="ms-2">{{ number_format((float) ($averageRating ?? 0), 1, ',', '.') }} / 5</span>
              </div>

              @if(isset($reviews) && $reviews->count() > 0)
                <div class="vstack gap-3">
                  @foreach($reviews as $review)
                    <div class="border rounded p-3">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>{{ $review->name }}</strong>
                        <small class="text-muted">{{ optional($review->created_at)->format('d.m.Y') }}</small>
                      </div>
                      <div class="rating-stars mb-2">
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= (int) $review->rating)
                            <i class="bi bi-star-fill"></i>
                          @else
                            <i class="bi bi-star"></i>
                          @endif
                        @endfor
                      </div>
                      <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="text-muted mb-0">Bu urun icin henuz yorum bulunmuyor.</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
      <div class="row mt-5">
        <div class="col-12">
          <h3 class="mb-3">Diger Urunler</h3>
          <div class="row gy-4">
            @foreach($relatedProducts as $related)
              <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none">
                  <div class="border rounded p-2 h-100">
                    @if($related->main_image)
                      <img src="{{ asset('storage/' . $related->main_image) }}" alt="{{ $related->name }}" class="img-fluid rounded mb-2" style="height: 120px; width: 100%; object-fit: cover;">
                    @endif
                    <div class="small text-dark">{{ \Illuminate\Support\Str::limit($related->name, 45) }}</div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
</section>

@endsection
