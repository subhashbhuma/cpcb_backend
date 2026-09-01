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
        Schema::create('tender_corrigendums', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tender_id');
            $table->text('title')->nullable();
            $table->text('title_hi')->nullable();
            $table->string('file_name');
            $table->string('file_name_hi');
            $table->text('description')->nullable();
            $table->text('description_hi')->nullable();
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
        Schema::dropIfExists('tender_corrigendums');
    }
};
