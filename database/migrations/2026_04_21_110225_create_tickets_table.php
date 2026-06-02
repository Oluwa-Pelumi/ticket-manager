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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');

            $table->string('order_type')->nullable();
            $table->string('recurrence_period')->nullable();
            $table->date('custom_recurrence_date')->nullable();
            $table->json('order_activations')->nullable();


            $table->foreignId('attended_to_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('subject');
            $table->text('content');
            $table->string('filename')->nullable();
            $table->json('images')->nullable();
            $table->string('priority')->default('medium');
            $table->string('status')->default('open');
            $table->timestamps();

            $table->foreignId('user_id')->nullable()->change();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
