@extends('admin.layouts.index')

@section('title')
    {{ $product->name }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Product Header -->
            <div class="mb-4">
                <div class="text-muted">Status: 
                    @if ($product->status)
                        <span class="text-success">This product Showing</span>
                    @else
                        <span class="text-danger">This product Hidden</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-start mt-2">
                    <div>
                        <h1 class="h3 mb-2">{{ $product->name }}</h1>
                        <div class="text-muted">SKU: {{ $product->sku ?? '' }}</div>
                    </div>
                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-success">Edit Product</a>
                </div>
            </div>

            <!-- Product Info -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-image mb-4">
                        <img src="{{ Storage::url($product->image) }}" class="img-fluid rounded border" alt="{{ $product->name }}" style="max-width: 100%; height: auto;">
                    </div>
                    @if ($productImages && count($productImages) > 0)
                        <div class="product-gallery d-flex gap-2 mb-4">
                            @foreach ($productImages as $productImage)
                                <div class="gallery-item">
                                    <img src="{{ Storage::url($productImage->image_url) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="product-info">
                        {{-- <div class="mb-4">
                            <div class="h2 mb-2">${{ number_format($product->price ?? 0, 2) }}</div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success-light me-2">In Stock</span>
                                <span class="text-muted">QUANTITY: {{ $product->quantity ?? 0 }}</span>
                            </div>
                        </div> --}}

                        <div class="mb-4">
                            <h5 class="mb-2">Category: <a href="#" class="text-decoration-none">{{ $category->name }}</a></h5>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark">{{ $product->tag ?? 'abc' }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-2">Description</h5>
                            <div class="text-muted">
                                {!! $product->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Variants -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Product Variant List</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>SR</th>
                                <th>IMAGE</th>
                                <th>COMBINATION</th>
                                <th>SKU</th>
                                <th>BARCODE</th>
                                <th>ORIGINAL PRICE</th>
                                <th>SALE PRICE</th>
                                <th>QUANTITY</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skuses as $index => $skus)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ Storage::url($skus->image) }}" width="40" height="40" class="rounded" alt="">
                                    </td>
                                    <td>
                                        <div>{{ $skus->name }}</div>
                                        <div class="text-muted small">{{ $skus->sku }}</div>
                                    </td>
                                    <td>{{ $skus->sku }}</td>
                                    <td>{{ $skus->barcode }}</td>
                                    <td>${{ number_format($skus->original_price ?? 0, 2) }}</td>
                                    <td>${{ number_format($skus->sale_price ?? 0, 2) }}</td>
                                    <td>{{ $skus->quantity }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.product.skus.edit', ['product' => $product->id, 'sku' => $skus->id]) }}" 
                                               class="btn btn-sm btn-light">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.product.skus.show', ['product' => $product->id, 'sku' => $skus->id]) }}" 
                                               class="btn btn-sm btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.skus.change_status', ['product' => $product->id, 'sku' => $skus->id]) }}"
                                                  method="post"
                                                  class="d-inline-block"
                                                  onsubmit="return confirm('Are you sure you want to change the status of this variant?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-light">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($skuses->hasPages())
                    <div class="card-footer border-top bg-white">
                        {{ $skuses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }
    .table > :not(caption) > * > * {
        padding: 1rem;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dde0e3;
    }
    .gallery-item {
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    .product-image img {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
    }
    .content-wrapper {
        background-color: #f8fafc;
    }
</style>
@endpush
