<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('register_ip');
            $table->string('last_ip');
            $table->string('blurb', 2000)->nullable();
            $table->boolean('admin')->default(false);
            $table->boolean('verified_hoster')->default(false);
            $table->boolean('scribbler')->default(false);
            $table->boolean('booster')->default(false);
            $table->boolean('old_cores')->default(false);
            $table->bigInteger('money')->default(0);
            $table->string('invite_key')->nullable();
            $table->string('discord_id')->nullable();
            $table->rememberToken();
            $table->datetime('joined')->useCurrent();
            $table->datetime('last_online')->useCurrent();
            $table->datetime('last_daily_reward')->nullable();
            $table->json('added_servers');
            $table->boolean('qa')->default(false);
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
