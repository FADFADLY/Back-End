<?php

use App\Enums\AttachmentTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration {

	public function up()
	{
		Schema::create('posts', function(Blueprint $table) {
			$table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->string('attachment')->nullable();
            $table->TinyInteger('type')->default(AttachmentTypeEnum::TEXT->value);
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('posts');
	}
}
