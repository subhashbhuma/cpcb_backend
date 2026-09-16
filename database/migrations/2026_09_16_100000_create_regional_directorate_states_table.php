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
        if (!Schema::hasTable('regional_directorate_states')) {
            Schema::create('regional_directorate_states', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('regional_directorate_id');
                $table->text('title');
                $table->text('title_hi')->nullable();
                $table->integer('order')->default(0);
                $table->tinyInteger('record_status')->default(1)->comment('1:Active, 0:Inactive');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->index('regional_directorate_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regional_directorate_states');
    }
};
