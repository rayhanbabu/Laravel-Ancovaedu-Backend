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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('username')->unique();
            $table->string('alternative_phone')->nullable();
            $table->string('email')->unique();
            $table->string('secondary_email')->nullable();
            $table->string('zone_id')->nullable();
            $table->string('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('last_login_device')->nullable();
            $table->string('last_login_location')->nullable();
            $table->boolean('is_online')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_enabled')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();
            $table->integer('failed_login_attempts')->nullable();

            $table->string('otp_type')->nullable();
            $table->integer('opt_code')->nullable();
            $table->integer('opt_expires_at')->nullable();
            $table->string('language_preference')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
