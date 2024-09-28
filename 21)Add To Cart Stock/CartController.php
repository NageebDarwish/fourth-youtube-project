<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $cartItems = Cart::with('Products')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($cartItems);
    }

    /**
     * check stock
     */
    public function check(Request $request)
    {
        $request->validate([
            'count' => 'required|numeric|min:1',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->count > $product->stock) {
            return response()->json(['error' => 'Requested quantity exceeds available stock'], 400);
        }

        return response()->json(['message' => 'Stock Has Available Products'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'count' => 'required|numeric|min:1',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->count > $product->stock) {
            return response()->json(['error' => 'Requested quantity exceeds available stock'], 400);
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {

            $newCount = $cart->count + $request->count;
            if ($newCount > $product->stock) {
                return response()->json(['error' => 'Total quantity exceeds available stock'], 400);
            }

            $cart->update([
                'count' => $newCount,
            ]);

            return response()->json(['message' => 'Cart updated successfully'], 200);
        } else {

            $cart = Cart::create([
                'count' => $request->count,
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
            ]);

            return response()->json(['message' => 'Cart created successfully'], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
