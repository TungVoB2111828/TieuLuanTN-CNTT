<style>
    .product-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 400px;
    }

    .product-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .product-image-container {
        position: relative;
        height: 190px;
        overflow: hidden;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-info {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-between;
    }

    .product-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
        line-height: 1.3;
        height: 2.6em; /* khoảng 2 dòng */
        overflow: hidden;
    }

    .product-name a {
        color: inherit;
        text-decoration: none;
    }

    .product-name a:hover {
        color: #0ea5e9;
    }

    .product-rating {
        color: #facc15;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .product-rating span {
        color: #777;
        font-size: 11px;
        margin-left: 6px;
    }

    .product-price {
        color: #dc2626;
        font-weight: bold;
        font-size: 15px;
        margin-bottom: 8px;
    }

    .product-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }

    .add-to-cart-btn {
        background-color: #0ea5e9;
        color: #fff;
        font-size: 12px;
        padding: 6px 10px;
        border: none;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: background-color 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background-color: #0284c7;
    }

    .favorite-icon {
        color: #666;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .favorite-icon:hover {
        color: #ef4444;
    }

    .badge-out {
        position: absolute;
        top: 8px;
        left: 8px;
        background-color: #dc2626;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .product-card {
            height: auto;
        }

        .product-image-container {
            height: 160px;
        }

        .product-price {
            font-size: 14px;
        }

        .product-name {
            font-size: 13px;
        }
    }
</style>

<div class="product-card">
    <a href="{{ route('products.show', $product->product_id) }}" class="product-image-container">
        <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('images/no-image.jpg') }}"
             alt="{{ $product->name }}"
             class="product-image">

        @if($product->stock_quantity <= 0)
            <span class="badge-out">Hết hàng</span>
        @endif
    </a>

    <div class="product-info">
        <!-- Tên sản phẩm -->
        <h5 class="product-name">
            <a href="{{ route('products.show', $product->product_id) }}">
                {{ $product->name }}
            </a>
        </h5>

        <!-- Giá -->
        <div class="product-price">
            {{ number_format($product->price, 0, ',', '.') }} <span class="text-sm">VNĐ</span>
        </div>

        <!-- Hành động -->
        <div class="product-actions">
            <button class="add-to-cart-btn"
                    data-product-id="{{ $product->product_id }}">
                <i class="fas fa-cart-plus"></i> Thêm
            </button>
        </div>
    </div>
</div>
