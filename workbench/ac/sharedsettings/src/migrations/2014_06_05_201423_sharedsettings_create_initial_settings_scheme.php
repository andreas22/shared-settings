<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SharedsettingsCreateInitialSettingsScheme extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('code');
            $table->text('title');
            $table->longText('description');
            $table->longText('content');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->integer('created_by')->unsigned();
            $table->integer('modified_by')->unsigned();
            $table->softDeletes();
        });

        Schema::create('apiusers', function(Blueprint $table)
        {
            $table->increments('id');
            $table->text('description');
            $table->text('username');
            $table->text('secret');
            $table->text('callback_url');
            $table->text('address');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->integer('created_by')->unsigned();
            $table->integer('modified_by')->unsigned();
            $table->softDeletes();
        });

        Schema::create('apiuser_data', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('apiuser_id')->unsigned();
            $table->integer('data_id')->unsigned();
        });

        Schema::table('data', function($table)
        {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modified_by')->references('id')->on('users');
        });

        Schema::table('apiusers', function($table)
        {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modified_by')->references('id')->on('users');
        });

        Schema::table('apiuser_data', function($table)
        {
            $table->foreign('apiuser_id')->references('id')->on('apiusers');
            $table->foreign('data_id')->references('id')->on('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apiuser_data', function($table)
        {
            $table->dropForeign('apiuser_data_data_id_foreign');
            $table->dropForeign('apiuser_data_apiuser_id_foreign');
        });
        Schema::dropIfExists('apiuser_data');
        Schema::dropIfExists('data');
        Schema::dropIfExists('apiusers');
    }
}
