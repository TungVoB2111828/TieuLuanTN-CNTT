@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

<style>
    .homepage-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
    }

    .sidebar {
        flex: 1 1 220px;
        max-width: 250px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .sidebar h2 {
        font-size: 20px;
        margin-bottom: 16px;
        font-weight: bold;
        color: #333;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar li {
        margin-bottom: 12px;
    }

    .sidebar a {
        text-decoration: none;
        color: #444;
        display: flex;
        align-items: center;
        transition: color 0.2s ease;
    }

    .sidebar a:hover {
        color: #0ea5e9;
    }

    .sidebar i {
        margin-right: 8px;
        color: #0ea5e9;
    }

    .banner {
        flex: 3;
        min-width: 300px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .banner img {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
    }

    @media (max-width: 768px) {
        .homepage-container {
            flex-direction: column;
        }

        .sidebar, .banner {
            max-width: 100%;
        }
    }
</style>

<div class="homepage-container">
    <!-- Sidebar danh mục -->
    <aside class="sidebar">
        <h2>Danh mục sản phẩm</h2>
        <ul>
            @php
                $categories = [
                    ['label' => 'Manga', 'id' => 1],
                    ['label' => 'Comic', 'id' => 2],
                    ['label' => 'Anime', 'id' => 3],
                    ['label' => 'Cartoon', 'id' => 4],
                    ['label' => 'Light Novel', 'id' => 5],
                    ['label' => 'Novel', 'id' => 6],
                    ['label' => 'Artbook', 'id' => 7],
                    ['label' => 'Tutorial Book', 'id' => 8],
                    ['label' => 'Movies', 'id' => 9],
                    ['label' => 'Magazine', 'id' => 10],
                ];
            @endphp
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('products.category', $category['id']) }}">
                        <i class="fas fa-angle-right"></i> {{ $category['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>

    <!-- Ảnh quảng cáo -->
    <div class="banner">
        <img src="{{ asset('./storage/images/AoT.jpg') }}" alt="Ảnh quảng cáo">
        <img src="{{ asset('./storage/images/KM.jpg') }}" alt="Ảnh quảng cáo">
    </div>
    <div class="banner">
        <img src="{{ asset('./storage/images/Zootopia.jpg') }}" alt="Ảnh quảng cáo">
        <img src="{{ asset('./storage/images/Narnia.png') }}" alt="Ảnh quảng cáo">
    </div>
</div>

<!-- Sản phẩm dành cho bạn -->
<div class="container py-5">
    @if (!empty($recommendedProducts) && $recommendedProducts->count())
        <div class="mb-4">
            <h2 class="text-center">Sản phẩm dành cho bạn</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            @foreach ($recommendedProducts as $product)
                <div>
                    @include('products.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
