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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('image');
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('calories', 8, 2)->nullable();
            $table->decimal('carbs', 8, 2)->nullable();
            $table->decimal('protein', 8, 2)->nullable();
            $table->decimal('fat', 8, 2)->nullable();
            $table->decimal('fiber', 8, 2)->nullable();
            $table->json('ingredients')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
