<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->string('provider')->nullable();

            $table->string('method')->nullable();

            $table->enum('status', ['pending', 'paid', 'failed', 'canceled']) ->default('pending');

            $table->string('reference')->nullable();

            $table->decimal('amount', 10, 2)->default(0);

            $table->char('currency', 3)->default('USD');

            $table->timestamp('paid_at')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
