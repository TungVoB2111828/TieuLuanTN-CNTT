@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

<style>
    body {
        background: #f3f4f6;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .magazine-container {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 20px;
        padding: 20px;
    }

    /* SIDEBAR */
    .sidebar {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .sidebar h2 {
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: bold;
        color: #333;
        border-bottom: 2px solid #0ea5e9;
        padding-bottom: 8px;
    }

    .sidebar ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar li {
        margin-bottom: 12px;
    }

    .sidebar a {
        text-decoration: none;
        color: #444;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .sidebar a:hover {
        color: #0ea5e9;
        transform: translateX(4px);
    }

    .sidebar i {
        margin-right: 8px;
        color: #0ea5e9;
    }

    /* MAIN CONTENT */
    .magazine-main {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* FEATURED SECTION */
    .featured-articles {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .featured-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }

    .featured-card:hover {
        transform: scale(1.02);
    }

    .featured-card img {
        width: 100%;
        height: auto;
        object-fit: contain; /* Giữ ảnh không bị cắt hoặc ép méo */
        display: block;
    }

    .featured-content {
        padding: 15px;
    }

    .featured-content h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #222;
    }

    .featured-content p {
        color: #666;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .magazine-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="magazine-container">
    <!-- Sidebar danh mục -->
    <aside class="sidebar">
        <h2>Danh mục</h2>
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

    <!-- Nội dung chính -->
    <section class="magazine-main">
        <div class="featured-articles">
            <div class="featured-card">
                <img src="{{ asset('./storage/images/AoT.jpg') }}" alt="Attack on Titan">
                <div class="featured-content">
                    <h3>Attack on Titan: Cuộc chiến cuối cùng</h3>
                    <p>Bài viết nổi bật trong tuần — phân tích chiều sâu tâm lý nhân vật Eren và tự do trong tuyệt vọng...</p>
                </div>
            </div>
            <div class="featured-card">
                <img src="{{ asset('./storage/images/KM.jpg') }}" alt="Kingsman">
                <div class="featured-content">
                    <h3>Kingsman</h3>
                    <p>Khám phá sự lịch lãm và tuyệt vời của các quý ông trong tổ chức mật vụ Kingsman...</p>
                </div>
            </div>
            <div class="featured-card">
                <img src="{{ asset('./storage/images/Zootopia.jpg') }}" alt="Zootopia">
                <div class="featured-content">
                    <h3>Zootopia: Xã hội loài vật và thông điệp về định kiến</h3>
                    <p>Một bộ phim vừa vui nhộn vừa sâu sắc về sự khác biệt và công bằng...</p>
                </div>
            </div>
            <div class="featured-card">
                <img src="{{ asset('./storage/images/Narnia.png') }}" alt="Narnia">
                <div class="featured-content">
                    <h3>Narnia: Huyền thoại về 1 thế giới fantasy tuổi thơ của không biết bao nhiêu người trên toàn thế giới</h3>
                    <p>Cánh cửa dẫn đến thế giới diệu kỳ, nơi lòng dũng cảm và niềm tin được thử thách...</p>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
