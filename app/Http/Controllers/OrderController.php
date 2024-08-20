<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Models\v_order;
use App\Models\rekap_laporan;
use PDF;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StokMasuk;
use App\Models\StokReturn;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = new Order();
        if($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $orders = $orders->with(['items', 'payments', 'customer'])->latest()->paginate(10);

        $total = $orders->map(function($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $orders->map(function($i) {
            return $i->receivedAmount();
        })->sum();

        $data = v_order::latest()->get();

        return view('orders.index', compact('data' ,'orders', 'total', 'receivedAmount'));
    }


    function showStokMasuk(Request $request)  {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if($start_date == null || $end_date == null ){
            $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
            $end_date = $request->input('end_date', now()->toDateString());
        }else{
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
        }
        $stokMasuk = new StokMasuk();
        if($request->start_date) {
            $stokMasuk = $stokMasuk->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $stokMasuk = $stokMasuk->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $stokMasuk = $stokMasuk->with('product', 'user')->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.indexStokLaporan', compact('stokMasuk','start_date', 'end_date'));
    }

    function showReturnMasuk(Request $request)  {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if($start_date == null || $end_date == null ){
            $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
            $end_date = $request->input('end_date', now()->toDateString());
        }else{
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
        }
        $StokReturn = new StokReturn();
        if($request->start_date) {
            $StokReturn = $StokReturn->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $StokReturn = $StokReturn->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $StokReturn = $StokReturn->with('product', 'user')->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.indexReturnLaporan', compact('StokReturn','start_date', 'end_date'));
    }

    

    function indexLaporan(Request $request) {

        $data =rekap_laporan::orderBy('order_date')->paginate(15);        
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if($start_date == null || $end_date == null ){
            $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
            $end_date = $request->input('end_date', now()->toDateString());
        }else{
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
        }
        return view('orders.indexlaporan', compact('data', 'start_date', 'end_date'));
    }

    function indexLaporanBulanan(Request $request) {

        $data = $results = DB::select("
        SELECT 
            DATE_FORMAT(`o`.`created_at`, '%Y-%m') AS `order_month`,
            `p`.`name` AS `product_name`,
            SUM(`p`.`price` * `o`.`quantity`) AS `total_revenue`,
            SUM(`o`.`quantity`) AS `total_quantity`
        FROM 
            `order_items` `o`
        JOIN 
            `products` `p` 
        ON 
            `o`.`product_id` = `p`.`id`
        GROUP BY 
            `order_month`,
            `product_name`
        ORDER BY 
            `order_month`
    ");        
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if($start_date == null || $end_date == null ){
            $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
            $end_date = $request->input('end_date', now()->toDateString());
        }else{
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
        }
        return view('orders.indexlaporanBulanan', compact('data', 'start_date', 'end_date'));
    }

    function indexPrintStok(Request $request) {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $data = StokMasuk::whereBetween('created_at', [$start_date, $end_date])->get();
        $pdf = PDF::loadView('orders.indexprintStok', compact('data'));
        return $pdf->download('recapsstok.pdf');
    }

    function indexPrintReturn(Request $request) {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $data = StokReturn::whereBetween('created_at', [$start_date, $end_date])->get();
        $pdf = PDF::loadView('orders.indexprintReturn', compact('data'));
        return $pdf->download('recapsReturn.pdf');
    }


    function indexPrint(Request $request) {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $data = rekap_laporan::whereBetween('order_date', [$start_date, $end_date])->get();
        $pdf = PDF::loadView('orders.indexprint', compact('data'));
        return $pdf->download('recaps.pdf');
    }

    function indexPrintBulanan(Request $request) {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
    
        // Ensure dates are provided, otherwise default to a wide range or handle as needed
        $start_date = $start_date ?: '1900-01-01';  // Default to a very early date if not provided
        $end_date = $end_date ?: now()->format('Y-m-d');  // Default to today's date if not provided
    
        // Execute the query with date filtering
        $data = DB::select("
            SELECT 
                DATE_FORMAT(`o`.`created_at`, '%Y-%m') AS `order_month`,
                `p`.`name` AS `product_name`,
                SUM(`p`.`price` * `o`.`quantity`) AS `total_revenue`,
                SUM(`o`.`quantity`) AS `total_quantity`
            FROM 
                `order_items` `o`
            JOIN 
                `products` `p` 
            ON 
                `o`.`product_id` = `p`.`id`
            WHERE 
                `o`.`created_at` BETWEEN :start_date AND :end_date
            GROUP BY 
                `order_month`,
                `product_name`
            ORDER BY 
                `order_month`
        ", ['start_date' => $start_date, 'end_date' => $end_date]);  // Bind parameters to the query
    
        // Generate PDF with the filtered data
        $pdf = PDF::loadView('orders.indexprintBulanan', compact('data'));
    
        // Return the generated PDF for download
        return $pdf->download('recap-bulanan.pdf');
    }



    function cariLaporan(Request $request) {

        $inputan = $request->input('inputan');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if($start_date == null || $end_date == null ){
            $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
            $end_date = $request->input('end_date', now()->toDateString());
        }else{
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
        }
        $data = rekap_laporan::whereBetween('order_date', [$start_date, $end_date])->paginate(15);
        return view('orders.indexlaporan', compact('data', 'start_date', 'end_date'));
        

    }

    function cariLaporanBulanan(Request $request) {

        // Retrieve input values from the request
    $inputan = $request->input('inputan');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    // Check if start_date and end_date are null
    if (!$start_date || !$end_date) {
        // Default to the start of the month and today's date
        $start_date = now()->startOfMonth()->toDateString();
        $end_date = now()->toDateString();
    }

    // Execute the raw SQL query for monthly revenue report
    $data = DB::table(DB::raw("
        (
            SELECT 
                DATE_FORMAT(`o`.`created_at`, '%Y-%m') AS `order_month`,
                `p`.`name` AS `product_name`,
                SUM(`p`.`price` * `o`.`quantity`) AS `total_revenue`,
                SUM(`o`.`quantity`) AS `total_quantity`
            FROM 
                `order_items` `o`
            JOIN 
                `products` `p` 
            ON 
                `o`.`product_id` = `p`.`id`
            WHERE 
                `o`.`created_at` BETWEEN :start_date AND :end_date
            GROUP BY 
                `order_month`,
                `product_name`
            ORDER BY 
                `order_month`
        ) as monthly_revenue
    "))
    ->setBindings(['start_date' => $start_date, 'end_date' => $end_date])
    ->paginate(15);

    // Return the view with the data and dates
    return view('orders.indexlaporanBulanan', compact('data', 'start_date', 'end_date'));
        

    }

    public function store(OrderStoreRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
        ]);

        $cart = $request->user()->cart()->get();
        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item->price * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }
        $request->user()->cart()->detach();
        $order->payments()->create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
        ]);

    return 'success';
    }


    public function storereturn(OrderStoreRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
        ]);

        $return = $request->user()->return()->get();
        foreach ($return as $item) {
            $order->items()->create([
                'price' => $item->price * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();

            StokReturn::create([
                'user_id' => $request->user()->id,
                'product_id' => $item->id,
                'quantity' => $item->pivot->quantity, // Log the quantity added
            ]);
        }
        $request->user()->return()->detach();

    return 'success';
    }


    public function storestok(Request $request)
    {


        $stok = $request->user()->stok()->get();

        foreach ($stok as $item) {
            $product = Product::find($item->id);
            if ($product) {
                // Update stok produk
                $product->quantity += $item->pivot->quantity;
                $product->save();

                StokMasuk::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'quantity' => $item->pivot->quantity, // Log the quantity added
                ]);
            }
        }
        $request->user()->stok()->detach();
        return 'success';
    }


  

    function apiproduct() {
        $data =v_order::latest()->first();
        return view('orders.invoice', compact('data'));
        
    }
}
