<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add temporary uuid column
        Schema::table('tickets', function (Blueprint $table) {
            $table->uuid('uuid_id')->nullable()->after('id');
        });

        // 2. Populate uuid_id with random UUIDs
        $tickets = DB::table('tickets')->get();
        foreach ($tickets as $ticket) {
            DB::table('tickets')->where('id', $ticket->id)->update([
                'uuid_id' => (string) Str::uuid(),
            ]);
        }

        // 3. Drop primary key and old id column
        // Note: SQLite might have trouble with dropping primary keys this way, 
        // but for MySQL/PostgreSQL it works.
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // 4. Rename uuid_id to id and make primary
        Schema::table('tickets', function (Blueprint $table) {
            $table->renameColumn('uuid_id', 'id');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropPrimary(['id']);
            $table->bigIncrements('old_id')->first();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->renameColumn('old_id', 'id');
        });
    }
};
