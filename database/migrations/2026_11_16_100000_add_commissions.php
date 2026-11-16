<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Vendor commission rate
        Schema::table('vendors', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(5.00)->after('status');
        });

        // 2) Order commission fields
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(0)->after('discount');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_rate');
            $table->decimal('vendor_net', 10, 2)->default(0)->after('commission_amount');
        });

        // 3) Platform earnings log
        Schema::create('platform_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->restrictOnDelete();
            $table->decimal('order_total', 12, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('amount', 12, 2);
            $table->string('status', 20)->default('pending'); // pending | settled
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_earnings');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'commission_amount', 'vendor_net']);
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('commission_rate');
        });
    }
};