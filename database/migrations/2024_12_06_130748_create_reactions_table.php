<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactionsTable extends Migration {

	public function up()
	{
		Schema::create('reactions', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('user_id')->unsigned();
			$table->enum('reactionable_type', array('post', 'comment'));
			$table->bigInteger('reactionable_id')->unsigned();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('reactions');
	}
}
