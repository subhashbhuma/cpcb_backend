<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->enum('job_type', ['regular', 'contract'])->default('regular')->nullable();
            $table->longText('title');
            $table->longText('title_hi')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('advertisement_file_name')->nullable();
            $table->string('advertisement_file_hi_name')->nullable();

            $table->enum('direct_application', ['online', 'offline'])->default('offline')->nullable();
            $table->enum('deputation_application', ['online', 'offline'])->default('offline')->nullable();
            $table->string('direct_application_form_name')->nullable();
            $table->string('direct_application_form_hi_name')->nullable();
            $table->string('deputation_application_form_name')->nullable();
            $table->string('deputation_application_form_hi_name')->nullable();
            $table->string('direct_application_url')->nullable();
            $table->string('deputation_application_url')->nullable();
            $table->string('online_form_url')->nullable();
            $table->date('walk_in_interview_date')->nullable();


            $table->string('remarks')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
