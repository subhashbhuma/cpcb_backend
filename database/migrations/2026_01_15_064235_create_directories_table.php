<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directories');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('directories', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('cpcb_no', 30)->nullable()->comment('CPCB1009');
            $table->string('name')->nullable();
            $table->string('name_hi')->nullable();
            $table->string('designation')->nullable();
            $table->string('designation_hi')->nullable();
            $table->bigInteger('division_id')->nullable();
            $table->string('office_ph_no', 20)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('ext_number', 20)->nullable();
            $table->string('assigned_work', 255)->nullable();
            $table->string('assigned_work_hi', 255)->nullable();
            $table->integer('order_no')->default(1);
            $table->integer('show_order')->default(1);
            $table->text('remarks')->nullable();
            $table->text('publish_remark')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['order_no', 'show_order']);
            $table->index(['order_no', 'show_order', 'deleted_at']);
        });


    }


};
