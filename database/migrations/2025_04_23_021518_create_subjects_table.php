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
        Schema::create('subjects', function (Blueprint $table) {
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


            $table->string('subject_name');
            $table->string('subject_code')->nullable();

            $table->string('input_lavel1')->nullable();
            $table->string('input_lavel2')->nullable();
            $table->string('input_lavel3')->nullable();

            $table->integer('input_number1')->default(0);
            $table->integer('input_number2')->default(0);
            $table->integer('input_number3')->default(0);
            $table->integer('total_number')->default(0);


          

            $table->integer('pass_number1')->default(0);
            $table->integer('pass_number2')->default(0);
            $table->integer('pass_number3')->default(0);
           
            $table->enum('subject_category', ['Fixed','Dynamic','Religion'])->default('Fixed');

            $table->foreignId('religion_id')->nullable()->constrained('religions')->onDelete('set null');

            $table->enum('subject_type', ['Single','Combined'])->default('Single');
            $table->foreignId('combined_subject_id')->nullable()->constrained('subjects')->onDelete('set null');

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
        Schema::dropIfExists('subjects');
    }
};
