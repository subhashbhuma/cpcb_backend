<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    /**
     * Upload file with security validation
     */
    public function uploadFile(UploadedFile $file, string $folder): array
    {
        try {
            $this->isFileSafe($file);

            $this->ensureFolderPermissions(storage_path("app/public/{$folder}"));

            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($folder, $fileName, 'public');

            return [
                'status' => true,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ];

        } catch (Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                // 'trace' => $e->getTraceAsString()
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

    /**
     * Improved malicious file detection
     */
    protected function isFileSafe(UploadedFile $file): bool
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();


        $path = $file->getRealPath();
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        // 2. Strict Filename Sanity Checks (Audit Requirement compliance)
        // - Null Byte check (\0)
        // - URL Decoded Null Byte check (%00)
        // - Directory traversal / Double dot check (..)
        // - Double Extension check (e.g. test.php.jpg - Strictly blocks multiple dots)
        // - Metadata / Dangerous character check
        // - Long filename check (length > 250)
        if (
            strpos($originalName, "\0") !== false ||
            strpos(urldecode($originalName), "\0") !== false ||
            preg_match('/\.{2,}/', $originalName) ||
            substr_count($originalName, '.') > 1 ||
            preg_match('/[<>:"\/\\\\|?*\x00-\x1F]/', $originalName) ||
            strlen($originalName) > 250
        ) {
            $msg = "Blocked upload: Malicious filename structure detected (Null bytes, metadata characters, double dots, or double extensions). File: {$originalName}";
            Log::warning($msg);
            throw new Exception($msg);
        }



        $allowedMime = [
            'jpg' => ['image/jpeg', 'image/png'],
            'jpeg' => ['image/jpeg', 'image/png'],
            'png' => ['image/png', 'image/jpeg'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
            'pdf' => ['application/pdf'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'mp4' => ['video/mp4'],
        ];

        if (!isset($allowedMime[$extension])) {
            $msg = "Blocked upload: Extension not allowed. Extension: {$extension}";
            Log::warning($msg);
            throw new Exception($msg);
        }

        $mimes = (array) $allowedMime[$extension];
        if (!in_array($mime, $mimes)) {
            $msg = "Blocked upload: Extension/MIME mismatch. Extension: {$extension}, MIME: {$mime}";
            Log::warning($msg);
            throw new Exception($msg);
        }

        // Read first 5MB for scanning (sufficient for headers/scripts)
        $content = file_get_contents($path, false, null, 0, 5 * 1024 * 1024);
        if ($content === false) {
            throw new \Exception("Could not read file content for security scanning.");
        }

        /**
         * PDF Security: Refined to avoid false positives on text content.
         * Blocks active PDF actions and automated execution triggers.
         */
        // if ($extension === 'pdf') {
        //     // Removed common strings like ".js" or ".exe" which often appear in document text
        //     // Added focus on PDF dictionary keys that trigger scripts or external launches
        //     $maliciousPatterns = [
        //         '/\/JavaScript/i',
        //         '/\/JS\s*/i',
        //         '/\/Launch/i',
        //         '/\/EmbeddedFiles/i',
        //         '/\/AA/i', // Additional Actions
        //         '/\/OpenAction/i'
        //     ];

        //     foreach ($maliciousPatterns as $pattern) {
        //         if (preg_match($pattern, $content)) {
        //             $msg = "Security Error: Malicious PDF Active element detected! (Pattern: {$pattern}) for file: {$file->getClientOriginalName()}";
        //             Log::error($msg);
        //             throw new Exception($msg);
        //         }
        //     }
        // }

        // if ($extension === 'pdf') {

        //     $content = file_get_contents($file->getRealPath());

        //     $maliciousPatterns = [
        //         '/\/JavaScript/i',
        //         '/\/JS\s*/i',
        //         '/\/Launch/i',
        //         '/\/EmbeddedFiles/i',
        //         '/\/AA/i',
        //         '/\/RichMedia/i',
        //         '/\/ObjStm/i',
        //         '/\/XFA/i'
        //     ];

        //     // Helper for consistent audit messages
        //     $securityError = function ($code, $message) {
        //         throw new Exception("Security Error $message");
        //     };

        //     // 1. Validate PDF header
        //     if (strpos($content, '%PDF-') !== 0) {
        //         $securityError('SEC001', 'Invalid or unsupported PDF format.');
        //     }

        //     // 2. Validate EOF
        //     if (strpos($content, '%%EOF') === false) {
        //         $securityError('SEC002', 'Corrupted or incomplete PDF file.');
        //     }

        //     // 3. Block embedded files
        //     if (preg_match('/\/EmbeddedFiles|\/Filespec/i', $content)) {
        //         $securityError('SEC003', 'PDF contains restricted embedded content.');
        //     }

        //     // 4. OpenAction + JS (auto execution)
        //     if (
        //         preg_match('/\/OpenAction/i', $content) &&
        //         preg_match('/\/(JavaScript|JS)/i', $content)
        //     ) {
        //         Log::error('Malicious PDF detected (OpenAction + JS)', [
        //             'file' => $file->getClientOriginalName()
        //         ]);

        //         $securityError('SEC004', 'PDF contains auto-executing script.');
        //     }

        //     // 5. General malicious patterns
        //     foreach ($maliciousPatterns as $pattern) {

        //         if (preg_match($pattern, $content)) {

        //             Log::error('Malicious PDF detected', [
        //                 'file' => $file->getClientOriginalName(),
        //                 'pattern' => $pattern
        //             ]);

        //             $securityError('SEC005', 'PDF contains restricted active elements.');
        //         }
        //     }
        // }

        // if ($extension === 'pdf') {

        //     $content = file_get_contents($file->getRealPath(), false, null, 0, 500000);

        //     $maliciousPatterns = [
        //         '/\/JavaScript/i',
        //         '/\/Launch/i',
        //         '/\/EmbeddedFiles/i',
        //         '/\/RichMedia/i'
        //     ];

        //     $securityError = function ($code, $message) {
        //         throw new Exception("Security Error $message");
        //     };

        //     // 1. Validate header
        //     if (strpos($content, '%PDF-') !== 0) {
        //         $securityError('SEC001', 'Invalid PDF format.');
        //     }

        //     // 2. Validate EOF
        //     if (strpos($content, '%%EOF') === false) {
        //         $securityError('SEC002', 'Corrupted PDF.');
        //     }

        //     // 3. Block embedded files
        //     if (preg_match('/\/EmbeddedFiles|\/Filespec/i', $content)) {
        //         $securityError('SEC003', 'Embedded files not allowed.');
        //     }

        //     // 4. OpenAction + JS (critical)
        //     if (
        //         preg_match('/\/OpenAction/i', $content) &&
        //         preg_match('/\/JavaScript/i', $content)
        //     ) {
        //         $securityError('SEC004', 'Auto-executing script found.');
        //     }

        //     // 5. Other checks
        //     foreach ($maliciousPatterns as $pattern) {
        //         if (preg_match($pattern, $content)) {
        //             $securityError('SEC005', 'Restricted PDF feature found.');
        //         }
        //     }
        // }

        if ($extension === 'pdf') {

            $path = $file->getRealPath();

            $securityError = function ($code, $message) {
                throw new Exception("Security Error {$code}: {$message}");
            };

            /*
            |--------------------------------------------------------------------------
            | MIME VALIDATION
            |--------------------------------------------------------------------------
            */

            $mime = mime_content_type($path);

            if (!in_array($mime, ['application/pdf', 'application/x-pdf'])) {
                $securityError('SEC001', 'Invalid PDF MIME type.');
            }

            /*
            |--------------------------------------------------------------------------
            | SIZE LIMIT
            |--------------------------------------------------------------------------
            */

            if (filesize($path) > 10 * 1024 * 1024) {
                $securityError('SEC002', 'PDF exceeds allowed size.');
            }

            /*
            |--------------------------------------------------------------------------
            | READ SAFE PARTIAL CONTENT
            |--------------------------------------------------------------------------
            */

            $content = file_get_contents($path, false, null, 0, 1024 * 1024);

            if ($content === false) {
                $securityError('SEC003', 'Unable to read PDF.');
            }

            /*
            |--------------------------------------------------------------------------
            | VALID PDF HEADER
            |--------------------------------------------------------------------------
            */

            if (strpos($content, '%PDF-') !== 0) {
                $securityError('SEC004', 'Invalid PDF header.');
            }

            /*
            |--------------------------------------------------------------------------
            | EOF CHECK
            |--------------------------------------------------------------------------
            */

            $fp = fopen($path, 'rb');

            $seek = max(filesize($path) - 4096, 0);

            fseek($fp, $seek);

            $tail = fread($fp, 4096);

            fclose($fp);

            if (strpos($tail, '%%EOF') === false) {
                $securityError('SEC005', 'Corrupted PDF.');
            }

            /*
            |--------------------------------------------------------------------------
            | BLOCK ONLY DANGEROUS FEATURES
            |--------------------------------------------------------------------------
            */

            $dangerousPatterns = [
                '/\/JavaScript/i' => 'JavaScript detected.',
                '/\/JS/i' => 'Embedded JavaScript detected.',
                '/\/Launch/i' => 'Launch action detected.',
                '/\/EmbeddedFiles/i' => 'Embedded files detected.',
                '/\/RichMedia/i' => 'Rich media detected.',
            ];

            foreach ($dangerousPatterns as $pattern => $message) {

                if (preg_match($pattern, $content)) {
                    $securityError('SEC006', $message);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL: OPENACTION + JAVASCRIPT
            |--------------------------------------------------------------------------
            */

            if (
                preg_match('/\/OpenAction/i', $content) &&
                preg_match('/\/JavaScript|\/JS/i', $content)
            ) {
                $securityError('SEC007', 'Auto executing script detected.');
            }

            /*
            |--------------------------------------------------------------------------
            | PDF BOMB PROTECTION
            |--------------------------------------------------------------------------
            */

            preg_match_all('/\d+\s+\d+\s+obj/i', $content, $matches);

            if (count($matches[0]) > 5000) {
                $securityError('SEC008', 'Suspicious PDF structure.');
            }
        }
        /**
         * Office Macros (DOCX/XLSX)
         */
        if (in_array($extension, ['docx', 'xlsx'])) {
            $zip = new \ZipArchive();
            if ($zip->open($path) === true) {
                $hasMacro = $zip->locateName('word/vbaProject.bin') !== false ||
                    $zip->locateName('xl/vbaProject.bin') !== false;
                $zip->close();
                if ($hasMacro) {
                    $msg = "Security Error: Malicious Office Macro detected in file: {$file->getClientOriginalName()}";
                    Log::error($msg);
                    throw new Exception($msg);
                }
            }
        }

        /**
         * Image Script Injection
         */
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            if (preg_match('/<script|<?php/i', $content)) {
                $msg = "Security Error: Executable script injection found in Image file: {$file->getClientOriginalName()}";
                Log::error($msg);
                throw new Exception($msg);
            }
        }

        return true;
    }
}