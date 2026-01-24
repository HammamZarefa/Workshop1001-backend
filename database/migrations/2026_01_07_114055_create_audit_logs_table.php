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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Who
            $table->foreignId('admin_id')
                ->constrained('users')
                ->casw2cadeOnDelete();

            $table->string('action');

            $table->string('resource');

            $table->unsignedBigInteger('resource_id')->nullable();

            $table->ipAddress('ip_address');

            $table->timestamp('created_at')->useCurrent();

            $table->index(['admin_id', 'action']);
            $table->index(['resource', 'resource_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
