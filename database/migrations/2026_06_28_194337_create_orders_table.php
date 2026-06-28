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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            /*$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('delivery_address');
            $table->string('delivery_trip')->nullable();
            $table->text('note')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('tracking_status')->default('pending');
            // pending, confirmed, preparing, picked_up, on_the_way, delivered, cancelled
            $table->string('estimated_delivery')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->foreignId('delivery_rider_id')->nullable()->constrained('delivery_riders')->nullOnDelete();*/
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
