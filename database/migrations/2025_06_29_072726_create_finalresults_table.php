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
        Schema::create('finalresults', function (Blueprint $table) {
             $table->id();

             $table->string('school_username'); // Foreign Key
             $table->foreign('school_username')->references('username')->on('users');

             $table->unsignedBigInteger('enroll_id'); // Foreign Key
             $table->foreign('enroll_id')->references('id')->on('enrolls');

             $table->string('finalresult_group');  // Description of the markinfo

             $table->unsignedBigInteger('exam_id'); // Foreign Key
             $table->foreign('exam_id')->references('id')->on('exams');

            $table->integer('total_subject')->default(0);
            $table->integer('total_attendance')->default(0);
            $table->integer('total_subject_passed')->default(0);  // Amount of the markinfo
            $table->integer('total_subject_failed')->default(0);  // Amount of the markinfo
            $table->integer('merit_position')->default(0);


            $table->double('total_mark', 8, 2)->default(0);  // Amount of the markinfo
            $table->double('gpa_total', 8, 2)->default(0);  // Amount of the markinfo
            $table->double('gpa', 8, 2)->default(0);  // Amount of the markinfo
            $table->string('grade')->nullable();  // Description of the markinfo
          


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finalresults');
    }
};
