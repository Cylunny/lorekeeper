<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CharAuthorization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add columns
        Schema::table('characters', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(0);
        });

        Schema::create('character_authorizations', function (Blueprint $table) { 
            $table->engine = 'InnoDB';         
            $table->bigIncrements('id');  
            $table->integer('character_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function (Blueprint $table) {
            //
            $table->dropColumn('is_hidden');
        });

        Schema::dropIfExists('character_authorizations');

    }
}
