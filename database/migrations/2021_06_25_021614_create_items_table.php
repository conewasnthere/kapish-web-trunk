<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->default('New Asset');
            $table->string('description', 2000)->default('No description.');
            $table->string('thumbnail_url')->nullable();
            $table->bigInteger('creator')->default(1);
            $table->bigInteger('price')->default(0);
			$table->bigInteger('original_price')->default(0);
			$table->bigInteger('stock_circulating')->default(0);
			$table->bigInteger('private_sellers')->default(0);
			$table->bigInteger('rap')->default(0);
            $table->boolean('onsale')->default(true);
            $table->boolean('approved')->default(0);
			$table->boolean('is_limited')->default(0);
			$table->boolean('is_limitedu')->default(0);
			$table->boolean('is_boosters_only')->default(0);
            $table->boolean('new_signature')->default(false);
            $table->string('type');
            $table->string('hatchname', 100)->nullable();
            $table->string('hatchdesc', 2000)->nullable();
            $table->string('hatchtype')->nullable();
            $table->timestamp('hatchdate')->nullable();
            $table->bigInteger('sales')->default(0);
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
        Schema::dropIfExists('items');
    }
}
