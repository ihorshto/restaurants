<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    protected string $disk;
    protected int $maxFileSize;

    public function __construct(string $disk = 'public', int $maxFileSize = 20480)
    {
        $this->disk = $disk;
        $this->maxFileSize = $maxFileSize; // File size in KB
    }

    public function validateFile(Request $request, string $allowedMimes, string $fileName): void
    {
        $request->validate([
            $fileName => "required|mimes:{$allowedMimes}|max:{$this->maxFileSize}",
        ], [
            $fileName . '.mimes' => 'Le fichier doit être de type : ' . str_replace(',', ', ', $allowedMimes) . '.',
            $fileName . '.required' => 'Veuillez télécharger un fichier.',
            $fileName . '.max' => 'La taille du fichier ne doit pas dépasser ' . ($this->maxFileSize / 1024) . ' Mo.',
        ]);
    }

    public function uploadFile(Request $request, string $requestFileName, string $folderName): string
    {
        $file = $request->file($requestFileName);
        $originalName = $file->getClientOriginalName();
        $cleanedName = $this->sanitizeFileName($originalName);

        $filePath = Storage::disk($this->disk)->putFileAs(
            $folderName,
            $file,
            $cleanedName
        );

        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            if ($mimeType == 'image/svg+xml' || $mimeType == 'text/svg+xml') {
                // Optionally minify the SVG file
                $this->minifySvg($filePath);
            } else {
                // Compress the image with Imagick if it's a raster image
                $this->compressImageWithImagick($filePath);
            }
        }

        if (!$filePath) {
            throw new \Exception('Erreur lors de l\'enregistrement du fichier');
        }

        if ($requestFileName == 'upload') {
            // Generate the public URL for the stored file
            return Storage::url($filePath);
        }

        return $cleanedName;
    }

    public function saveFile(string $temporaryFileName, string $targetFolder): string
    {
        $userId = auth()->check() ? auth()->user()->id : '0';
        $tempFilePath = "uploads/uploads_{$userId}/{$temporaryFileName}";

        if (!Storage::disk($this->disk)->exists($tempFilePath)) {
            throw new Exception('Le fichier téléchargé n\'a pas été trouvé ou a été supprimé.');
        }

        $documentFilePath = Storage::disk('public')->putFileAs(
            $targetFolder,
            Storage::disk($this->disk)->path($tempFilePath),
            $temporaryFileName
        );

        if (!$documentFilePath) {
            throw new Exception('Erreur lors de l\'enregistrement du fichier');
        }
        // Remove temporary file
        Storage::disk($this->disk)->delete($tempFilePath);

        return $documentFilePath;
    }

    public function sanitizeFileName(string $fileName): string
    {
        $cleanedName = preg_replace('/[^a-zA-Z0-9-_\.]/u', '_', trim($fileName));
        $cleanedName = preg_replace('/_+/', '_', $cleanedName);
        return trim($cleanedName, '_');
    }
}
