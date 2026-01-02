<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class FileService
{
    /**
     * Upload file dengan resize otomatis
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;
        $mime = $file->getMimeType();
        if (str_starts_with($mime, 'image/')) {
            $image = Image::read($file);
            $image->scale(width: 1000);
            Storage::disk(config('filesystems.default'))->put($path, (string) $image->encode());
        } else {
            Storage::disk(config('filesystems.default'))->putFileAs($folder, $file, $filename);
        }

        return $path;
    }

    /**
     * Hapus file dari storage
     */
    public function delete(string $path): void
    {
        if (Storage::disk(config('filesystems.default'))->exists($path)) {
            Storage::disk(config('filesystems.default'))->delete($path);
        }
    }
}
