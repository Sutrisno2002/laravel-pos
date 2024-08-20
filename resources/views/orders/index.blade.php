@extends('layouts.admin')

@section('title', __('order.Orders_List'))
@section('content-header', __('order.Orders_List'))
@section('content-actions')
    <a href="{{route('cart.index')}}" class="btn btn-primary">{{ __('cart.title') }}</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="{{route('orders.index')}}">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" type="submit">{{ __('order.submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('no') }}</th>
                    <th>{{ __('product name') }}</th>
                    <th>{{ __('quantity') }}</th>
                    <th>{{ __('price') }}</th>
                    <th>{{ __('total') }}</th>

                    <th>{{ __('order.Created_At') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                @endphp
                @foreach ($data as $order)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$order->name}}</td>
                    <td>{{$order->quantity}}</td>
                    <td>{{$order->price}}</td>
                    <td>{{$order->price * $order->quantity}}</td>

                    <td>{{$order->created_at}}</td>




                </tr>
                @endforeach
            </tbody>
            
        </table>
        {{ $orders->render() }}
    </div>
</div>
@endsection

