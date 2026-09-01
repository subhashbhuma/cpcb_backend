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

        Schema::create('agra_air_qualities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quality_zone_id');
            $table->text('title')->nullable();
            $table->text('title_hi')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_name_hi')->nullable();
            $table->date('for_date')->nullable();
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
        Schema::dropIfExists('agra_air_qualities');
    }
};
