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
        Schema::create('head_offices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('division_id')->nullable();
            $table->string('title')->nullable();
            $table->string('title_hi')->nullable();
            $table->string('email')->nullable();
            $table->string('ext_number')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_hi')->nullable();
            $table->integer('is_approved')->default(0)->comment("0:Pending, 1:Approved, 2:Rejected");
            $table->boolean('is_published')->default(0)->comment("0:Draft, 1:Published");
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('head_offices');
    }
};
