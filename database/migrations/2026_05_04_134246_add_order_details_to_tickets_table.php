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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('order_type')->nullable()->after('category_id');
            $table->string('recurrence_period')->nullable()->after('order_type');
            $table->date('custom_recurrence_date')->nullable()->after('recurrence_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'recurrence_period', 'custom_recurrence_date']);
        });
    }
};
