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
        Schema::create('page_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id');
            $table->string('file_name')->nullable();
            $table->string('file_name_hi')->nullable();
            $table->string('title')->nullable();
            $table->string('title_hi')->nullable();
            $table->integer('order_number')->default(0)->nullable();
            $table->date('upload_date')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_files');
    }
};
