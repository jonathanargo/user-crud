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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 128)->nullable();
            $table->string('last_name', 128)->nullable();
            $table->string('email', 128);
            $table->string('mobile_number', 32)->nullable();
            $table->string('address', 128)->nullable();
            $table->string('city', 128)->nullable();
            $table->string('state', 2)->nullable();
            $table->integer('zip')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('timezone', 32)->nullable();
            $table->dateTime('created')->nullable()->useCurrent();
            $table->dateTime('last_updated')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
