<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('directories')) {
            return;
        }

        // 1. Check if the necessary columns exist before running data normalization
        if (Schema::hasColumn('directories', 'order_no') && Schema::hasColumn('directories', 'show_order')) {
            $this->normalizeExistingOrders();
        }

        // 2. Safely update or create the columns and indexes
        Schema::table('directories', function (Blueprint $table) {
            if (Schema::hasColumn('directories', 'show_order')) {
                // If it exists, change its type to integer
                $table->integer('show_order')->default(1)->change();
            } else {
                // If it doesn't exist at all, create it!
                $table->integer('show_order')->default(1);
            }
            
            // Only add unique constraint if both columns exist and the index doesn't
            if (Schema::hasColumn('directories', 'order_no') && !$this->indexExists('directories', 'directories_order_no_show_order_unique')) {
                $table->unique(['order_no', 'show_order'], 'directories_order_no_show_order_unique');
            }
            
            // Only add lookup index if both columns exist and the index doesn't
            if (Schema::hasColumn('directories', 'order_no') && !$this->indexExists('directories', 'directories_order_lookup_index')) {
                $table->index(['order_no', 'show_order', 'deleted_at'], 'directories_order_lookup_index');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('directories')) {
            return;
        }

        Schema::table('directories', function (Blueprint $table) {
            if ($this->indexExists('directories', 'directories_order_no_show_order_unique')) {
                $table->dropUnique('directories_order_no_show_order_unique');
            }
            if ($this->indexExists('directories', 'directories_order_lookup_index')) {
                $table->dropIndex('directories_order_lookup_index');
            }
            
            if (Schema::hasColumn('directories', 'show_order')) {
                $table->string('show_order')->default('1')->change();
            }
        });
    }

    private function normalizeExistingOrders(): void
    {
        DB::transaction(function () {
            $groups = DB::table('directories')
                ->select('order_no')
                ->whereNull('deleted_at')
                ->distinct()
                ->pluck('order_no');

            foreach ($groups as $orderNo) {
                $rows = DB::table('directories')
                    ->where('order_no', $orderNo)
                    ->whereNull('deleted_at')
                    ->orderByRaw('CAST(show_order AS INTEGER) ASC') 
                    ->orderBy('id')
                    ->get(['id']);

                $position = 1;
                foreach ($rows as $row) {
                    DB::table('directories')
                        ->where('id', $row->id)
                        ->update(['show_order' => $position++]);
                }
            }

            DB::table('directories')
                ->whereNotNull('deleted_at')
                ->orderBy('id')
                ->get(['id'])
                ->each(function ($row) {
                    DB::table('directories')
                        ->where('id', $row->id)
                        ->update(['show_order' => -abs((int) $row->id)]);
                });
        });
    }

    private function indexExists(string $table, string $name): bool
    {
        try {
            $indexes = Schema::getIndexes($table);
        } catch (\Throwable) {
            return false;
        }

        return collect($indexes)
            ->contains(fn (array $index) => ($index['name'] ?? null) === $name);
    }
};