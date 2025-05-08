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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

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

            $table->unsignedBigInteger('exam_id'); // Foreign Key
            $table->foreign('exam_id')->references('id')->on('exams');

            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');

            $table->decimal('level1_mark', 8, 2)->default(0); // Amount of the markinfo
            $table->decimal('level2_mark', 8, 2)->default(0);; // Amount of the markinfo
            $table->decimal('level3_mark', 8, 2)->default(0);; // Amount of the markinfo
            $table->decimal('total', 8, 2)->default(0);; // Amount of the markinfo
            $table->decimal('gpa', 8, 2)->default(0);; // Amount of the markinfo
            $table->string('grade')->nullable(); // Description of the markinfo


            $table->boolean('final_submit_status')->default(false);
            $table->foreignId('final_submited_by')->nullable()->constrained('users')->onDelete('set null');

            $table->boolean('check_status')->default(false);
            $table->foreignId('checked_by')->nullable()->constrained('users')->onDelete('set null');


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
        Schema::dropIfExists('marks');
    }
};
