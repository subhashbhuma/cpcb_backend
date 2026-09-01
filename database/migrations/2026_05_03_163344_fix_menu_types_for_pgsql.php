<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Drop existing check constraints
            DB::statement('ALTER TABLE menus DROP CONSTRAINT IF EXISTS menus_type_check');
            DB::statement('ALTER TABLE menus DROP CONSTRAINT IF EXISTS menus_icon_type_check');

            // Alter columns to string
            DB::statement('ALTER TABLE menus ALTER COLUMN "type" TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE menus ALTER COLUMN "icon_type" TYPE VARCHAR(255)');

            // Set defaults if needed (already set in previous migrations but to be safe)
            DB::statement('ALTER TABLE menus ALTER COLUMN "type" SET DEFAULT \'URL\'');
            DB::statement('ALTER TABLE menus ALTER COLUMN "icon_type" SET DEFAULT \'ICON\'');
        } else {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('type')->default('URL')->change();
                $table->string('icon_type')->default('ICON')->change();
            });
        }
    }
};
