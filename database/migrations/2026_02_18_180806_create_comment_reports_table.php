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
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('title_hi')->nullable();
            $table->text('emails')->nullable();
            $table->date('published_date')->nullable();
            $table->text('description')->nullable();
            $table->text('description_hi')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_name_hi')->nullable();
            $table->boolean('is_approved')->default(false)->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->boolean('is_published')->default(false)->comment('0: Draft, 1: Published');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
    }
};
