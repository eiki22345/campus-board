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
        Schema::create('post_mentions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_post_id')
                ->constrained('posts')
                ->onDelete('cascade');

            $table->foreignId('post_id')
                ->constrained('posts')
                ->onDelete('cascade');

            $table->unique(['parent_post_id', 'post_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_mentions');
    }
};
