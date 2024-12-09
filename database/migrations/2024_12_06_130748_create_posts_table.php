<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration {

	public function up()
	{
		Schema::create('posts', function(Blueprint $table) {
			$table->increments('id', true);
			$table->text('body');
			$table->bigInteger('user_id')->unsigned();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('posts');
	}
}
