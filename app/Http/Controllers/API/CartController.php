<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
{
    $cartItems = auth()->user()->carts()->with('product')->get();

    return response()->json([
        'data' => $cartItems
    ]);
}
    public function add(Product $product)
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['quantity' => 0]
        );

        $cart->increment('quantity');

        return response()->json(['message' => 'Added to cart']);
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cart->delete();

        return response()->json(['message' => 'Removed from cart']);
    }
}
