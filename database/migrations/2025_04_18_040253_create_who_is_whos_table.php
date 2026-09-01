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
        Schema::create('who_is_whos', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->default(0);
            $table->string('image')->default('no-image.png');
            $table->string('name');
            $table->string('name_hi');
            $table->string('designation')->nullable();
            $table->string('designation_hi')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email_id')->nullable();
            $table->string('address')->nullable();
            $table->string('address_hi')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('show_on_homepage')->default(0);
            $table->integer('hide_on_who_is_who')->default(0);
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('who_is_whos');
    }
};
