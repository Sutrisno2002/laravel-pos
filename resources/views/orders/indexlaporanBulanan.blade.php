@extends('layouts.admin')

@section('title', __('Laporan Bulanan'))
@section('content-header', __('Laporan Bulanan'))
@section('content-actions')
    <a href="{{route('cart.index')}}" class="btn btn-primary">{{ __('cart.title') }}</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="cariLaporanBulanan" method="GET">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="hidden" name="inputan" value="cari">
                            <input type="date" name="start_date" class="form-control" value="{{$start_date}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{$end_date}}" />
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
                    <th>{{ __('total quantity') }}</th>
                    <th>{{ __('total revenue') }}</th>
                    <th>{{ __('order month') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $total_quantity = 0;
                    $total_revenue = 0;
                @endphp
                @if ($data != null && count($data) > 0)
                    @foreach ($data as $order)
                        @php
                            $total_quantity += $order->total_quantity;
                            $total_revenue += $order->total_revenue;
                        @endphp
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$order->product_name}}</td>
                            <td>{{$order->total_quantity}}</td>
                            <td>{{number_format($order->total_revenue)}}</td>
                            <td>{{$order->order_month}}</td>
                        </tr>
                    @endforeach
                    <!-- Total Row -->
                    <tr>
                        <th colspan="2">{{ __('Total') }}</th>
                        <th>{{$total_quantity}}</th>
                        <th>{{number_format($total_revenue)}}</th>
                        <th></th>
                    </tr>
                @else
                    <tr>
                        <td colspan="5" class="text-center">{{ __('Not Found') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <a href="{{ route('generatebulanan.pdf', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-primary">Cetak PDF</a>
    </div>
</div>
@endsection
