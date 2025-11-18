<header class="bg-white shadow-md">
    <nav class="container mx-auto px-4 py-3 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 hover:text-blue-800 transition">
            Entertainment Shop
        </a>

        {{-- Mobile Toggle --}}
        <button class="lg:hidden text-gray-700 focus:outline-none" id="navbar-toggle">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Main Menu --}}
        <div class="hidden lg:flex items-center space-x-6" id="navbar-menu">
            {{-- Menu Links --}}
            <ul class="flex items-center space-x-4 text-gray-700 font-medium text-sm">
                <li>
                    <a href="{{ route('home') }}"
                       class="hover:text-blue-600 {{ Route::currentRouteName() == 'home' ? 'text-blue-600 font-semibold' : '' }}">
                        Trang chủ
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}"
                       class="hover:text-blue-600 {{ Route::currentRouteName() == 'products.index' ? 'text-blue-600 font-semibold' : '' }}">
                        Sản phẩm
                    </a>
                </li>

                {{-- Dropdown Danh mục --}}
                <li class="relative group">
                    <a href="#" class="hover:text-blue-600 flex items-center">
                        Danh mục
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                    <ul class="absolute left-0 hidden group-hover:block bg-white shadow-md rounded mt-2 z-50 min-w-[150px] text-gray-700">
                        @foreach(App\Models\Category::all() as $category)
                            <li>
                                <a href="{{ route('products.category', $category->category_id) }}"
                                   class="block px-4 py-2 hover:bg-gray-100">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            {{-- Search --}}
            <form action="{{ route('products.search') }}" method="GET" class="ml-6 flex">
                <input type="search" name="q"
                       class="px-3 py-1 rounded-l border border-gray-300 focus:ring-2 focus:ring-blue-300 text-sm"
                       placeholder="Tìm kiếm...">
                <button type="submit"
                        class="bg-blue-500 text-white px-3 rounded-r hover:bg-blue-600 text-sm">
                    Tìm
                </button>
            </form>

            {{-- Right Menu --}}
            <ul class="flex items-center space-x-4 ml-6 text-sm">
                {{-- Cart --}}
                <li class="relative">
                    <a href="{{ route('cart.index') }}"
                       class="flex items-center text-gray-700 hover:text-blue-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.4 5M17 13l1.4 5M6 18h12a2 2 0 11-2 2H8a2 2 0 11-2-2z"/>
                        </svg>
                        <span>Giỏ hàng</span>
                        <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs rounded-full px-1">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </a>
                </li>

                {{-- User Auth --}}
                @guest
                    <li><a href="{{ route('login.user') }}" class="hover:text-blue-600">Đăng nhập</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-blue-600">Đăng ký</a></li>
                @else
                    <li class="relative group">
                        <a href="#" class="flex items-center hover:text-blue-600">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                        <ul class="absolute right-0 hidden group-hover:block bg-white shadow-md mt-2 rounded min-w-[150px] text-gray-700 z-50">
                            <li><a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Tài khoản</a></li>
                            <li><a href="{{ route('orders') }}" class="block px-4 py-2 hover:bg-gray-100">Đơn hàng</a></li>
                            <li><hr class="my-1 border-gray-200"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</header>

{{-- Script toggle menu --}}
<script>
    document.getElementById('navbar-toggle').addEventListener('click', function () {
        document.getElementById('navbar-menu').classList.toggle('hidden');
    });
</script>
