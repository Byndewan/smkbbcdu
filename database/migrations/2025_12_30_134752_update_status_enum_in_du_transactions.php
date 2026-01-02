<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE du_transactions MODIFY COLUMN status ENUM('draft', 'pending', 'awaiting_payment', 'rejected', 'paid') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("ALTER TABLE du_transactions MODIFY COLUMN status ENUM('draft', 'pending', 'rejected', 'paid') NOT NULL DEFAULT 'draft'");
    }
};
