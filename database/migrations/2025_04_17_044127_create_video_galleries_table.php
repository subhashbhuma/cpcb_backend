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
        Schema::create('video_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_event_id')->nullable()->constrained();
            $table->string('thumbnail_image');
            $table->integer('type'); // 1 = file, 2 = youtube embed link, 3 = url
            $table->string('file_name')->nullable();
            $table->string('youtube_embed_code')->nullable();
            $table->string('url')->nullable();
            $table->string('title');
            $table->string('title_hi');
            $table->text('description')->nullable();
            $table->text('description_hi')->nullable();
            $table->dateTime('date')->nullable();
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
        Schema::dropIfExists('video_galleries');
    }
};
