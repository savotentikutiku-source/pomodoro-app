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
        Schema::create('pomo_records', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');       // 学習内容（例：PHP, 筋トレ）
            $table->integer('pomodoro_count'); // 完了したセット数（例：1）
            $table->integer('duration_minutes')->default(25); // 1セット何分か
            $table->text('memo')->nullable();  // ちょっとしたメモ
            $table->timestamps();              // 作成日時（いつやったか）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pomo_records');
    }
};
