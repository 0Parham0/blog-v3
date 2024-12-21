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
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id(); #todo.ma: pivot no need id
            $table->foreignId('blog_id')->nullable(false)->constrained();
            $table->foreignId('tag_id')->nullable(false)->constrained();
            $table->timestamps(); #todo.ma: pivot no need timestamps

            $table->unique(['blog_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_tags');
    }
};
