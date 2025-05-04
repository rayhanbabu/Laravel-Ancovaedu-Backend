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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('bangla_name');
            $table->string('english_name');
            $table->unsignedBigInteger('user_id'); // Foreign Key
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

            $table->unsignedBigInteger('religion_id'); // Foreign Key
            $table->foreign('religion_id')->references('id')->on('religions');

            $table->unsignedBigInteger('designation_id'); // Foreign Key
            $table->foreign('designation_id')->references('id')->on('designations');

            $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('set null');
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');


            $table->enum('gender',['Male','Female','Other'])->default('Male');
            $table->enum('relationship',['Married','Unmarried'])->default('Unmarried');
            $table->enum('blood_group',['A+','A-','B+','B-','AB+','AB-','O+','O-'])->default('A+');

            $table->date('joining_date')->nullable();
            $table->string('index_number')->nullable();
            $table->string('tin_number')->nullable();
            $table->date('dob')->nullable();

            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();

            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('routing_number')->nullable();
           

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
        Schema::dropIfExists('employees');
    }
};
