<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToAdoptionStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('adoption_stock', function(Blueprint $table) {
            $table->timestamps();
        });

        Schema::create('adoption_prices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('days')->unsigned(); // number of days or months
            $table->integer('adoption_id')->unsigned(); // id of the adopt center it belongs to, which should always be the same but alas
            $table->integer('currency_id')->unsigned(); // id of the currency the price changes to / or same if same currency
            $table->integer('amount')->unsigned(); // how much does it cost now, new price after x days
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //#
        Schema::table('adoption_stock', function(Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::dropIfExists('adoption_prices');

    }
}
