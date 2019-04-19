<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_follow', function (Blueprint $table) {
            $table->increments('id'); //unsigned()かぶるのを防ぐ １と１のように同じ数字を作らない
            $table->integer('user_id')->unsigned()->index();
            $table->integer('follow_id')->unsigned()->index();
            $table->timestamps();

            // 外部キー設定　onDelete('cascade') 参照データが削除された時にどうするか　cascade同時に消える
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('follow_id')->references('id')->on('users')->onDelete('cascade');

            // user_idとfollow_idの組み合わせの重複を許さない　ユーザーIDとフォローした相手のID
            $table->unique(['user_id', 'follow_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_follow');
    }
}
