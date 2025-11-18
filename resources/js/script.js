import $ from 'jquery';
import * as bootstrap from 'bootstrap';

// Khởi tạo tooltip Bootstrap 5
$(function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Setup CSRF token cho tất cả các request AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Xử lý nút tăng giảm số lượng trong giỏ hàng
    $(document).on('click', '.cart-update-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const action = $(this).data('action');

        $.ajax({
            url: '/cart/update',
            type: 'POST',
            data: {
                product_id: productId,
                action: action
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Cập nhật giỏ hàng thất bại');
                }
            },
            error: function() {
                alert('Lỗi kết nối server');
            }
        });
    });

    // Xử lý nút Thêm vào giỏ hàng
    $(document).on('click', '.add-to-cart-btn', function () {
        const productId = $(this).data('product-id');
        const button = this;

        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                if (response.success) {
                    addToCartAnimation(button);
                    $('.cart-count').text(response.cartCount);
                } else {
                    alert(response.message || 'Có lỗi xảy ra!');
                }
            },
            error: function() {
                alert('Lỗi mạng hoặc máy chủ.');
            }
        });
    });

    // Hàm animation thêm giỏ hàng
    function addToCartAnimation(button) {
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';

        setTimeout(function() {
            button.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
            setTimeout(function() {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1000);
        }, 700);
    }

    // Toggle yêu thích (favorite)
    $(document).on('click', '.toggle-favorite', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const element = this;

        $.ajax({
            url: '/favorites/toggle',
            type: 'POST',
            data: { product_id: productId },
            success: function(response) {
                if (response.success) {
                    if (response.isFavorite) {
                        element.innerHTML = '<i class="fas fa-heart text-danger"></i>';
                    } else {
                        element.innerHTML = '<i class="far fa-heart"></i>';
                    }
                }
            }
        });
    });
});