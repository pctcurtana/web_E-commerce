<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'total_amount',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'billing_address',
        'shipping_address',
        'payment_status',
        'payment_method',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Auto-update payment_status based on order status changes
        static::updating(function ($order) {
            // Check if status is being changed
            if ($order->isDirty('status')) {
                $newStatus = $order->status;
                $paymentMethod = $order->payment_method;
                $currentPaymentStatus = $order->getOriginal('payment_status');
                
                // Business logic for payment status
                switch ($newStatus) {
                    case 'delivered':
                        // COD orders: payment completed when delivered
                        // Bank transfer: already completed from creation
                        if ($paymentMethod === 'cod') {
                            $order->payment_status = 'completed';
                        }
                        break;
                        
                    case 'cancelled':
                        // Business logic for cancelled orders:
                        // - Bank transfer đã thanh toán → hoàn tiền
                        // - COD chưa thanh toán → thất bại
                        if ($paymentMethod === 'bank_transfer' && $currentPaymentStatus === 'completed') {
                            $order->payment_status = 'refunded';
                        } else {
                            $order->payment_status = 'failed';
                        }
                        break;
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
