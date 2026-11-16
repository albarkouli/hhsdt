<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Expand status column to fit 'pending_customer_approval' (25 chars)
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 40)->change();
        });

        Schema::create('order_modifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proposed_by')->constrained('users')->restrictOnDelete();

            // JSON snapshots
            $table->json('original_items');
            $table->json('proposed_items');
            $table->decimal('original_total', 12, 2);
            $table->decimal('proposed_total', 12, 2);

            $table->text('note');

            $table->string('status', 20)->default('pending'); // pending, accepted, rejected

            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_modifications');
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });
    }
};