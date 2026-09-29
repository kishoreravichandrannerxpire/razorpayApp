<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 15)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     * Replace NULL phones with an empty string before re-adding NOT NULL constraint.
     */
    public function down(): void
{
    // Give each NULL phone a unique placeholder to satisfy the unique
    // constraint before re-adding NOT NULL.
    DB::table('users')->whereNull('phone')->orderBy('id')->get()->each(function ($user) {
        DB::table('users')->where('id', $user->id)->update(['phone' => 'na-' . $user->id]);
    });

    Schema::table('users', function (Blueprint $table) {
        $table->string('phone', 15)->nullable(false)->change();
    });
}
};
