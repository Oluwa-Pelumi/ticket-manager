<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Read existing data
        $existing = DB::table('tickets')
            ->select('id', 'attended_to_by')
            ->whereNotNull('attended_to_by')
            ->get();

        // 2. Drop the foreign key and old column
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['attended_to_by']);
            $table->dropColumn('attended_to_by');
        });

        // 3. Re-create the column as JSON
        Schema::table('tickets', function (Blueprint $table) {
            $table->json('attended_to_by')->nullable()->after('category_id');
        });

        // 4. Restore the data, wrapping single IDs into arrays
        foreach ($existing as $row) {
            DB::table('tickets')
                ->where('id', $row->id)
                ->update([
                    'attended_to_by' => json_encode([(int) $row->attended_to_by])
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Read json data
        $existing = DB::table('tickets')
            ->select('id', 'attended_to_by')
            ->whereNotNull('attended_to_by')
            ->get();

        // 2. Drop JSON column
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('attended_to_by');
        });

        // 3. Re-create foreignId column
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('attended_to_by')->nullable()->after('category_id')->constrained('users')->onDelete('set null');
        });

        // 4. Restore the first element of the array
        foreach ($existing as $row) {
            $array = json_decode($row->attended_to_by, true);
            if (is_array($array) && !empty($array)) {
                $first = (int) reset($array);
                // Check if user still exists to avoid foreign key failure
                if (DB::table('users')->where('id', $first)->exists()) {
                    DB::table('tickets')
                        ->where('id', $row->id)
                        ->update([
                            'attended_to_by' => $first
                        ]);
                }
            }
        }
    }
};
