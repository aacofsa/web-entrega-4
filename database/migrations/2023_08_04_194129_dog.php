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
        Schema::create('dog', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('breed')->nullable();
            $table->string('photo')->default("https://static.vecteezy.com/system/resources/previews/006/043/051/original/black-dog-silhouette-free-vector.jpg");
            $table->enum('gender', ["Male", "Female", "Not Defined"])->default('Not Defined');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dog');
    }
};
