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
        Schema::create('students', function (Blueprint $table) {

            $table->id();
            $table->string('bangla_name');
            $table->string('english_name');
            $table->unsignedBigInteger('user_id'); // Foreign Key
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

            $table->unsignedBigInteger('religion_id'); // Foreign Key
            $table->foreign('religion_id')->references('id')->on('religions');

            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();

            $table->enum('gender',['Male','Female','Other'])->default('Male');
            $table->string('registration')->nullable();
            $table->date('dob')->nullable();

           

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
