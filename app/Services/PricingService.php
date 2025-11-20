<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;

class PricingService
{
    /**
     * ---------------------------------------------------------
     *  CART CALCULATIONS
     * ---------------------------------------------------------
     */

    public function cartSubtotal(Cart $cart): float
    {
        // نحمّل العلاقة items.product لتجنب N+1 query
        if (! $cart->relationLoaded('items.product')) {
            $cart->load('items.product');
        }

        return (float) $cart->items->sum(function ($item) {
            // السعر دائمًا من المنتج في قاعدة البيانات
            $price = (float) $item->product->price;
            return $price * (int) $item->quantity;
        });
    }

    public function cartCouponDiscount(float $subtotal, ?Coupon $coupon): float
    {
        if ($subtotal <= 0 || ! $coupon) {
            return 0.0;
        }

        $value = (float) $coupon->value;

        return match ($coupon->type) {
            'percentage' => max(0, min($subtotal * ($value / 100), $subtotal)),
            'fixed'      => max(0, min($value, $subtotal)),
            default      => 0.0,
        };
    }

    public function cartTotals(Cart $cart, ?Coupon $coupon): array
    {
        $subtotal = $this->cartSubtotal($cart);
        $discount = $this->cartCouponDiscount($subtotal, $coupon);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total'    => max($subtotal - $discount, 0),
        ];
    }

    /**
     * ---------------------------------------------------------
     *  ORDER CALCULATIONS
     * ---------------------------------------------------------
     */

    public function orderSubtotal(Order $order): float
    {
        return (float) $order->items->sum(function ($item) {
            // دائمًا نستخدم السعر المخزن في OrderItem
            return (float) $item->price * (int) $item->quantity;
        });
    }

    public function orderTaxTotal(Order $order): float
    {
        $subtotal = $this->orderSubtotal($order);
        $taxRate  = (float) $order->tax_amount;

        if ($subtotal <= 0 || $taxRate <= 0) {
            return 0.0;
        }

        return $subtotal * ($taxRate / 100);
    }

    public function orderDiscountTotal(Order $order): float
    {
        $subtotal      = $this->orderSubtotal($order);
        $discountRate  = (float) $order->discount_percentage;

        if ($subtotal <= 0 || $discountRate <= 0) {
            return 0.0;
        }

        return $subtotal * ($discountRate / 100);
    }

    public function orderCouponDiscount(Order $order): float
    {
        return (float) ($order->coupon_value ?? 0);
    }

    public function orderTotal(Order $order): float
    {
        $subtotal = $this->orderSubtotal($order);
        $tax      = $this->orderTaxTotal($order);
        $discount = $this->orderDiscountTotal($order);
        $coupon   = $this->orderCouponDiscount($order);

        $total = $subtotal + $tax - $discount - $coupon;

        return max($total, 0);
    }

    public function orderBreakdown(Order $order): array
    {
        return [
            'subtotal' => $this->orderSubtotal($order),
            'tax'      => $this->orderTaxTotal($order),
            'discount' => $this->orderDiscountTotal($order),
            'coupon'   => $this->orderCouponDiscount($order),
            'total'    => $this->orderTotal($order),
        ];
    }
}
