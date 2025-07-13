<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = $this->getCartItems();
        $total = $cartItems->sum('total');

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        // Nếu là AJAX request mà user chưa đăng nhập, trả về JSON error
        if ($request->ajax() && !Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!',
                'redirect' => route('login')
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is in stock
        if (!$product->in_stock || $product->stock_quantity < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không đủ hàng trong kho.'
                ], 200, ['Content-Type' => 'application/json']);
            }
            return back()->with('error', 'Sản phẩm không đủ hàng trong kho.');
        }

        $userId = Auth::id();

        $cartItem = CartItem::where('product_id', $request->product_id)
            ->where('user_id', $userId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock_quantity < $newQuantity) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá hàng tồn kho.'
                    ], 200, ['Content-Type' => 'application/json']);
                }
                return back()->with('error', 'Số lượng vượt quá hàng tồn kho.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'session_id' => null,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!'
            ], 200, ['Content-Type' => 'application/json']);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($cartItem->product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Số lượng vượt quá hàng tồn kho.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Đã cập nhật số lượng sản phẩm!');
    }

    public function remove($id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $cartItem->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function clear()
    {
        $this->getCartItems()->each->delete();

        return back()->with('success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng!');
    }

    private function getCartItems()
    {
        $userId = Auth::id();

        return CartItem::with('product')
            ->where('user_id', $userId)
            ->get();
    }

    public function count()
    {
        $count = $this->getCartItems()->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
