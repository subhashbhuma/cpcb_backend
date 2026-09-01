<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            //add new security columns
            $table->string('current_session_id')->nullable()->after('remember_token');
            $table->timestamp('lockout_until')->nullable()->after('current_session_id');
            $table->integer('failed_logins')->default(0)->after('lockout_until');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'current_session_id',
                'lockout_until',
                'failed_logins'
            ]);
        });
    }
};
?>