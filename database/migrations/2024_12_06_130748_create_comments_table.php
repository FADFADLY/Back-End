<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration {

	public function up()
	{
		Schema::create('comments', function(Blueprint $table) {
			$table->id();
			$table->text('body');
			$table->unsignedBigInteger('post_id');
			$table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('comments');
	}
}
