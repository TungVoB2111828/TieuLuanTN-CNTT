<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []); // Giỏ hàng dạng mảng

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['product']->price * $item['quantity'];
        }

        return view('cart.index', [
            'products' => $cart,
            'total' => $total
        ]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $action = $request->input('action');

        if (isset($cart[$productId])) {
            if ($action === 'increase') {
                $cart[$productId]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$productId]['quantity']--;
                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }
            } elseif ($action === 'remove') {
                unset($cart[$productId]);
            }
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ]);
        }

        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cartCount' => count($cart)
        ]);
    }

    public function clear(Request $request)
    {
        // Xóa giỏ hàng, ví dụ xóa session hoặc xóa bảng cart
        session()->forget('cart');

        return redirect()->back()->with('success', 'Giỏ hàng đã được xóa.');
    }
}
