<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterCreatorTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_creators', function (Blueprint $table) {
            // 'name', 'description', 'parsed_description', 'cost', 'item_id', 'currency_id', 'is_visible', 'image_extension'
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
            $table->integer('cost')->unsigned()->default(0);
            $table->integer('item_id')->unsigned()->nullable();
            $table->integer('currency_id')->unsigned()->nullable();
            $table->boolean('is_visible')->default(0);
            $table->string('image_extension', 191)->nullable()->default(null); 
            $table->timestamps();
        });

        Schema::create('character_creator_layer_group', function (Blueprint $table) {
            // 'name', 'description', 'parsed_description', 'sort', 'character_creator_id'
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
            $table->integer('sort')->unsigned()->default(0); 
            $table->integer('character_creator_id')->unsigned();
        });

        Schema::create('character_creator_layer_option', function (Blueprint $table) {
            // 'name', 'description', 'parsed_description', 'sort', 'layer_group_id'
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
            $table->integer('sort')->unsigned()->default(0); 
            $table->integer('layer_group_id')->unsigned();
        });

        Schema::create('character_creator_layer', function (Blueprint $table) {
            // 'name', 'sort', 'layer_option_id', 'image_extension'
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('sort')->unsigned()->default(0); 
            $table->integer('layer_option_id')->unsigned();
            $table->string('image_extension', 191)->nullable()->default(null); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_creators');
        Schema::dropIfExists('character_creator_layer_group');
        Schema::dropIfExists('character_creator_layer_option');
        Schema::dropIfExists('character_creator_layer');
    }
}
