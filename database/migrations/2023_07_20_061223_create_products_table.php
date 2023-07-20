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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->tinyInteger('type');
            $table->string('name')->nullable();
            $table->string('name_cn')->nullable();
            $table->string('name_en')->nullable();
            $table->bigInteger('category_id');
            $table->string('category_ids')->nullable();
            $table->string('slug');
            $table->text('thumb')->nullable();
            $table->text('thumbs')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('content')->nullable();
            $table->text('content_cn')->nullable();
            $table->text('content_en')->nullable();
            $table->string('weight')->nullable();
            $table->string('size')->nullable();
            $table->integer('ship_quocte_nktd')->nullable();
            $table->integer('ship_quocte_nktm')->nullable();
            $table->dateTime('approved_request_at');
            $table->dateTime('approved_at');
            $table->tinyInteger('status_approve')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
