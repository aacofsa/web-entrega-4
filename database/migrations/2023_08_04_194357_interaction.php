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
        Schema::create('interaction', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->bigInteger('selected_dog');
            $table->bigInteger('giver_dog');
            $table->timestamps();
            $table->unique(array('selected_dog', 'giver_dog'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interaction');
    }
};
