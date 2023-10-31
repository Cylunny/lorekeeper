<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCharacterTitleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('character_titles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('title');
            $table->string('short_title');
            $table->integer('sort')->default(0)->index();
            $table->integer('rarity_id')->nullable()->default(null)->index();

            $table->boolean('has_image')->default(0);
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
        });

        Schema::table('character_images', function (Blueprint $table) {
            $table->integer('title_id')->nullable()->default(null)->index();
            $table->string('title_data')->nullable()->default(null);
        });

        Schema::table('design_updates', function (Blueprint $table) {
            $table->integer('title_id')->nullable()->default(null)->index();
            $table->string('title_data')->nullable()->default(null);
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
        Schema::dropIfExists('character_titles');

        Schema::table('character_images', function (Blueprint $table) {
            $table->dropIndex(['title_id']);
            $table->dropColumn('title_id');
            $table->dropColumn('title_data');
        });

        Schema::table('design_updates', function (Blueprint $table) {
            $table->dropIndex(['title_id']);
            $table->dropColumn('title_id');
            $table->dropColumn('title_data');
        });
    }
}
