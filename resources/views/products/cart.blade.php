@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cart</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->product->price, 2) }}</td>
                <td>{{ number_format($item->quantity * $item->product->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Checkout</button>
    </form>
</div>
@endsection
