@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-4">
            <div class="card">
                <img class="card-img-top" src="{{ Storage::url($product->image) }}" alt="Product Image" style="width: 100%; height: 150px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ number_format($product->price, 2) }}</p>
                    {{-- <a href="{{ route('products.show', $product) }}" class="btn btn-primary">View Details</a> --}}
                    <form action="{{ route('cart.store') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" min="1" value="1" class="form-control">
                        <button type="submit" class="btn btn-success mt-2">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
