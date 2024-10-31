<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalChecklistTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('species_approval_checklists', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('species_id')->unsigned()->index();
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
        });

        Schema::create('subtype_approval_checklists', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('subtype_id')->unsigned()->index();
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('species_approval_checklists');
        Schema::dropIfExists('subtype_approval_checklists');

    }
}
