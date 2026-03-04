<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pomodoros', function (Blueprint $table) {
            $table->boolean('hidden_from_list')->default(false); // 非表示フラグ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pomodoros', function (Blueprint $table) {
            //
        });
    }
};
