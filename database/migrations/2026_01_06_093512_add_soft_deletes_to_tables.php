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
        Schema::table('core_majors', function (Blueprint $table) {
            if (!Schema::hasColumn('core_majors', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('core_school_years', function (Blueprint $table) {
            if (!Schema::hasColumn('core_school_years', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('core_classes', function (Blueprint $table) {
            if (!Schema::hasColumn('core_classes', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('core_students', function (Blueprint $table) {
            if (!Schema::hasColumn('core_students', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('du_bills', function (Blueprint $table) {
            if (!Schema::hasColumn('du_bills', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('du_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('du_transactions', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('core_majors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('core_school_years', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('core_classes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('core_students', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('du_bills', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('du_transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
