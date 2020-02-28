<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->comment('用户名');
            $table->string('name')->comment('姓名')->nullable();
            $table->string('phone')->comment('手机号')->nullable();
            $table->string('password')->comment('密码')->nullable();
            $table->integer('status')->comment('状态')->nullable();
            $table->string('avatar')->comment('头像')->nullable();
            $table->integer('parent_id')->comment('推荐人');
            $table->integer('user_level_id')->comment('等级');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
