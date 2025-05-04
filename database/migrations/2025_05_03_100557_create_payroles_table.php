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
        Schema::create('payroles', function (Blueprint $table) {
            $table->id();

            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

            $table->unsignedBigInteger('employee_id'); // Foreign Key
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->unsignedBigInteger('payroleinfo_id'); // Foreign Key
            $table->foreign('payroleinfo_id')->references('id')->on('payroleinfos');

            $table->integer('basic_salary')->default(0);
            $table->integer('increment')->default(0);
            $table->integer('college_sallary')->default(0);
            $table->integer('city')->default(0);
            $table->integer('medical')->default(0);
            $table->integer('house_rent')->default(0);
            $table->integer('contributory')->default(0);
            $table->integer('incentive')->default(0);
            $table->integer('arrear')->default(0);
            $table->integer('other')->default(0);

            $table->integer('loan_refund')->default(0);
            $table->integer('attendance')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('gratuity_loan')->default(0);


            $table->integer('boishakhi')->default(0);
            $table->integer('adha')->default(0);
            $table->integer('fitr')->default(0);

            $table->integer('total_gross')->default(0);
            $table->integer('total_deduction')->default(0);
            $table->integer('total_net')->default(0);

            $table->boolean('salary_status')->default(0);
    
            $table->date('payrole_date');

            $table->integer('month');
            $table->integer('year');
        
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
        Schema::dropIfExists('payroles');
    }
};
