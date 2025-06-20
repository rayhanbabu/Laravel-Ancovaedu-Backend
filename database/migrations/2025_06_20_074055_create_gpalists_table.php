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
        Schema::create('gpalists', function (Blueprint $table) {
              $table->id();
              $table->string('session_year');
              $table->string('school_username'); // Foreign Key
              $table->foreign('school_username')->references('username')->on('users');

              $table->foreignId('gpa_category_id')->constrained('gpacategories')->onDelete('cascade');

              $table->boolean('status')->default(true);

              $table->integer('total_student');
              $table->integer('total_pass');
              $table->integer('total_fail');
              $table->decimal('pass_rate', 8, 2);
              $table->integer('gpa5')->nullable();
              $table->integer('gpa4')->nullable();
              $table->integer('gpa3')->nullable();
              $table->integer('gpa35')->nullable();
              $table->integer('gpa2')->nullable();
              $table->integer('gpa1')->nullable();
              $table->integer('gpa0')->nullable();


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
        Schema::dropIfExists('gpalists');
    }
};
