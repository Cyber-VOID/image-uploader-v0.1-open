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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ikey');
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->string('slug');
            $table->string('image_name');
            $table->string('path');
            $table->enum('ispublic', [0, 1]);
            $table->string('token');
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->unsignedBigInteger('url_id')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('collection_id')->references('id')->on('images_collections')->cascadeOnDelete();
            $table->foreign('url_id')->references('id')->on('public_urls')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
