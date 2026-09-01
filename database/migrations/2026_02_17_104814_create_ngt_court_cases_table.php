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
        Schema::create('ngt_court_cases', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('title_hi')->nullable();
            $table->string('qa_number')->nullable();
            $table->string('qa_number_hi')->nullable();
            $table->longText('type')->nullable();
            $table->longText('type_hi')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_name_hi')->nullable();
            $table->date('publish_date')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ngt_court_cases');
    }
};
