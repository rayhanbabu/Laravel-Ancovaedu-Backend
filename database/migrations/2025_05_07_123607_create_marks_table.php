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

            $table->unsignedBigInteger('enroll_id'); // Foreign Key
            $table->foreign('enroll_id')->references('id')->on('enrolls');

            $table->unsignedBigInteger('exam_id'); // Foreign Key
            $table->foreign('exam_id')->references('id')->on('exams');

            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');

            $table->double('level1_mark', 8, 2)->default(0); // Amount of the markinfo
            $table->double('level2_mark', 8, 2)->default(0);; // Amount of the markinfo
            $table->double('level3_mark', 8, 2)->default(0);; // Amount of the markinfo
            $table->double('sub_total', 8, 2)->default(0);; // Amount of the markinfo
            $table->double('total', 8, 2)->default(0);; // Amount of the markinfo
            $table->double('gpa', 8, 2)->default(0);; // Amount of the markinfo
            $table->string('grade')->nullable(); // Description of the markinfo

            $table->boolean('attendance_status')->default(true);
            $table->foreignId('attendance_by')->nullable()->constrained('users')->onDelete('set null');

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
