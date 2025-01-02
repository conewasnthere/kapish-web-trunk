<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('servers');

        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name', 40)->default('My Server');
            $table->string('description', 250)->default('No description.');
            $table->bigInteger('creator');
            $table->bigInteger('visits')->default(0);
            $table->string('ip');
            $table->string('loopback_ip')->nullable();
            $table->string('port');
            $table->string('version');
            $table->boolean('friends_only')->default(false);
            $table->bigInteger('maxplayers')->default(12);
            $table->biginteger('chat_type')->default(2);
            $table->string('secret');
            $table->boolean('unlisted')->default(false);
            $table->boolean('allow_guests')->default(false);
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
        Schema::dropIfExists('servers');
    }
}
