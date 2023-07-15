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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->unique();
            $table->date('birthdate')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('menstruation_status')
                ->default(true) // true = user's menstruation is active and is currently not pregnant
                ->comment('1 = active and not pregnant, 0 = inactive and might be pregnant');
            $table->unsignedBigInteger('user_role_id')->default(2); // set 2 as default for user only role
            $table->boolean('is_active')->default(false); // true = user is active and can login, false = user is inactive and cannot login or need to be verified by admin
            $table->text('remarks')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('user_role_id')->references('id')->on('user_roles');
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
