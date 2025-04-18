<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration {

	public function up()
	{
		Schema::create('answers', function(Blueprint $table) {
			$table->id();
			$table->string('answer');
			$table->integer('points');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('answers');
	}
}
