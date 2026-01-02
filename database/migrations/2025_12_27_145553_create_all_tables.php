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
        // ==========================================
        // MODULE: CORE
        // ==========================================
        Schema::create('core_majors', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('abbreviation', 10)->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('core_school_years', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 20);
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('core_classes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 50)->index();
            $table->foreignId('major_id')->constrained('core_majors')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('core_students', function (Blueprint $table) {
            $table->id();
            $table->string('nipd', 20)->unique();
            $table->string('name')->index();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo_path')->nullable();
            $table->foreignId('current_class_id')->nullable()->constrained('core_classes')->nullOnDelete();
            $table->foreignId('prev_class_id')->nullable()->constrained('core_classes')->nullOnDelete();
            $table->foreignId('major_id')->nullable()->constrained('core_majors')->nullOnDelete();
            $table->foreignId('school_year_id')->nullable()->constrained('core_school_years')->nullOnDelete();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_graduated')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->index(['major_id', 'school_year_id', 'is_active'], 'idx_std_main');
        });

        // ==========================================
        // MODULE: FINANCE
        // ==========================================
        Schema::create('fin_banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 50);
            $table->string('account_number', 50);
            $table->string('account_holder', 100);
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ==========================================
        // MODULE: DAFTAR ULANG
        // ==========================================
        Schema::create('du_bills', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->foreignId('target_major_id')->nullable()->constrained('core_majors');
            $table->foreignId('target_school_year_id')->constrained('core_school_years');
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('du_bill_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('du_bill_id')->constrained('du_bills')->cascadeOnDelete();
            $table->string('document_name');
            $table->boolean('is_mandatory')->default(true);
            $table->string('file_type')->default('image');
            $table->integer('max_size_mb')->default(2);
            $table->timestamps();
        });

        Schema::create('du_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('trx_code')->unique();
            $table->foreignId('student_id')->constrained('core_students')->restrictOnDelete();
            $table->foreignId('du_bill_id')->constrained('du_bills')->restrictOnDelete();
            $table->enum('status', ['draft', 'pending', 'verified', 'rejected', 'expired'])->default('draft')->index();
            $table->decimal('amount_to_pay', 15, 2);
            $table->string('payment_method', 50)->nullable(); // MANUAL atau BNI_VA

            // === KOLOM KHUSUS TF MANUAL ===
            $table->foreignId('bank_id')->nullable()->constrained('fin_banks');
            $table->string('sender_bank_name')->nullable();
            $table->string('sender_account_name')->nullable();
            $table->string('sender_account_number')->nullable();
            $table->date('transfer_date')->nullable();
            $table->time('transfer_time')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('transfer_proof_path')->nullable();
            $table->text('user_note')->nullable();

            // === KOLOM KHUSUS TF OTOMATIS (BNI) ===
            $table->string('va_number', 30)->nullable();
            $table->string('vendor_trx_id')->nullable();
            $table->json('payment_payload')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');

            $table->timestamps();
        });

        Schema::create('du_transaction_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('du_transaction_id')->constrained('du_transactions')->cascadeOnDelete();
            $table->foreignId('du_bill_requirement_id')->constrained('du_bill_requirements');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->integer('size_kb')->nullable();

            $table->timestamps();
        });

        // ==========================================
        // MODULE: CMS
        // ==========================================
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('cms_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cms_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('image_path')->nullable();
            $table->string('type')->default('feature');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_features');
        Schema::dropIfExists('cms_faqs');
        Schema::dropIfExists('cms_settings');
        Schema::dropIfExists('du_transaction_files');
        Schema::dropIfExists('du_transactions');
        Schema::dropIfExists('du_bill_requirements');
        Schema::dropIfExists('du_bills');
        Schema::dropIfExists('fin_banks');
        Schema::dropIfExists('core_students');
        Schema::dropIfExists('core_classes');
        Schema::dropIfExists('core_school_years');
        Schema::dropIfExists('core_majors');
    }
};
