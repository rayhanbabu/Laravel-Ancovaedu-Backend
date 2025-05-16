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
        Schema::create('enrolls', function (Blueprint $table) {
            $table->id();

            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');


            $table->unsignedBigInteger('user_id'); // Foreign Key
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('student_id'); // Foreign Key
            $table->foreign('student_id')->references('id')->on('students');

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

            $table->boolean('confirm_enroll_status')->default(true);
            $table->date('confirm_enroll_date')->nullable();

            $table->integer('roll')->default(0);

            $table->enum ('created_type', ['Student', 'Enroll'])->default('Enroll');

            $table->boolean('subject_create_status')->default(false);
            $table->foreignId('subject_created_by')->nullable()->constrained('users')->onDelete('set null');
           

            $table->foreignId('main_subject1')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('main_subject2')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('main_subject3')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('additional_subject')->nullable()->constrained('subjects')->onDelete('set null');


            $table->foreignId('confirm_enroll_by')->nullable()->constrained('users')->onDelete('set null');
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
        Schema::dropIfExists('enrolls');
    }
};
