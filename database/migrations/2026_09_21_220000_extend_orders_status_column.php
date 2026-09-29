<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extend orders status to include Shipped and Refunded.
     * SQLite does not support ALTER COLUMN for enums, so we
     * convert the column to a plain string while preserving data.
     */
    public function up(): void
    {
        // SQLite-compatible: rename → recreate → copy → drop old
        // For MySQL/Postgres, a simple change() call would suffice,
        // but we use DB::statement for broadest compatibility.
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite workaround: add a temp column, copy, drop original, rename
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status_new', 20)->default('Pending')->after('currency');
            });

            DB::statement("UPDATE orders SET status_new = status");

            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('status');
            });

            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('status_new', 'status');
            });
        } else {
            // MySQL / PostgreSQL
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status', 20)->default('Pending')->change();
            });
        }
    }

    /**
     * Revert back to the original 4-value enum (best-effort).
     */
    public function down(): void
    {
        // Simply leave as string — reverting enum on SQLite is complex
        // and rollback to enum on MySQL would drop Shipped/Refunded records.
    }
};
