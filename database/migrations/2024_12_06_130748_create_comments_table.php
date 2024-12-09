<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration {

	public function up()
	{
		Schema::create('comments', function(Blueprint $table) {
			$table->increments('id');
			$table->text('body');
			$table->bigInteger('post_id')->unsigned();
			$table->bigInteger('user_id')->unsigned();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('comments');
	}
}
