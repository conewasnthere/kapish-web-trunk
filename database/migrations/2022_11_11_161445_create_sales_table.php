<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
			$table->bigInteger("purchaser_id");
			$table->bigInteger("seller_id");
			$table->bigInteger("product_id");
			$table->bigInteger("quantity")->default(1);
			$table->bigInteger("purchase_price")->default(0);
			$table->bigInteger("discount")->default(0);
			$table->bigInteger("total_price")->default(0); // (quantity * purchase_price) - discount
			$table->bigInteger("marketplace_fee")->default(0); // purchase_rice * commission_rate
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
        Schema::dropIfExists('sales');
    }
}
