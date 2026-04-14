<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->ID();
            $table->smallInteger('SHIFT_ID');
            $table->bigInteger('CONTACT_ID');
            $table->date('SCHED_DATE');
            $table->smallInteger('SCHED_STATUS');
            $table->dateTime('STATUS_LOG')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
