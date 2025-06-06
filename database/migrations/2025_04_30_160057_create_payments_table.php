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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('school_username'); // Foreign Key
            $table->foreign('school_username')->references('username')->on('users');

            $table->unsignedBigInteger('enroll_id'); // Foreign Key
            $table->foreign('enroll_id')->references('id')->on('enrolls');

            $table->enum('payment_type', ['cash','bank_transfer','online'])->default('cash');

            $table->string('payment_group');  

            $table->string('tran_id')->unique();

            $table->string('payment_method')->nullable();
            $table->integer('payment_status')->default(0);
            $table->string('payment_status_message')->nullable();
            $table->string('bank_tran_id')->nullable();

            $table->integer('amount');
            $table->double('gateway_charge', 10, 2)->default(0);
            $table->double('total_amount', 10, 2);

            $table->date('date')->nullable();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();

            $table->enum ('collection_type', ['Full','Partial']);

        
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
        Schema::dropIfExists('payments');
    }
};
