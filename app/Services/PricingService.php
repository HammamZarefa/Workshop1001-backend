<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;

class PricingService
{
    /**
     * ---------------------------------------------------------
     * SHARED CALCULATIONS
     * ---------------------------------------------------------
     */

    private function calculateSubtotal($items): float
    {
        return (float) collect($items)->sum(function ($item) {
            // Cart: $item->product->price, Order: $item->price
            $price =  $item->product->price ??$item->price ?? 0;
            return (float) $price * (int) $item->quantity;
        });
    }

    private function calculateDiscount(float $subtotal, float $value, string $type): float
    {
        return match ($type) {
            'percentage' => max(0, min($subtotal * ($value / 100), $subtotal)),
            'fixed'      => max(0, min($value, $subtotal)),
            default      => 0,
        };
    }

    private function calculateTotal(float $subtotal, float $discount, float $tax = 0): float
    {
        return max($subtotal - $discount + $tax, 0);
    }

    /**
     * ---------------------------------------------------------
     * CART CALCULATIONS
     * ---------------------------------------------------------
     */

    public function cartSubtotal(Cart $cart): float
    {
        if (! $cart->relationLoaded('items.product')) {
            $cart->load('items.product');
        }
        return $this->calculateSubtotal($cart->items);
    }

    public function cartCouponDiscount(Cart $cart, ?Coupon $coupon): float
    {
        if (! $coupon) return 0.0;
        $subtotal = $this->cartSubtotal($cart);
        return $this->calculateDiscount($subtotal, (float)$coupon->value, $coupon->type);
    }

    public function cartTotalsWithTax(Cart $cart, ?Coupon $coupon = null, ?float $taxRate = null): array
    {
        $taxRate = $taxRate ?? config('tax.default_rate', 0);
        $subtotal = $this->cartSubtotal($cart);
        $discount = $this->cartCouponDiscount($cart, $coupon);
        $totalBeforeTax = max($subtotal - $discount, 0);
        $taxAmount = $totalBeforeTax * ($taxRate / 100);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax'      => $taxAmount,
            'total'    => $totalBeforeTax + $taxAmount,
        ];
    }

    /**
     * ---------------------------------------------------------
     * ORDER CALCULATIONS
     * ---------------------------------------------------------
     */

    public function orderSubtotal(Order $order): float
    {
        return $this->calculateSubtotal($order->items);
    }

    public function orderDiscountTotal(Order $order): float
    {
        $subtotal = $this->orderSubtotal($order);
        $discountRate = (float) ($order->discount_percentage ?? 0);
        return $this->calculateDiscount($subtotal, $discountRate, 'percentage');
    }

    public function orderCouponDiscount(Order $order): float
    {
        return (float) ($order->coupon_value ?? 0);
    }

    public function orderTaxTotal(Order $order): float
    {
        $subtotal = $this->orderSubtotal($order);
        $discount = $this->orderDiscountTotal($order);
        $coupon   = $this->orderCouponDiscount($order);
        $totalBeforeTax = max($subtotal - $discount - $coupon, 0);
        $taxRate = (float) ($order->tax_amount ?? 0);

        return $totalBeforeTax * ($taxRate / 100);
    }

    public function orderTotal(Order $order): float
    {
        $subtotal = $this->orderSubtotal($order);
        $discount = $this->orderDiscountTotal($order);
        $coupon   = $this->orderCouponDiscount($order);
        $tax      = $this->orderTaxTotal($order);

        return $this->calculateTotal($subtotal, $discount + $coupon, $tax);
    }

    public function orderBreakdown(Order $order): array
    {
        $subtotal = $this->orderSubtotal($order);
        $discount = $this->orderDiscountTotal($order);
        $coupon   = $this->orderCouponDiscount($order);
        $tax      = $this->orderTaxTotal($order);
        $total    = $this->orderTotal($order);

        return compact('subtotal', 'discount', 'coupon', 'tax', 'total');
    }
}
