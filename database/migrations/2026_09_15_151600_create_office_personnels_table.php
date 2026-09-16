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
        Schema::create('office_personnels', function (Blueprint $table) {
            $table->id();
            $table->string('office_type')->comment('head_offices or regional_directorates');
            $table->unsignedBigInteger('office_id');
            $table->text('title');
            $table->text('title_hi')->nullable();
            $table->string('designation')->nullable();
            $table->string('designation_hi')->nullable();
            $table->integer('order')->default(0);
            $table->tinyInteger('record_status')->default(1)->comment('1:Active, 0:Inactive');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['office_type', 'office_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_personnels');
    }
};
