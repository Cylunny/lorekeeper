<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTitles extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_titles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('title_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });

        Schema::table('character_titles', function(Blueprint $table) {
            $table->boolean('is_user_selectable')->default(0);
            $table->boolean('is_active')->default(0);
            $table->integer('item_id')->unsigned()->nullable()->default(null);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_titles');

        Schema::table('character_titles', function(Blueprint $table) {
            $table->dropcolumn('is_user_selectable');
            $table->dropcolumn('is_active');
            $table->dropcolumn('item_id');
        });

    }
}
