@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích')

@section('header')
    <h1 class="mb-4">Danh sách sản phẩm yêu thích</h1>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($favorites->isEmpty())
        <div class="alert alert-info">Bạn chưa có sản phẩm yêu thích nào.</div>
    @else
        <div class="row">
            @foreach($favorites as $favorite)
                @php $product = $favorite->product; @endphp
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset($product->image_url ?? 'images/default.jpg') }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-primary">Xem chi tiết</a>

                            {{-- Nút xoá khỏi yêu thích --}}
                            <form action="{{ route('favorites.remove', $product->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Bỏ yêu thích</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
