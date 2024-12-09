<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration {

	public function up()
	{
		Schema::create('answers', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('answer');
			$table->integer('points');
			$table->bigInteger('question_id');
		});
	}

	public function down()
	{
		Schema::drop('answers');
	}
}
