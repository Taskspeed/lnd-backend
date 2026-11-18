<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EmployeeFileServices
{
 protected string $disk = 'local';

    /**
     * Upload employee file
     */
    public function upload(
        string $controlNo,
        int $scheduleId,
        UploadedFile $file,
        string $type
    ): string {

        $allowedTypes = ['documents', 'forms'];

        if (!in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException('Invalid file type.');
        }

        $path = "employee-submissions/{$controlNo}/schedule_{$scheduleId}/{$type}";

        return $file->store($path, $this->disk);
    }

    /**
     * Delete employee file
     */
    public function delete(string $filePath): bool
    {
        return Storage::disk($this->disk)->delete($filePath);
    }

    /**
     * Check if file exists
     */
    public function exists(string $filePath): bool
    {
        return Storage::disk($this->disk)->exists($filePath);
    }

    /**
     * Get file URL (for public disk only)
     */
    public function getUrl(string $filePath): string
    {
        return Storage::disk('public')->url($filePath);
    }
}
