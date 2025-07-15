<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->byUser(Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')
            ->byUser(Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItems = CartItem::with('product')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = $cartItems->sum('total');
        $shippingFee = 30000; // 30k VND shipping fee
        $total = $subtotal + $shippingFee;

        return view('orders.checkout', compact('cartItems', 'subtotal', 'shippingFee', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email',
            'billing_phone' => 'required|string|regex:/^0[0-9]{9}$/',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string|max:100',
            'billing_district' => 'required|string|max:100',
            'billing_ward' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,bank_transfer',
            'notes' => 'nullable|string|max:500',]
        ,[
                
            'billing_phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng.',
        ]);

        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItems = CartItem::with('product')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = $cartItems->sum('total');
        $shippingFee = 30000;
        $total = $subtotal + $shippingFee;

        DB::transaction(function () use ($request, $cartItems, $total, $shippingFee) {
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total_amount' => $total,
                'shipping_amount' => $shippingFee,
                'billing_address' => [
                    'name' => $request->billing_name,
                    'email' => $request->billing_email,
                    'phone' => $request->billing_phone,
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'district' => $request->billing_district,
                    'ward' => $request->billing_ward,
                ],
                'shipping_address' => [
                    'name' => $request->billing_name,
                    'email' => $request->billing_email,
                    'phone' => $request->billing_phone,
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'district' => $request->billing_district,
                    'ward' => $request->billing_ward,
                ],
                'payment_method' => $request->payment_method,
                // Logic thanh toán đúng theo thực tế:
                // - Bank transfer: đã chuyển khoản trước → completed
                // - COD: thanh toán khi nhận hàng → pending cho đến khi delivered
                'payment_status' => $request->payment_method === 'bank_transfer' ? 'completed' : 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->final_price,
                    'total' => $cartItem->quantity * $cartItem->product->final_price,
                ]);

                // Update product stock
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Clear cart
            $cartItems->each->delete();
        });

        return redirect()->route('orders.index')
            ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy đơn hàng này.'
            ]);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng đã được xác nhận.'
            ]);
        }

        $order->update(['status' => 'cancelled']);
        // payment_status được tự động cập nhật qua Order model boot method

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy đơn hàng thành công.'
        ]);
    }
}
