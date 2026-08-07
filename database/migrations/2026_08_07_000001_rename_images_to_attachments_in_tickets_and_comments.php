<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tickets', 'images')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->renameColumn('images', 'attachments');
            });
        }

        if (Schema::hasColumn('comments', 'images')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('images', 'attachments');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tickets', 'attachments')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->renameColumn('attachments', 'images');
            });
        }

        if (Schema::hasColumn('comments', 'attachments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('attachments', 'images');
            });
        }
    }
};
