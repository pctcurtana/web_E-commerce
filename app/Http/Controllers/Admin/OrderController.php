<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user:id,name,email', 'orderItems'])
            ->withCount('orderItems');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereJsonContains('billing_address->name', $search)
                  ->orWhereJsonContains('billing_address->phone', $search);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Amount range filter
        if ($request->filled('amount_from')) {
            $query->where('total_amount', '>=', $request->amount_from);
        }
        if ($request->filled('amount_to')) {
            $query->where('total_amount', '<=', $request->amount_to);
        }

        $orders = $query->latest()->paginate(15);

        // Statistics for dashboard
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('delivered_at', today())
                                   ->where('status', 'delivered')
                                   ->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Validate status transitions
        if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chuyển từ trạng thái "' . $this->getStatusText($oldStatus) . '" sang "' . $this->getStatusText($newStatus) . '".'
            ]);
        }

        $updateData = ['status' => $newStatus];

        // Set timestamps for tracking
        if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
            $updateData['shipped_at'] = now();
        } elseif ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);
        // Note: payment_status được tự động cập nhật qua Order model boot method

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái đơn hàng thành công!',
            'status_text' => $this->getStatusText($newStatus)
        ]);
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user:id,name,email', 'orderItems']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();

        $filename = 'orders_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($handle, [
                'Mã đơn hàng',
                'Khách hàng',
                'Email',
                'Trạng thái',
                'Tổng tiền',
                'Phương thức thanh toán',
                'Trạng thái thanh toán',
                'Ngày đặt',
                'Ghi chú'
            ]);

            // CSV data
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->user->name ?? 'Khách vãng lai',
                    $order->user->email ?? '',
                    $order->status,
                    number_format($order->total_amount),
                    $order->payment_method,
                    $order->payment_status,
                    $order->created_at->format('d/m/Y H:i'),
                    strip_tags($order->notes ?? '')
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * We don't need create, store, edit, update, destroy for orders
     * Orders are created from frontend checkout process
     */
    public function create() { return abort(404); }
    public function store() { return abort(404); }
    public function edit() { return abort(404); }
    public function update() { return abort(404); }
    public function destroy() { return abort(404); }

    /**
     * Validate if status transition is allowed
     */
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        // If same status, allow (no change)
        if ($oldStatus === $newStatus) {
            return true;
        }

        // Define allowed transitions
        $allowedTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => [], // Final state - cannot change
            'cancelled' => [] // Final state - cannot change
        ];

        return in_array($newStatus, $allowedTransitions[$oldStatus] ?? []);
    }

    /**
     * Get status text in Vietnamese
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy'
        ];

        return $statusTexts[$status] ?? ucfirst($status);
    }
}
