<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpeciesToAdoptionPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('adoption_prices', function(Blueprint $table) {
            $table->integer('species_id')->nullable()->unsigned(); // id of a species if the price update should only affect certain species
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
        Schema::table('adoption_prices', function(Blueprint $table) {
            $table->dropColumn('species_id');
        });

    }
}
