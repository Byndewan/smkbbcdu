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
        Schema::create('du_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_code', 100)->unique();
            $table->foreignId('student_id')->constrained('core_students')->cascadeOnDelete();
            $table->foreignId('du_bill_id')->constrained('du_bills')->cascadeOnDelete();
            $table->enum('status', ['draft', 'pending', 'paid', 'verified', 'rejected'])->default('draft');
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_method')->nullable();
            $table->string('bank_sender')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
            $table->index(['student_id', 'du_bill_id']);
            $table->index('status');
        });

        Schema::create('du_transaction_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('du_transaction_id')->constrained('du_transactions')->cascadeOnDelete();
            $table->foreignId('du_bill_requirement_id')->constrained('du_bill_requirements')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('du_transactions');
    }
};
