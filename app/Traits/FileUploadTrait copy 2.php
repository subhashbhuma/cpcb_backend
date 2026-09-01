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

            // Safe name: random + extension derived from MIME (not client claim)
            $safeExtension = $this->extensionFromMime($file->getMimeType());
            $fileName = Str::random(20) . '.' . $safeExtension;
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
            Log::error('File upload failed', ['error' => $e->getMessage()]);
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
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        // ── 1. Filename sanity ────────────────────────────────────────────
        if (
            strpos($originalName, "\0") !== false ||
            strpos(urldecode($originalName), "\0") !== false ||
            preg_match('/\.{2,}/', $originalName) ||
            substr_count($originalName, '.') > 1 ||
            preg_match('/[<>:"\/\\\\|?*\x00-\x1F]/', $originalName) ||
            strlen($originalName) > 250
        ) {
            $msg = "Security Error: Malicious filename — {$originalName}";
            Log::warning($msg);
            throw new Exception($msg);
        }

        // ── 2. Extension + MIME strict allowlist ──────────────────────────
        // SVG intentionally excluded — it carries XSS/SSRF/XXE payloads
        $allowedMime = [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],

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
            $msg = "Security Error: Extension not allowed — {$extension}";
            Log::warning($msg);
            throw new Exception($msg);
        }

        if (!in_array($mime, $allowedMime[$extension], true)) {
            $msg = "Security Error: MIME mismatch — ext={$extension}, detected={$mime}";
            Log::warning($msg);
            throw new Exception($msg);
        }

        // ── 3. Magic bytes (real binary fingerprint) ──────────────────────
        $handle = fopen($path, 'rb');
        $header = fread($handle, 12);
        fclose($handle);

        $magicMap = [
            'jpg' => ["\xFF\xD8\xFF"],
            'jpeg' => ["\xFF\xD8\xFF"],
            'png' => ["\x89PNG\r\n\x1a\n"],
            'gif' => ["GIF87a", "GIF89a"],
            'webp' => null,
            'pdf' => ['%PDF-'],

            'doc' => ["\xD0\xCF\x11\xE0"],
            'docx' => ["\x50\x4B\x03\x04"],

            'xls' => ["\xD0\xCF\x11\xE0"],
            'xlsx' => ["\x50\x4B\x03\x04"],

            'mp4' => ["ftyp"],
        ];

        if ($extension === 'webp') {
            if (!(substr($header, 0, 4) === 'RIFF' && substr($header, 8, 4) === 'WEBP')) {
                throw new Exception("Security Error: Magic bytes mismatch for webp — {$originalName}");
            }
        } elseif (isset($magicMap[$extension])) {

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
                $msg = "Security Error: Magic bytes mismatch — ext={$extension}, file={$originalName}";
                Log::warning($msg);
                throw new Exception($msg);
            }
        }

        // ── 4. Raw XML/SVG tag detection ──────────────────────────────────
        $content = file_get_contents($path, false, null, 0, 5 * 1024 * 1024);

        if ($content === false) {
            throw new Exception("Could not read file for security scanning.");
        }

        if (preg_match('/<\?xml|<svg|<!DOCTYPE/i', $content)) {
            $msg = "Security Error: XML/SVG structure in raw content — {$originalName}";
            Log::error($msg);
            throw new Exception($msg);
        }

        // ── 5. PDF pipeline ───────────────────────────────────────────────
        if ($extension === 'pdf') {
            $this->validatePdf($content, $file);
        }

        // ── 6. Office macro detection ─────────────────────────────────────
        if (in_array($extension, ['docx', 'xlsx'], true)) {

            $zip = new \ZipArchive();

            if ($zip->open($path) === true) {

                $hasMacro = $zip->locateName('word/vbaProject.bin') !== false
                    || $zip->locateName('xl/vbaProject.bin') !== false;

                $zip->close();

                if ($hasMacro) {
                    $msg = "Security Error: VBA macro in Office file — {$originalName}";
                    Log::error($msg);
                    throw new Exception($msg);
                }
            }
        }

        // ── 7. Image script injection ─────────────────────────────────────
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {

            if (preg_match('/<script|<\?php|<\?xml|<svg/i', $content)) {
                $msg = "Security Error: Script injection in image — {$originalName}";
                Log::error($msg);
                throw new Exception($msg);
            }
        }

        return true;
    }

    // ── PDF validation ────────────────────────────────────────────────────────

    protected function validatePdf(string $content, UploadedFile $file): void
    {
        $name = $file->getClientOriginalName();

        $err = fn($code, $msg)
            => throw new Exception("Security Error [{$code}]: {$msg} — {$name}");

        if (strpos($content, '%PDF-') !== 0)
            $err('SEC001', 'Invalid PDF header');

        if (strpos($content, '%%EOF') === false)
            $err('SEC002', 'Corrupted PDF, missing EOF');

        if (preg_match('/\/EmbeddedFiles|\/Filespec/i', $content))
            $err('SEC003', 'Embedded files not allowed');

        $dangerousKeys = [
            '/\/JavaScript/i',
            '/\/JS\s/i',
            '/\/Launch/i',
            '/\/RichMedia/i',
            '/\/AA\s/i',
            '/\/OpenAction/i',
            '/\/XFA/i',
        ];

        foreach ($dangerousKeys as $pattern) {

            if (preg_match($pattern, $content)) {
                $err('SEC004', "Restricted PDF element: {$pattern}");
            }
        }

        // Decode all compressed streams
        $this->scanAllPdfStreams($content, $name);
    }

    /**
     * Decompress every stream in the PDF and scan for:
     *  - Dangerous PDF keys hidden in ObjStm
     *  - SVG/script payloads encoded as PDF visible text
     */
    protected function scanAllPdfStreams(string $content, string $fileName): void
    {
        $dangerousKeys = [
            '/\/JavaScript/i',
            '/\/JS\s/i',
            '/\/Launch/i',
            '/\/RichMedia/i',
            '/\/OpenAction/i',
            '/\/XFA/i',
            '/\/AA\s/i',
        ];

        $svgScriptPatterns = [
            '/<svg/i',
            '/<script/i',
            '/javascript:/i',
            '/xlink:href/i',
            '/onerror\s*=/i',
            '/onload\s*=/i',
            '/<\?xml/i',
            '/<!DOCTYPE/i',
            '/data:text\/html/i',
            '/vbscript:/i',
            '/burpcollaborator/i',
        ];

        preg_match_all('/stream\r?\n(.*?)\r?\nendstream/s', $content, $matches);

        foreach ($matches[1] as $i => $streamData) {

            $dec = @gzuncompress($streamData) ?: @gzinflate($streamData);

            if ($dec === false) {
                continue;
            }

            // Scan raw decompressed bytes
            foreach ($dangerousKeys as $pattern) {

                if (preg_match($pattern, $dec)) {

                    $msg = "Security Error : Hidden PDF key in stream #{$i} ({$pattern}) — {$fileName}";
                    Log::error($msg);

                    throw new Exception($msg);
                }
            }

            // Reassemble visible text
            $visible = '';

            if (preg_match_all('/\(([^)]*)\)\s*(?:Tj|\]?\s*TJ)/s', $dec, $tj)) {
                $visible .= implode('', $tj[1]);
            }

            if (preg_match_all('/\(([^)\\\\]*(?:\\\\.[^)\\\\]*)*)\)/', $dec, $all)) {
                $visible .= implode('', $all[1]);
            }

            // Scan visible text
            foreach ($svgScriptPatterns as $pattern) {

                if (preg_match($pattern, $visible) || preg_match($pattern, $dec)) {

                    $msg = "Security Error : SVG/script in PDF stream #{$i} ({$pattern}) — {$fileName}";
                    Log::error($msg);

                    throw new Exception($msg);
                }
            }
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Derive safe extension from detected MIME.
     */
    protected function extensionFromMime(string $mime): string
    {
        return match ($mime) {

            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',

            'application/pdf' => 'pdf',

            'application/msword',
            'application/vnd.ms-office' => 'doc',

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

            'video/mp4' => 'mp4',

            default => 'bin',
        };
    }
}