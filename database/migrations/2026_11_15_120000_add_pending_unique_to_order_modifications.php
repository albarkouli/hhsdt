<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("CREATE UNIQUE INDEX IF NOT EXISTS order_modifications_one_pending_per_order ON order_modifications (order_id) WHERE status = 'pending'");
    }

    public function down(): void
    {
        DB::unprepared("DROP INDEX IF EXISTS order_modifications_one_pending_per_order");
    }
};