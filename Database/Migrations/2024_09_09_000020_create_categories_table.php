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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')
                ->unique();
            $table->string('description');
            $table->boolean('status')
                ->default(false);
            $table->enum('category_type', ['product', 'post'])
                ->nullable();
            $table->timestamps();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
