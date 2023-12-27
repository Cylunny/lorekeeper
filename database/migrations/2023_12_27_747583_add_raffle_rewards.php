<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRaffleRewards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('raffle_rewards', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('raffle_id')->unsigned()->default(0);
            $table->string('rewardable_type');
            $table->integer('rewardable_id')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->foreign('raffle_id')->references('id')->on('raffles');
        });

        Schema::table('raffles', function(Blueprint $table) {
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
            $table->boolean('has_join_button')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('raffle_rewards');

        Schema::table('raffles', function(Blueprint $table) {
            $table->dropcolumn('description');
            $table->dropcolumn('parsed_description');            
            $table->dropcolumn('has_join_button');
        });
    }
}
