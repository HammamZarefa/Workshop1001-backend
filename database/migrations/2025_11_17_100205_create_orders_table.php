<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        
            $table->decimal('coupon_value', 10, 2)->default(0);
            $table->string('shipping_address');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->char('currency', 3)->default('USD');
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
