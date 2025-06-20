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
        Schema::create('pages', function (Blueprint $table) {
              $table->id();
              $table->string('school_username'); // Foreign Key
              $table->foreign('school_username')->references('username')->on('users');

              $table->foreignId('page_category_id')->constrained('pagecategories')->onDelete('cascade');

              $table->boolean('status')->default(true);
              $table->string('title');
              $table->date('date');
              $table->text('content')->nullable();
              $table->string('link')->nullable();
              $table->string('image')->nullable();
              $table->integer('serial')->default(0);

              $table->string('name')->nullable(); 
              $table->string('phone')->nullable();  
              $table->string('email')->nullable();
              $table->string('designation')->nullable(); 



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
        Schema::dropIfExists('pages');
    }
};
