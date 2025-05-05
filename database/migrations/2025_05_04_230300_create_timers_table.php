<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimersTable extends Migration
{
    public function up()
    {
        Schema::create('timers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('related_type'); // task, habit, call, etc.
            $table->unsignedBigInteger('related_id');
            $table->enum('status', ['running', 'paused', 'stopped'])->default('paused');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('timers');
    }
}
