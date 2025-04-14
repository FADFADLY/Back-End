<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration {

	public function up()
	{
		Schema::create('questions', function(Blueprint $table) {
			$table->id();
			$table->string('question');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('questions');
	}
}
