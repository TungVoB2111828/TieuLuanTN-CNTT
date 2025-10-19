@extends('admin.component.layout')

@section('title', 'Trang chủ')
@section('content')

<style>
    .card-custom {
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    .card-custom:hover {
        transform: translateY(-5px);
    }
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
    }
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 30px;
    }
    .filter-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .export-btn {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }
    .loading {
        text-align: center;
        padding: 20px;
    }
</style>

<div class="container mt-1">
    <div class="row g-3">
        <!-- Card 1 -->
        <div class="col-md-4">
            <div class="card-custom bg-warning shadow p-3">
                <i class="fas fa-th-list fa-2x"></i>
                <h3 class="mb-1">Tổng số hóa đơn: {{$invoiceCount}}</h3>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card-custom bg-success shadow p-3">
                <i class="fas fa-coffee fa-2x"></i>
                <h2 class="mb-1">Tổng số đã xử lý: {{$invoiceCompletedCount}}</h2>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4">
            <div class="card-custom bg-danger shadow p-3">
                <i class="fas fa-calendar-day fa-2x"></i>
                <h3 class="mb-1">Tổng số sản phẩm: {{$productCount}}</h3>
            </div>
        </div>
    </div>
</div>

@include('admin.component.script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('admin.home.chart')

@endsection
