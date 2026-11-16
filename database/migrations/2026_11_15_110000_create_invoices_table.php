<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('vendor_id')->constrained()->restrictOnDelete();

            $table->string('invoice_number', 40)->unique();
            $table->unsignedBigInteger('sequence_number');
            $table->string('status', 20)->default('issued'); // issued, cancelled
            $table->unsignedInteger('version')->default(1);

            $table->json('snapshot');

            $table->timestamp('issued_at');
            $table->timestamps();

            $table->index(['vendor_id', 'issued_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};