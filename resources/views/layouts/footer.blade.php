<footer class="bg-gray-900 text-white py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Cột 1 -->
        <div>
            <h5 class="text-xl font-semibold mb-2">Entertainment Shop</h5>
            <p class="mb-4">Cửa hàng trực tuyến với các sản phẩm chất lượng cao.</p>
            <ul class="space-y-2 text-sm">
                <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Đường ABC, Quận XYZ, TP. Tokyo</li>
                <li><i class="fas fa-phone mr-2"></i> (123) 456-7890</li>
                <li><i class="fas fa-envelope mr-2"></i> info@shoponline.com</li>
            </ul>
        </div>

        <!-- Cột 2 -->
        <div>
            <h5 class="text-xl font-semibold mb-2">Liên kết & Newsletter</h5>
            <ul class="space-y-2 text-sm mb-4">
                <li><a href="{{ route('home') }}" class="hover:text-sky-400">Trang chủ</a></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-sky-400">Sản phẩm</a></li>
                <li><a href="#" class="hover:text-sky-400">Giới thiệu</a></li>
                <li><a href="#" class="hover:text-sky-400">Liên hệ</a></li>
                <li><a href="#" class="hover:text-sky-400">Điều khoản</a></li>
            </ul>
            <form class="flex flex-col sm:flex-row gap-2">
                <input type="email" placeholder="Email của bạn"
                    class="w-full px-3 py-2 rounded text-gray-900" />
                <button type="submit"
                    class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded">
                    Đăng ký
                </button>
            </form>
            <div class="flex items-center gap-4 mt-4">
                <a href="#" class="hover:text-sky-400"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-sky-400"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-sky-400"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-sky-400"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Dòng cuối -->
    <div class="mt-8 text-center text-sm border-t border-gray-700 pt-4">
        <p>&copy; {{ date('Y') }} Shop Laravel. All rights reserved.</p>
    </div>
</footer>
