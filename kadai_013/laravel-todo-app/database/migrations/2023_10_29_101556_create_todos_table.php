<!-- 
    todo用テーブルを作成するためのマイグレーションのファイル。Todoモデル作成と同時に生成される 
     コマンドで「php artisan migrate」と命令すると、このファイルが実行される（マイグレーションされる）
-->
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();  // 　←外部キーを設定したので、モデルファイルにリレーションシップの設定を想起せよ
            $table->foreignId('goal_id')->constrained()->cascadeOnDelete();  // 　←外部キーを設定したので、モデルファイルにリレーションシップの設定を想起せよ
            $table->boolean('done')->default(false);    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
};
