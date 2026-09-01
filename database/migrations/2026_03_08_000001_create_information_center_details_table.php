<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('information_center_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('information_center_id');
            $table->enum('type', ['FILE', 'URL']);
            $table->string('url')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_name_hi')->nullable();
            $table->string('title');
            $table->string('title_hi');
            $table->text('remarks')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_center_details');
    }
};
