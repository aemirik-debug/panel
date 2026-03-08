@extends('themes.theme_1.layouts.app')

@section('title', 'Urunler - ' . ($settings->site_name ?? 'Urunler'))
@section('body-class', 'products-page')

@push('styles')
<style>
  .products-page .category-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    padding: 16px;
    position: sticky;
    top: 90px;
  }

  .products-page .category-title {
    font-weight: 700;
    margin-bottom: 12px;
  }

  .products-page .category-tree,
  .products-page .category-tree ul {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .products-page .category-tree > li {
    margin-bottom: 6px;
  }

  .products-page .category-tree a {
    display: inline-block;
    color: #37373f;
    text-decoration: none;
    padding: 4px 0;
  }

  .products-page .category-tree a.active {
    color: #0ea5e9;
    font-weight: 700;
  }

  .products-page .category-tree ul {
    margin-left: 14px;
    border-left: 1px dashed #d9d9d9;
    padding-left: 10px;
  }

  .products-page .product-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    overflow: hidden;
    height: 100%;
    transition: all 0.3s ease;
  }

  .products-page .product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    border-color: #0ea5e9;
  }

  .products-page .product-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
  }

  .products-page .product-content {
    padding: 16px;
  }

  .products-page .product-title {
    font-size: 18px;
    margin-bottom: 8px;
    color: #37373f;
  }

  .products-page .product-price {
    font-weight: 700;
    color: #0ea5e9;
  }

  .products-page .product-old-price {
    text-decoration: line-through;
    color: #999;
    margin-left: 8px;
    font-size: 14px;
  }

  .products-page .product-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }
</style>
@endpush

@section('content')

<div class="page-title dark-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Urunler</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
        <li class="current">Urunler</li>
      </ol>
    </nav>
  </div>
</div>

<section id="products" class="products section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3">
        <aside class="category-card">
          <div class="category-title">Kategoriler</div>
          <ul class="category-tree">
            <li>
              <a href="{{ route('products.index') }}" class="{{ empty($activeCategorySlug) ? 'active' : '' }}">Tümü</a>
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
        <div class="row gy-4">
          @forelse($products as $product)
            <div class="col-xl-4 col-md-6" data-aos="fade-up">
              <a href="{{ route('products.show', $product->slug) }}" class="product-card-link">
                <article class="product-card">
                  @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-image">
                  @else
                    <img src="{{ asset('themes/theme_1/assets/img/portfolio/portfolio-1.jpg') }}" alt="{{ $product->name }}" class="product-image">
                  @endif

                  <div class="product-content">
                    <h3 class="product-title">{{ $product->name }}</h3>

                    @if($product->short_description)
                      <p class="mb-3 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 100) }}</p>
                    @endif

                    <div class="d-flex align-items-center mb-2">
                      @if(!is_null($product->price))
                        <span class="product-price">{{ number_format((float) $product->price, 2, ',', '.') }} TL</span>
                      @endif
                      @if(!is_null($product->old_price))
                        <span class="product-old-price">{{ number_format((float) $product->old_price, 2, ',', '.') }} TL</span>
                      @endif
                    </div>
                  </div>
                </article>
              </a>
            </div>
          @empty
            <div class="col-12 text-center">
              <p class="text-muted mb-0">Henuz aktif urun eklenmedi.</p>
            </div>
          @endforelse
        </div>

        @if(method_exists($products, 'hasPages') && $products->hasPages())
          <div class="pagination d-flex justify-content-center mt-5">
            {{ $products->appends(request()->query())->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

@endsection
