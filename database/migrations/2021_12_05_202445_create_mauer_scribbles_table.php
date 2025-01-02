<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMauerScribblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mauer_scribbles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('body', 2000);
            $table->bigInteger('user_id');
            $table->boolean('anonymous')->default(true);
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
        Schema::dropIfExists('mauer_scribbles');
    }
}
