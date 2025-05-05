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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

            $table->unsignedBigInteger('classdate_id'); // Foreign Key
            $table->foreign('classdate_id')->references('id')->on('classdates');

            $table->unsignedBigInteger('student_id'); // Foreign Key
            $table->foreign('student_id')->references('id')->on('students');

            $table->string('status')->default(0); // present, absent, late, etc.

            $table->string('remark')->nullable(); // Additional remarks

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
        Schema::dropIfExists('attendances');
    }
};
