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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();

            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

            $table->unsignedBigInteger('sessionyear_id'); // Foreign Key
            $table->foreign('sessionyear_id')->references('id')->on('sessionyears');

            $table->unsignedBigInteger('programyear_id'); // Foreign Key
            $table->foreign('programyear_id')->references('id')->on('programyears');

            $table->unsignedBigInteger('level_id'); // Foreign Key
            $table->foreign('level_id')->references('id')->on('levels');

            $table->unsignedBigInteger('faculty_id'); // Foreign Key
            $table->foreign('faculty_id')->references('id')->on('faculties');

            $table->unsignedBigInteger('department_id'); // Foreign Key
            $table->foreign('department_id')->references('id')->on('departments');

            $table->unsignedBigInteger('section_id'); // Foreign Key
            $table->foreign('section_id')->references('id')->on('sections');

            $table->string('fee_type')->nullable();
            $table->integer('amount');
            $table->string('desc');

            $table->date('date')->nullable();
          

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
        Schema::dropIfExists('fees');
    }
};
