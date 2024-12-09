<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('questions', function(Blueprint $table) {
            $table->foreign('test_id')->references('id')->on('tests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('answers', function(Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('reactions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('reactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['test_id']);
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
        });
    }
};
