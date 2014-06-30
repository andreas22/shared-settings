<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedPushNotifications extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('notifications', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('data_id')->unsigned();
            $table->tinyInteger('sent')->default(0);
            $table->dateTime('created_at');
            $table->integer('created_by')->unsigned();
            $table->text('recipients');
            $table->dateTime('updated_at');
            $table->integer('modified_by')->unsigned();
        });

        Schema::table('notifications', function($table)
        {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('data_id')->references('id')->on('data');
            $table->foreign('modified_by')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('notifications', function($table)
        {
            $table->dropForeign('notifications_data_id_foreign');
            $table->dropForeign('notifications_created_by_foreign');
            $table->dropForeign('notifications_modified_by_foreign');
        });
        Schema::dropIfExists('notifications');
	}

}
