@extends('layouts.app')

@section('title', 'Tìm kiếm: ' . $query)

@section('content')
<style>
    .breadcrumb {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }

    .product-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px;
        transition: box-shadow 0.3s ease;
        background-color: #fff;
        height: 100%;
    }

    .product-card:hover {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }

    .product-card h5 {
        font-size: 1rem;
        margin-top: 10px;
        color: #333;
    }

    .product-card .price {
        font-weight: bold;
        color: #d32f2f;
    }

    .search-results-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #444;
    }

    .alert-info {
        background-color: #eef5fb;
        border: 1px solid #bcdffb;
    }

    .alert-info ul {
        margin-left: 1.5rem;
    }

    .pagination {
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .product-card img {
            height: 150px;
        }
    }
</style>

<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tìm kiếm: {{ $query }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 search-results-title">Kết quả tìm kiếm cho "{{ $query }}"</h1>

            @if($products->count() > 0)
            <p>Tìm thấy {{ $products->total() }} sản phẩm</p>

            <div class="row">
                @foreach($products as $product)
                <div class="col-md-3 col-6 mb-4">
                    <div class="product-card">
                        @include('products.product-card', ['product' => $product])
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(['q' => $query])->links() }}
            </div>

            @else
            <div class="alert alert-info">
                Không tìm thấy sản phẩm nào phù hợp với từ khóa "{{ $query }}".
                <div class="mt-3">
                    <h5>Gợi ý:</h5>
                    <ul>
                        <li>Kiểm tra lại chính tả của từ khóa tìm kiếm</li>
                        <li>Sử dụng từ khóa khác</li>
                        <li>Sử dụng từ khóa ngắn hơn</li>
                        <li>Xem tất cả sản phẩm trong <a href="{{ route('products.index') }}">danh mục sản phẩm</a></li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
