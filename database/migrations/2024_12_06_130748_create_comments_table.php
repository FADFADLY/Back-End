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
			$table->unsignedBigInteger('post_id')->unsigned();
			$table->unsignedBigInteger('user_id')->unsigned();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('comments');
	}
}
