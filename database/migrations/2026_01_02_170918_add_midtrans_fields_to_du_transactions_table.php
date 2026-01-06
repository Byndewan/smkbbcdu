<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('du_transactions', function (Blueprint $table) {
            $table->string('midtrans_transaction_id')->nullable()->after('status');
            $table->string('payment_type')->default('manual')->after('midtrans_transaction_id');
            $table->string('va_number')->nullable()->after('payment_type');
            $table->dateTime('payment_expiry_time')->nullable()->after('va_number');
            $table->json('midtrans_response')->nullable()->after('payment_expiry_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('du_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_transaction_id',
                'payment_type',
                'va_number',
                'payment_expiry_time',
                'midtrans_response',
            ]);
        });
    }
};
