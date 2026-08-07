<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->renameColumn('images', 'attachments');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('images', 'attachments');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->renameColumn('attachments', 'images');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('attachments', 'images');
        });
    }
};
