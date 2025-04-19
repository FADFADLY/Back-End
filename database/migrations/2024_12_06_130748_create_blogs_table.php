<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration {

	public function up()
	{
		Schema::create('blogs', function(Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->text('body');
			$table->string('image');
            $table->string('author');
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('share_count')->default(0);
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('blogs');
	}
}
