@extends('layouts.app')

@section('title', $category->name)

@section('content')
<style>
    /* Breadcrumb */
    nav.breadcrumb {
        font-size: 0.9rem;
        background-color: #f8f9fa;
        box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.075);
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
    }

    nav.breadcrumb .breadcrumb-item a {
        color: #0d6efd;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    nav.breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca;
        text-decoration: underline;
    }

    nav.breadcrumb .breadcrumb-item.active {
        color: #6c757d;
    }

    h2.fw-bold {
        font-size: 2rem;
        margin-bottom: 0;
    }

    span.text-muted.small {
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Horizontal scroll layout */
    .horizontal-scroll-wrapper {
        display: flex;
        overflow-x: auto;
        gap: 1rem;
        padding-bottom: 1rem;
        scroll-snap-type: x mandatory;
    }

    .product-horizontal-card {
        flex: 0 0 auto;
        width: 240px;
        scroll-snap-align: start;
    }

    .horizontal-scroll-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .horizontal-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    /* Sidebar styling remains unchanged */
    .card {
        border-radius: 1rem;
        box-shadow: 0 0.25rem 0.5rem rgb(0 0 0 / 0.1);
        border: none;
        margin-bottom: 1.5rem;
    }

    .card-header {
        font-weight: 600;
        font-size: 1.25rem;
        padding: 0.75rem 1.25rem;
        border-radius: 1rem 1rem 0 0;
        border: none;
    }

    .card-header.bg-primary {
        background-color: #0d6efd !important;
        color: #fff;
    }

    .card-header.bg-secondary {
        background-color: #6c757d !important;
        color: #fff;
    }

    ul.list-group-flush li.list-group-item {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.5rem;
        transition: background-color 0.3s ease;
        margin-bottom: 0.25rem;
    }

    ul.list-group-flush li.list-group-item:hover {
        background-color: #e9ecef;
    }

    ul.list-group-flush li.list-group-item.bg-primary {
        background-color: #0d6efd !important;
    }

    ul.list-group-flush li.list-group-item.bg-primary a {
        color: #fff !important;
        font-weight: 700;
    }

    ul.list-group-flush li.list-group-item a {
        text-decoration: none;
        display: block;
        width: 100%;
        color: #212529;
        font-weight: 500;
    }

    @media (max-width: 576px) {
        h2.fw-bold {
            font-size: 1.3rem;
        }

        .card-header {
            font-size: 1.1rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light rounded px-3 py-2 shadow-sm">
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-tags me-1"></i>{{ $category->name }}
            </li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar -->
        <div class="bg-white p-4 rounded-xl shadow mb-4">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-end gap-4 flex-wrap">
                <!-- Sắp xếp -->
                <div>
                    <label for="sort" class="block text-sm font-semibold text-gray-700 mb-1">Sắp xếp theo</label>
                    <select name="sort" id="sort" onchange="this.form.submit()"
                        class="w-48 border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
                    </select>
                </div>

                <!-- Khoảng giá -->
                <div class="flex items-center gap-2 flex-wrap">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Giá từ</label>
                        <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}"
                            class="w-24 border border-gray-300 rounded-md px-2 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Đến</label>
                        <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}"
                            class="w-24 border border-gray-300 rounded-md px-2 py-2 text-sm">
                    </div>
                </div>

                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md">
                        <i class="fas fa-filter mr-1"></i> Lọc
                    </button>
                </div>
            </form>
        </div>

        <!-- Danh sách sản phẩm ngang -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h2 class="fw-bold">{{ $category->name }}</h2>
                <span class="text-muted small">
                    Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} / {{ $products->total() ?? 0 }} sản phẩm
                </span>
            </div>

            @if($products->count())
            <div class="horizontal-scroll-wrapper">
                @foreach($products as $product)
                    <div class="product-horizontal-card">
                        @include('products.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-info">
                Không có sản phẩm nào thuộc danh mục này.
            </div>
            @endif

            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
