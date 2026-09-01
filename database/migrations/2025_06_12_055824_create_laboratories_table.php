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

        Schema::create('laboratories_category', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('title_hi')->nullable();
            $table->string('slogan')->nullable();
            $table->string('slogan_hi')->nullable();
            $table->text('description')->nullable();
            $table->text('description_hi')->nullable();
            $table->string('featured_image')->nullable();
            $table->tinyInteger('is_approved')->default(0)->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->boolean('is_published')->default(false)->comment('Indicates if the FAQ is published');
            $table->string('remarks')->nullable()->comment('Remarks for the FAQ');
            $table->unsignedBigInteger('created_by')->nullable()->comment('ID of the user who created the FAQ');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('ID of the user who last updated the FAQ');
            $table->string('permission_group');
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');
        });
        Schema::create('laboratories_page', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->comment('ID of the category this laboratory belongs to');
            $table->string('title')->nullable()->comment('Title of the laboratory in English');
            $table->string('title_hi')->nullable()->comment('Title of the laboratory in Hindi');
            $table->string('featured_image')->nullable()->comment('Featured image URL');
            $table->text('public_comments')->nullable()->comment('Public comments in English');
            $table->text('public_comments_hi')->nullable()->comment('Public comments in Hindi');
            $table->string('public_comments_url')->nullable()->comment('URL for public comments');
            $table->text('content')->nullable()->comment('Content of the laboratory in English');
            $table->text('content_hi')->nullable()->comment('Content of the laboratory in Hindi');
            $table->tinyInteger('is_approved')->default(0)->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->boolean('is_published')->default(false)->comment('Indicates if the laboratory is published');
            $table->string('remarks')->nullable()->comment('Remarks for the laboratory');
            $table->unsignedBigInteger('created_by')->nullable()->comment('ID of the user who created the laboratory');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('ID of the user who last updated the laboratory');
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');
        });
        Schema::create('laboratories_file', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id');
            $table->string('file_name')->nullable()->comment('File name in English');
            $table->string('file_name_hi')->nullable()->comment('File name in Hindi');
            $table->string('title')->nullable()->comment('Title of the file in English');
            $table->string('title_hi')->nullable()->comment('Title of the file in Hindi');
            $table->date('date')->nullable()->comment('Date associated with the file');
            $table->string('type')->nullable()->comment('Type of the file');
            $table->text('description')->nullable()->comment('Description of the file in English');
            $table->text('description_hi')->nullable()->comment('Description of the file in Hindi');
            $table->unsignedBigInteger('created_by')->nullable()->comment('ID of the user who created the file');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('ID of the user who last updated the file');
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete timestamp');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratories_category');
        Schema::dropIfExists('laboratories_page');
        Schema::dropIfExists('laboratories_file');
    }
};
