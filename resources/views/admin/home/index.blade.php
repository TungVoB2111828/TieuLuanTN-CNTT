@extends('admin.component.layout')

@section('title', 'Trang chủ')

@section('content')

<div class="container mt-3">

    <h2 class="mb-4">Trang chủ quản trị</h2>

    <div class="row g-3">

        <!-- Tổng số hóa đơn -->
        <div class="col-12">
            <div class="p-3 bg-light border rounded shadow-sm">
                <h5 class="mb-1">Tổng số hóa đơn</h5>
                <p class="fs-4 fw-bold text-white">{{ $invoiceCount }}</p>
            </div>
        </div>

        <!-- Tổng số hóa đơn đã xử lý -->
        <div class="col-12">
            <div class="p-3 bg-light border rounded shadow-sm">
                <h5 class="mb-1">Hóa đơn đã xử lý</h5>
                <p class="fs-4 fw-bold text-white">{{ $invoiceCompletedCount }}</p>
            </div>
        </div>

        <!-- Tổng số sản phẩm -->
        <div class="col-12">
            <div class="p-3 bg-light border rounded shadow-sm">
                <h5 class="mb-1">Tổng số sản phẩm</h5>
                <p class="fs-4 fw-bold text-white">{{ $productCount }}</p>
            </div>
        </div>

        <!-- Tổng số danh mục -->
        <div class="col-12">
            <div class="p-3 bg-light border rounded shadow-sm">
                <h5 class="mb-1">Tổng số danh mục</h5>
                <p class="fs-4 fw-bold text-white">{{ $categoryCount }}</p>
            </div>
        </div>

        <!-- Tổng số nhân viên -->
        <div class="col-12">
            <div class="p-3 bg-light border rounded shadow-sm">
                <h5 class="mb-1">Tổng số nhân viên</h5>
                <p class="fs-4 fw-bold text-white">{{ $staffCount }}</p>
            </div>
        </div>

    </div>

</div>

@endsection
