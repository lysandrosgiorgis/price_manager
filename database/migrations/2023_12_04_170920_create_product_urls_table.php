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
        Schema::create('product_urls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_company_id');
            $table->string('name');
            $table->string('url');
            $table->timestamps();
            $table->foreign('product_company_id')->references('id')->on('products_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_urls');
    }
};
