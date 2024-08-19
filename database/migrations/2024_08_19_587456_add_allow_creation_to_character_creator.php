<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowCreationToCharacterCreator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('character_creators', function (Blueprint $table) {
            //
            $table->boolean('allow_character_creation')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('character_creators', function (Blueprint $table) {
            //
            $table->dropColumn('allow_character_creation');
        });
    }
}
