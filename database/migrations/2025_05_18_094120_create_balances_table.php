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
        Schema::create('balances', function (Blueprint $table) {
             $table->id();

             $table->string('school_username'); // Foreign Key
             $table->foreign('school_username')->references('username')->on('users');

             $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

             $table->enum('category_type',['Debit','Credit']);

             $table->string('image')->nullable();

             $table->foreignId('previous_id')->nullable()->constrained('balances')->onDelete('set null');
             $table->integer('previous_balance')->default(0);

             $table->string('comment')->nullable();          
             $table->integer('amount');
             $table->integer('balance')->default(0);
             $table->boolean('status')->default(false);

             $table->date('date');

             $table->integer('month');
             $table->integer('year');
             $table->integer('day');

             $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
        

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
        Schema::dropIfExists('balances');
    }
};
