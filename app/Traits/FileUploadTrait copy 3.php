<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    public function uploadFile(UploadedFile $file, string $folder): array
    {
        try {

            $this->isFileSafe($file);

            $this->ensureFolderPermissions(storage_path("app/public/{$folder}"));

            // SAFE EXTENSION FROM REAL MIME
            $safeExtension = $this->extensionFromMime(
                $this->detectRealMime($file->getRealPath())
            );

            $fileName = Str::random(30) . '.' . $safeExtension;

            $filePath = $file->storeAs($folder, $fileName, 'public');

            return [
                'status' => true,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $this->detectRealMime($file->getRealPath()),
                'size' => $file->getSize(),
            ];

        } catch (Exception $e) {

            Log::error('File upload failed', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function ensureFolderPermissions(string $folderPath): void
    {
        if (!File::exists($folderPath)) {

            File::makeDirectory($folderPath, 0755, true, true);

        } else {

            @chmod($folderPath, 0755);
        }
    }

    protected function isFileSafe(UploadedFile $file): bool
    {
        $path = $file->getRealPath();

        if (!$path || !file_exists($path)) {
            throw new Exception('Uploaded file not found.');
        }

        $originalName = $file->getClientOriginalName();

        $extension = strtolower($file->getClientOriginalExtension());

        $mime = $this->detectRealMime($path);

        // ============================================================
        // 1. BLOCK DANGEROUS MIME TYPES
        // ============================================================

        $blockedMimePatterns = [
            'text/html',
            'application/javascript',
            'text/javascript',
            'application/x-httpd-php',
            'application/x-php',
            'text/xml',
            'application/xml',
            'image/svg+xml',
        ];

        foreach ($blockedMimePatterns as $blockedMime) {

            if (stripos($mime, $blockedMime) !== false) {

                throw new Exception(
                    "Security Error: Dangerous MIME type detected ({$mime})"
                );
            }
        }

        // ============================================================
        // 2. FILENAME SECURITY
        // ============================================================

        if (
            strpos($originalName, "\0") !== false ||
            strpos(urldecode($originalName), "\0") !== false ||
            preg_match('/\.{2,}/', $originalName) ||
            substr_count($originalName, '.') > 1 ||
            preg_match('/[<>:"\/\\\\|?*\x00-\x1F]/', $originalName) ||
            strlen($originalName) > 250
        ) {

            throw new Exception(
                "Security Error: Malicious filename detected"
            );
        }

        // ============================================================
        // 3. ALLOWED EXTENSIONS + MIME
        // ============================================================

        $allowedMime = [

            // IMAGES
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],

            // PDF
            'pdf' => ['application/pdf'],

            // DOC
            'doc' => [
                'application/msword',
                'application/vnd.ms-office',
            ],

            // DOCX
            'docx' => [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],

            // XLS
            'xls' => [
                'application/vnd.ms-excel',
                'application/msexcel',
                'application/x-msexcel',
                'application/x-ms-excel',
                'application/x-excel',
                'application/x-dos_ms_excel',
                'application/xls',
            ],

            // XLSX
            'xlsx' => [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],

            // MP4
            'mp4' => [
                'video/mp4',
            ],
        ];

        if (!isset($allowedMime[$extension])) {

            throw new Exception(
                "Security Error: Extension not allowed ({$extension})"
            );
        }

        if (!in_array($mime, $allowedMime[$extension], true)) {

            throw new Exception(
                "Security Error: MIME mismatch. Extension={$extension}, MIME={$mime}"
            );
        }

        // ============================================================
        // 4. MAGIC BYTES VALIDATION
        // ============================================================

        $handle = fopen($path, 'rb');

        $header = fread($handle, 20);

        fclose($handle);

        $magicMap = [

            'jpg' => ["\xFF\xD8\xFF"],
            'jpeg' => ["\xFF\xD8\xFF"],

            'png' => ["\x89PNG\r\n\x1a\n"],

            'gif' => ["GIF87a", "GIF89a"],

            'pdf' => ['%PDF-'],

            'doc' => ["\xD0\xCF\x11\xE0"],

            'docx' => ["\x50\x4B\x03\x04"],

            'xls' => ["\xD0\xCF\x11\xE0"],

            'xlsx' => ["\x50\x4B\x03\x04"],

            'mp4' => ['ftyp'],
        ];

        if ($extension === 'webp') {

            if (
                !(
                    substr($header, 0, 4) === 'RIFF' &&
                    substr($header, 8, 4) === 'WEBP'
                )
            ) {

                throw new Exception(
                    'Security Error: Invalid WEBP file'
                );
            }

        } else {

            $matched = false;

            foreach ($magicMap[$extension] as $magic) {

                if ($extension === 'mp4') {

                    if (strpos($header, $magic) !== false) {

                        $matched = true;
                        break;
                    }

                } else {

                    if (strncmp($header, $magic, strlen($magic)) === 0) {

                        $matched = true;
                        break;
                    }
                }
            }

            if (!$matched) {

                throw new Exception(
                    "Security Error: Magic bytes mismatch"
                );
            }
        }

        // ============================================================
        // 5. READ FILE CONTENT
        // ============================================================

        $content = file_get_contents(
            $path,
            false,
            null,
            0,
            20 * 1024 * 1024
        );

        if ($content === false) {

            throw new Exception(
                'Security Error: Unable to scan file'
            );
        }

        // ============================================================
        // 6. GLOBAL MALICIOUS PAYLOAD SCAN
        // ============================================================

        $dangerousPatterns = [

            '/<script\b/i',
            '/<\/script>/i',

            '/<iframe/i',
            '/<\/iframe>/i',

            '/<svg/i',

            '/javascript:/i',

            '/vbscript:/i',

            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',

            '/<\?php/i',
            '/<\?=/i',

            '/base64_decode\s*\(/i',

            '/eval\s*\(/i',

            '/document\.cookie/i',

            '/window\.location/i',

            '/alert\s*\(/i',

            '/data:text\/html/i',

            '/<!DOCTYPE/i',

            '/<\?xml/i',

            '/burpcollaborator/i',
        ];

        foreach ($dangerousPatterns as $pattern) {

            if (preg_match($pattern, $content)) {

                throw new Exception(
                    "Security Error: Malicious payload detected"
                );
            }
        }

        // ============================================================
        // 7. PDF VALIDATION
        // ============================================================

        if ($extension === 'pdf') {

            $this->validatePdf($content, $file);
        }

        // ============================================================
        // 8. OFFICE MACRO DETECTION
        // ============================================================

        if (in_array($extension, ['docx', 'xlsx'], true)) {

            $zip = new \ZipArchive();

            if ($zip->open($path) === true) {

                $hasMacro =
                    $zip->locateName('word/vbaProject.bin') !== false ||
                    $zip->locateName('xl/vbaProject.bin') !== false;

                $zip->close();

                if ($hasMacro) {

                    throw new Exception(
                        'Security Error: Office macro detected'
                    );
                }
            }
        }

        return true;
    }

    // ================================================================
    // PDF VALIDATION
    // ================================================================

    protected function validatePdf(
        string $content,
        UploadedFile $file
    ): void {

        $name = $file->getClientOriginalName();

        if (strpos($content, '%PDF-') !== 0) {

            throw new Exception(
                "Security Error: Invalid PDF header ({$name})"
            );
        }

        if (strpos($content, '%%EOF') === false) {

            throw new Exception(
                "Security Error: Corrupted PDF ({$name})"
            );
        }

        // BLOCK EMBEDDED FILES

        if (
            preg_match('/\/EmbeddedFiles|\/Filespec/i', $content)
        ) {

            throw new Exception(
                "Security Error: Embedded files inside PDF"
            );
        }

        // BLOCK PDF JAVASCRIPT

        $dangerousPdfKeys = [

            '/\/JavaScript/i',
            '/\/JS\s/i',
            '/\/Launch/i',
            '/\/RichMedia/i',
            '/\/OpenAction/i',
            '/\/AA\s/i',
            '/\/XFA/i',
        ];

        foreach ($dangerousPdfKeys as $pattern) {

            if (preg_match($pattern, $content)) {

                throw new Exception(
                    "Security Error: Dangerous PDF object detected"
                );
            }
        }

        // SCAN PDF STREAMS

        $this->scanAllPdfStreams($content, $name);
    }

    // ================================================================
    // PDF STREAM SCAN
    // ================================================================

    protected function scanAllPdfStreams(
        string $content,
        string $fileName
    ): void {

        preg_match_all(
            '/stream\r?\n(.*?)\r?\nendstream/s',
            $content,
            $matches
        );

        foreach ($matches[1] as $streamData) {

            $decoded =
                @gzuncompress($streamData) ?:
                @gzinflate($streamData);

            if ($decoded === false) {
                continue;
            }

            $dangerousPatterns = [

                '/<script\b/i',
                '/javascript:/i',
                '/onload\s*=/i',
                '/onerror\s*=/i',
                '/alert\s*\(/i',
                '/<svg/i',
                '/<iframe/i',
                '/document\.cookie/i',
                '/window\.location/i',
                '/burpcollaborator/i',
            ];

            foreach ($dangerousPatterns as $pattern) {

                if (
                    preg_match($pattern, $decoded)
                ) {

                    throw new Exception(
                        "Security Error: Malicious PDF stream payload detected in {$fileName}"
                    );
                }
            }
        }
    }

    // ================================================================
    // REAL MIME DETECTION
    // ================================================================

    protected function detectRealMime(string $path): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mime = finfo_file($finfo, $path);

        finfo_close($finfo);

        return strtolower(trim($mime));
    }

    // ================================================================
    // SAFE EXTENSION FROM MIME
    // ================================================================

    protected function extensionFromMime(string $mime): string
    {
        return match ($mime) {

            'image/jpeg' => 'jpg',

            'image/png' => 'png',

            'image/gif' => 'gif',

            'image/webp' => 'webp',

            'application/pdf' => 'pdf',

            'application/msword',
            'application/vnd.ms-office'
                => 'doc',

            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                => 'docx',

            'application/vnd.ms-excel',
            'application/msexcel',
            'application/x-msexcel',
            'application/x-ms-excel',
            'application/x-excel',
            'application/x-dos_ms_excel',
            'application/xls'
                => 'xls',

            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                => 'xlsx',

            'video/mp4'
                => 'mp4',

            default => 'bin',
        };
    }
}
