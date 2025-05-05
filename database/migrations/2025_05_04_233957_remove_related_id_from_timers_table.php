<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('timers', function (Blueprint $table) {
            $table->dropColumn('related_id');
        });
    }

    public function down()
    {
        Schema::table('timers', function (Blueprint $table) {
            $table->unsignedBigInteger('related_id')->nullable();
        });
    }
};
