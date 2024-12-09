<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration {

	public function up()
	{
		Schema::create('tests', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->text('description');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('tests');
	}
}
