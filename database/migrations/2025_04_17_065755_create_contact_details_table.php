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
        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_hi');
            $table->string('department')->nullable();
            $table->string('department_hi')->nullable();
            $table->text('address')->nullable();
            $table->text('address_hi')->nullable();
            $table->text('phone_numbers')->nullable();
            $table->text('email_ids')->nullable();
            $table->string('profile_image')->nullable();
            $table->integer('myorder')->default(0)->comment('Order of the contact detail');
            $table->text('remarks')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
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
        Schema::dropIfExists('contact_details');
    }
};
