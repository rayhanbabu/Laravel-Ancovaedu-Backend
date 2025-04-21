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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id'); // Foreign Key
            $table->foreign('user_id')->references('id')->on('users');

            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('bangla_name');
            $table->string('english_name');
            $table->string('full_address')->nullable();
            $table->string('short_address')->nullable();
            $table->string('eiin')->unique();

            $table->integer('bangla_name_front_size')->nullable();
            $table->integer('english_name_front_size')->nullable();
            $table->integer('full_address_front_size')->nullable();
            $table->integer('short_address_front_size')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
