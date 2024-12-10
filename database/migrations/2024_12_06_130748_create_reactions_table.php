<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactionsTable extends Migration {

	public function up()
	{
		Schema::create('reactions', function(Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
            $table->morphs('reactable');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('reactions');
	}
}
