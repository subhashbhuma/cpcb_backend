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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question')->comment('Question in English');
            $table->string('question_hi')->nullable()->comment('Question in Hindi');
            $table->text('answer')->comment('Answer in English');
            $table->text('answer_hi')->nullable()->comment('Answer in Hindi');
            $table->tinyInteger('is_approved')->default(0)->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->boolean('is_published')->default(false)->comment('Indicates if the FAQ is published');
            $table->string('remarks')->nullable()->comment('Remarks for the FAQ');
            $table->unsignedBigInteger('created_by')->nullable()->comment('ID of the user who created the FAQ');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('ID of the user who last updated the FAQ');
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
