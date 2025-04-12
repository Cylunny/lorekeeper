<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactPrivacy extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('contact_authorizations', function (Blueprint $table) { 
            $table->engine = 'InnoDB';         
            $table->bigIncrements('id');  
            $table->integer('granted_by_user_id')->unsigned();
            $table->integer('granted_to_user_id')->unsigned();
        });

        Schema::table('user_settings', function (Blueprint $table) {
            $table->boolean('allow_contact')->default(true); //true = anyone can contact or comment/reply, false = only users granted auth can contact
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('contact_authorizations');

        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('allow_contact');
        });
    }
}
