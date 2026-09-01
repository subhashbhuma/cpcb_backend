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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_number')->nullable()->change();
            $table->unsignedBigInteger('designation_id')->nullable()->after('mobile_number');
            $table->string('emp_code')->nullable()->after('designation_id');
            $table->integer('level')->nullable()->after('emp_code');
            $table->integer('cell')->nullable()->after('level');
            $table->string('posted_at')->nullable()->after('cell');
            $table->string('pan_no')->nullable()->after('posted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_number')->nullable(false)->change();
            $table->dropColumn([
                'designation_id',
                'emp_code',
                'level',
                'cell',
                'posted_at',
                'pan_no'
            ]);
        });
    }
};
