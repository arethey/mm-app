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
        Schema::create('menstruation_periods', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('menstruation_date')->nullable();
            $table->integer('menstruation_period_length')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menstruation_periods');
    }
};
