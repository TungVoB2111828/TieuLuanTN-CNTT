@extends('layouts.app')

@section('title', 'Tất cả sản phẩm')

@section('content')
<style>
    /* Breadcrumb */
    nav.breadcrumb {
        font-size: 0.9rem;
        background-color: #f8f9fa;
        box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.075);
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        margin-bottom: 1.5rem;
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

    label.form-label {
        font-weight: 600;
    }

    .input-group input.form-control {
        border-radius: 0.375rem;
    }

    button.btn-primary {
        font-weight: 600;
        border-radius: 0.5rem;
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
    ul.list-group-flush li.list-group-item a {
        text-decoration: none;
        display: block;
        width: 100%;
        color: #212529;
        font-weight: 500;
    }

    h2.fw-bold {
        font-size: 2rem;
        margin-bottom: 0;
    }

    span.text-muted.small {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .col-md-4.col-6.mb-4 {
        display: flex;
        justify-content: center;
    }

    @media (max-width: 992px) {
        .col-md-3 {
            margin-bottom: 1.5rem;
        }
        h2.fw-bold {
            font-size: 1.5rem;
        }
    }
    @media (max-width: 576px) {
        h2.fw-bold {
            font-size: 1.3rem;
        }
        .card-header {
            font-size: 1.1rem;
        }
    }

    /* Grid hiển thị sản phẩm */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr); /* 5 cột đều nhau */
        gap: 1.5rem;
    }
    .product-horizontal-card {
        width: 100%; /* mỗi ô chiếm full trong cột */
    }
    @media (max-width: 1200px) {
        .product-grid {
            grid-template-columns: repeat(4, 1fr); /* 4 cột */
        }
    }
    @media (max-width: 992px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr); /* 3 cột */
        }
    }
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr); /* 2 cột */
        }
    }
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr; /* 1 cột */
        }
    }
</style>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-store me-1"></i> Tất cả sản phẩm
            </li>
        </ol>
    </nav>

    <div class="row mt-4">
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

        <!-- Danh sách sản phẩm -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h2 class="fw-bold">Tất cả sản phẩm</h2>
                <span class="text-muted small">
                    Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} / {{ $products->total() ?? 0 }} sản phẩm
                </span>
            </div>

            <div class="product-grid mb-4">
                @forelse($products as $product)
                <div class="product-horizontal-card">
                    @include('products.product-card', ['product' => $product])
                </div>
                @empty
                <div class="w-100 text-center">
                    <div class="alert alert-info text-center py-4">
                        Không có sản phẩm nào phù hợp với tiêu chí lọc.
                    </div>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
