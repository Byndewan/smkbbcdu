<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Student;

/**
 * Service untuk menangani Upload File Terpusat
 * @author Muhammad Abyan
 */
class FileService
{
    /**
     * Upload File Umum (Tanpa konteks siswa)
     * Abyan - Digunakan untuk upload aset website kayak logo, banner, dll.
     * Abyan - Default masuk ke folder 'uploads' di storage public.
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        // Abyan - Generate nama file random pake UUID biar gak bentrok
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $this->processUpload($file, $folder, $filename);
    }

    /**
     * Upload Khusus Siswa (Dengan Struktur Folder Rapi)
     * * @param UploadedFile $file File yang diupload
     * @param Student $student Data Model Siswa (Relasi ke Kelas & Tahun Ajaran)
     * @param string $category Nama FOLDER penyimpanan (Contoh: 'Dokumen', 'profile', 'transactions')
     * @param string|null $customFilename Nama FILE khusus (Opsional). Kalo null, pake nama category.
     */
    public function uploadStudentFile(UploadedFile $file, Student $student, string $category = 'others', ?string $customFilename = null): string
    {
        // Abyan - Tentuin mau disimpan di folder mana (misal: .../Dokumen)
        $path = $this->generateStudentPath($student, $category);

        // Abyan - Tentuin nama awal file
        // Abyan - Kalo ada request nama khusus (misal: 'Kartu Keluarga'), pake itu.
        // Abyan - Kalo gak ada, pake nama foldernya (misal: 'Dokumen').
        // Abyan - Pake Str::slug biar aman dari spasi (jadi 'kartu-keluarga').
        $prefixName = $customFilename ? Str::slug($customFilename) : $category;

        // Abyan - Rakit nama file final + Timestamp biar unik
        // Hasil: kartu-keluarga-1728123.jpg
        $filename = $prefixName . '-' . time() . '.' . $file->getClientOriginalExtension();

        return $this->processUpload($file, $path, $filename);
    }

    /**
     * Logic Inti Proses Upload & Resize Gambar
     */
    private function processUpload(UploadedFile $file, string $folder, string $filename): string
    {
        $path = $folder . '/' . $filename;
        $mime = $file->getMimeType();

        // Abyan - Cek apakah file ini gambar?
        if (str_starts_with($mime, 'image/')) {
            // Abyan - Kalo Gambar: Resize dulu biar hemat storage server
            $image = Image::read($file);
            $image->scale(width: 1000); // Abyan - Max lebar 1000px, tinggi menyesuaikan

            // Abyan - Simpen hasil encode ke disk 'public'
            Storage::disk('public')->put($path, (string) $image->encode());
        } else {
            // Abyan - Kalo File Biasa (PDF/Doc): Langsung simpen raw file-nya
            Storage::disk('public')->putFileAs($folder, $file, $filename);
        }

        return $path;
    }

    /**
     * Helper: Generator Struktur Folder Siswa
     * Abyan - Ini otak dari struktur folder biar jadi rapi
     */
    private function generateStudentPath(Student $student, string $category)
    {
        // Abyan - Acuan Data: Model/table Student (kolom: name, nipd)
        // Hasil: budi-santoso-12345
        $studentFolder = Str::slug($student->name) . '-' . $student->nipd;

        // Abyan - Acuan Relasi: Student -> belongsTo SchoolYear (kolom: name)
        // Hasil: 2025-2026 (atau 'no-year' kalo null)
        $yearFolder = $student->schoolYear ? Str::slug($student->schoolYear->name) : 'no-year';

        // Abyan - Acuan Relasi: Student -> belongsTo Class (kolom: name)
        // Hasil: xii-rpl-1 (atau 'no-class' kalo null)
        $classFolder = $student->class ? Str::slug($student->class->name) : 'no-class';

        // Abyan - Return Path Lengkap.
        // Abyan - PENTING: Depannya WAJIB pake 'uploads/' biar kebaca sama Laravel File Manager (LFM)
        return "uploads/siswa/{$studentFolder}/{$yearFolder}/{$classFolder}/{$category}";
    }

    /**
    * Helper: Hapus File dari Storage
    */
    public function delete(string $path): void
    {
        // Abyan - Cek dulu file-nya ada gak di disk 'public' biar gak error
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
