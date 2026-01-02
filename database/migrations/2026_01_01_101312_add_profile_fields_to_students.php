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
        Schema::table('core_students', function (Blueprint $table) {
            $table->text('address')->nullable()->after('pob');
            $table->boolean('is_profile_completed')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('core_students', function (Blueprint $table) {
            $table->dropColumn(['address', 'is_profile_completed']);
        });
    }
};
