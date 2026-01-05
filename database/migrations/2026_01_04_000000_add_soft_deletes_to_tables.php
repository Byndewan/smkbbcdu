<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('core_students', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('du_bills', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('du_transactions', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('du_transaction_files', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('fin_banks', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('core_students', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('du_bills', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('du_transactions', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('du_transaction_files', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('fin_banks', function (Blueprint $table) { $table->dropSoftDeletes(); });
    }
};
