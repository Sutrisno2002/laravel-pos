<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StokMasuk;


class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->cart()->get()
            );
        }
        return view('cart.index');
    }

    public function indexreturn(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->return()->get()
            );
        }
        return view('cart.indexreturn');
    }

    public function addstok(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->stok()->get()
            );
        }
        return view('cart.indexstok');
    }

    public function addreturn(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->return()->get()
            );
        }
        return view('cart.indexreturn');
    }

    public function indexstok(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->stok()->get()
            );
        }
        return view('cart.indexstok');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;

        $product = Product::where('barcode', $barcode)->first();
        $cart = $request->user()->cart()->where('barcode', $barcode)->first();
        if ($cart) {
            // check product quantity
            if ($product->quantity <= $cart->pivot->quantity) {
                return response([
                    'message' => __('cart.available', ['quantity' => $product->quantity]),
                ], 400);
            }
            // update only quantity
            $cart->pivot->quantity = $cart->pivot->quantity + 1;
            $cart->pivot->save();
        } else {
            if ($product->quantity < 1) {
                return response([
                    'message' => __('cart.outstock'),
                ], 400);
            }
            $request->user()->cart()->attach($product->id, ['quantity' => 1]);
        }

        return response('', 204);
    }

    public function storereturn(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;

        $product = Product::where('barcode', $barcode)->first();
        $return = $request->user()->return()->where('barcode', $barcode)->first();
        if ($return) {
            // check product quantity
            if ($product->quantity <= $return->pivot->quantity) {
                return response([
                    'message' => __('cart.available', ['quantity' => $product->quantity]),
                ], 400);
            }
            // update only quantity
            $return->pivot->quantity = $return->pivot->quantity + 1;
            $return->pivot->save();
        } else {
            if ($product->quantity < 1) {
                return response([
                    'message' => __('cart.outstock'),
                ], 400);
            }
            $request->user()->return()->attach($product->id, ['quantity' => 1]);
        }

        return response('', 204);
    }


    public function showStokMasuk()
    {

    // Fetch all records from the stok_masuk table, ordered by the most recent
    $stokMasuk = StokMasuk::with('product', 'user')->orderBy('created_at', 'desc')->get();
        return $stokMasuk;
    // Pass the data to the view
    // return view('orders.indexStokLaporan', compact('stokMasuk'));
    }

    public function storestok(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;

        $product = Product::where('barcode', $barcode)->first();
        $stok = $request->user()->stok()->where('barcode', $barcode)->first();
        if ($stok) {
            // check product quantity
            if ($product->quantity <= $stok->pivot->quantity) {
                return response([
                    'message' => __('stok.available', ['quantity' => $product->quantity]),
                ], 400);
            }
            // update only quantity
            $stok->pivot->quantity = $stok->pivot->quantity + 1;
            $stok->pivot->save();
        } else {
            if ($product->quantity < 1) {
                return response([
                    'message' => __('cart.outstock'),
                ], 400);
            }
            $request->user()->stok()->attach($product->id, ['quantity' => 1]);
        }

        DB::table('stok_masuk')->insert([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'quantity' => 1, // Assuming 1 is added each time
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    

        return response('', 204);
    }

    public function changeQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $cart = $request->user()->cart()->where('id', $request->product_id)->first();

        if ($cart) {
            // check product quantity
            if ($product->quantity < $request->quantity) {
                return response([
                    'message' => __('cart.available', ['quantity' => $product->quantity]),
                ], 400);
            }
            $cart->pivot->quantity = $request->quantity;
            $cart->pivot->save();
        }

        return response([
            'success' => true
        ]);
    }

    public function changeQtystok(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $stok = $request->user()->stok()->where('id', $request->product_id)->first();

        if ($stok) {
            // check product quantity
            $stok->pivot->quantity = $request->quantity;
            $stok->pivot->save();
        }

        return response([
            'success' => true
        ]);
    }

    public function changeQtyreturn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $return = $request->user()->return()->where('id', $request->product_id)->first();

        if ($return) {
            // check product quantity
            $return->pivot->quantity = $request->quantity;
            $return->pivot->save();
        }

        return response([
            'success' => true
        ]);
    }


    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        $request->user()->cart()->detach($request->product_id);

        return response('', 204);
    }


    public function deletestok(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        $request->user()->stok()->detach($request->product_id);

        return response('', 204);
    }

    public function deletereturn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        $request->user()->return()->detach($request->product_id);

        return response('', 204);
    }

    public function empty(Request $request)
    {
        $request->user()->cart()->detach();

        return response('', 204);
    }
}
