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
        // 1. TABLE BACKUP
        Schema::create('core_student_backups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable(); // ID Asli Siswa
            // Kolom Data Siswa
            $table->string('nipd')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('pob')->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('current_class_id')->nullable();
            $table->unsignedBigInteger('prev_class_id')->nullable();
            $table->unsignedBigInteger('major_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->boolean('is_active')->default(1);
            // Metadata Backup
            $table->string('trigger_action'); // 'create', 'update', 'import'
            $table->unsignedBigInteger('backup_by')->nullable(); // Siapa yang ngubah
            $table->timestamps(); // Kapan diubah
        });

        // 2. TABLE DELETED
        Schema::create('core_students_deleted', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_student_id'); // ID Asli sebelum dihapus
            // Kolom Data Siswa
            $table->string('nipd')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('pob')->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('current_class_id')->nullable();
            $table->unsignedBigInteger('prev_class_id')->nullable();
            $table->unsignedBigInteger('major_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();
            // Metadata Penghapusan
            $table->unsignedBigInteger('deleted_by')->nullable(); // Siapa ngehapus
            $table->string('deleter_name')->nullable(); // Nama user penghapus (jaga-jaga user dihapus)
            $table->text('deletion_reason'); // Alasan (Mandatory)
            $table->timestamp('deleted_at')->useCurrent(); // Kapan dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_students_deleted');
        Schema::dropIfExists('core_student_backups');
    }
};
