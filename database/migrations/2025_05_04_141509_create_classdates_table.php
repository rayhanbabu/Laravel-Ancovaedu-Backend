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
        Schema::create('classdates', function (Blueprint $table) {
            $table->id();
            
            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

            $table->string('attendance_group'); 

            $table->unsignedBigInteger('enroll_id'); // Foreign Key
            $table->foreign('enroll_id')->references('id')->on('enrolls');

            $table->unsignedBigInteger('subject_id'); // Foreign Key
            $table->foreign('subject_id')->references('id')->on('subjects');

            $table->string('time')->nullable();
            $table->date('date');
          
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
        Schema::dropIfExists('classdates');
    }
};
