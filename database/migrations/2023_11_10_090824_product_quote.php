<?php

use App\Models\Quote;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_quote', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Quote::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->unsignedInteger('quantity');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_quote');
    }
};
