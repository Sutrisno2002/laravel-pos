@extends('layouts.admin')

@section('title', __('Laporan Return'))
@section('content-header', __('Laporan Return'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="/admin/return-masuk">
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
        <br>
        <div class="container">
            <br>
        
            @if ($StokReturn->isEmpty())
                <p>Tidak ada laporan stok masuk.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Return</th>
                            <th>Nama User</th>
                            <th>Tanggal Return</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($StokReturn as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->user->getFullname()}}</td>
                                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        {{ $StokReturn->render() }}
        <a href="{{ route('generatereturn.pdf', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-primary">Cetak PDF</a>

    </div>
</div>
@endsection

