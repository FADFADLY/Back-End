<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mood_entries', function (Blueprint $table) {
            $table->enum('model_prediction',
                [
                'none',
                'anger',
                'joy',
                'sadness',
                'love',
                'fear',
                'sympathy'
                ,'surprise'
            ]
            )->nullable()->after('mood');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mood_entries', function (Blueprint $table) {
            //
        });
    }
};
