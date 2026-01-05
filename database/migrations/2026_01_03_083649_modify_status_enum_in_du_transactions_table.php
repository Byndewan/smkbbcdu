<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE du_transactions MODIFY COLUMN status ENUM(
            'draft',
            'pending_docs',
            'doc_rejected',
            'payment_review',
            'payment_rejected',
            'paid',
            'expired'
        ) DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE du_transactions MODIFY COLUMN status ENUM(
            'draft',
            'pending',
            'paid',
            'rejected',
            'awaiting_payment'
        ) DEFAULT 'draft'");
    }
};
