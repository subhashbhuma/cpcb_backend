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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->string('title');
            $table->string('title_hi');
            $table->decimal('price');
            $table->string('file_name')->nullable();
            $table->string('file_name_hi')->nullable();
            $table->date('published_date')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication');
    }
};
