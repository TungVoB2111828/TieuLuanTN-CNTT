

<nav class="col-md-3 col-lg-2 d-md-block sidebar ">
<div class="bg-light border-end" id="sidebar-wrapper" style="width: 250px;">
    <div class="sidebar-heading border-bottom bg-white text-center py-4">



                <strong>Đoàn Võ Anh Tùng</strong><br>
                <small class="text-muted">Admin</small>


    </div>

    <div class="list-group list-group-flush">
        <div class="collapse show" id="menu1">
            <a href="{{ route('admin.home.index') }}" class="list-group-item list-group-item-action">Trang chủ</a>
            <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action">Quản lý sản phẩm</a>
            <a href="{{ route('admin.category.index')}}" class="list-group-item list-group-item-action">Quản lý danh mục</a>
            <a href="{{ route('admin.invoice.index') }}" class="list-group-item list-group-item-action">Quản lý hóa đơn</a>
            <a href="{{ route('admin.staff.index') }}" class="list-group-item list-group-item-action">Quản lý nhân viên</a>
            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">Quản lý account người dùng</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="list-group-item list-group-item-action">
                        Đăng xuất
                </button>
            </form>
        </div>
    </div>
</div>
</nav>



