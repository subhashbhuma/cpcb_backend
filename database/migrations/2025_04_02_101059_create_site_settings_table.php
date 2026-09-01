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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('site_name_hi');
            $table->string('header_logo')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('favicon')->nullable();
            $table->string('header_img_1')->nullable();
            $table->string('header_name_1')->nullable();
            $table->string('header_name_1_hi')->nullable();
            $table->string('header_img_2')->nullable();
            $table->string('header_name_2')->nullable();
            $table->string('header_name_2_hi')->nullable();
            $table->string('header_img_3')->nullable();
            $table->string('header_name_3')->nullable();
            $table->string('header_name_3_hi')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('admin_panel_logo')->nullable();
            $table->string('site_address')->nullable();
            $table->string('site_address_hi')->nullable();
            $table->text('disclaimer')->nullable();
            $table->text('disclaimer_hi')->nullable();
            $table->text('copyright_text')->nullable();
            $table->text('copyright_text_hi')->nullable();
            $table->text('maintained_by_text')->nullable();
            $table->text('maintained_by_text_hi')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Adds 'deleted_at' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
