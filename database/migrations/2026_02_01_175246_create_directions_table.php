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
        Schema::create('directions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('direction_act_type_id')->nullable();
            $table->bigInteger('direction_type_id')->nullable();
            $table->bigInteger('direction_subject_id')->nullable();
            $table->text('title')->nullable();
            $table->text('title_hi')->nullable();
            $table->date('publish_date')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_name_hi')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('direction_state_id')->nullable();
            $table->text('direction_category_id')->nullable();
            $table->text('direction_issued_to_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directions');
    }
};
