<?php

namespace App\Traits;

use Exception;
use ZipArchive;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    /**
     * Upload File
     */
    public function uploadFile(
        UploadedFile $file,
        string $folder
    ): array {

        try {

            // $this->isFileSafe($file);

            $this->ensureFolderPermissions(
                public_path("storage/{$folder}")
            );

            // SAFE EXTENSION FROM REAL MIME
            $safeExtension = $this->extensionFromMime(
                $this->detectRealMime(
                    $file->getRealPath()
                )
            );

            $fileName =
                Str::random(40)
                . '.'
                . $safeExtension;

            // STORE IN PUBLIC
            $filePath = $file->storeAs(
                $folder,
                $fileName,
                'public'
            );

            return [

                'status' => true,

                'file_name' => $fileName,

                'file_path' => $filePath,

                'original_name' =>
                $file->getClientOriginalName(),

                'mime_type' =>
                $this->detectRealMime(
                    $file->getRealPath()
                ),

                'size' =>
                $file->getSize(),
            ];
        } catch (Exception $e) {

            Log::error(
                'File upload failed',
                [
                    'error' =>
                    $e->getMessage(),
                ]
            );

            throw $e;
        }
    }

    /**
     * Ensure folder permissions
     */
    protected function ensureFolderPermissions(
        string $folderPath
    ): void {

        if (!File::exists($folderPath)) {

            File::makeDirectory(
                $folderPath,
                0755,
                true,
                true
            );
        } else {

            @chmod(
                $folderPath,
                0755
            );
        }
    }

    /**
     * Main security validation
     */
    protected function isFileSafe(
        UploadedFile $file
    ): bool {

        $path = $file->getRealPath();

        if (
            !$path ||
            !file_exists($path)
        ) {

            throw new Exception(
                'Uploaded file not found.'
            );
        }

        $originalName =
            $file->getClientOriginalName();

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        // ====================================================
        // BLOCK DANGEROUS EXTENSIONS
        // ====================================================

        $blockedExtensions = [

            'php',
            'phtml',
            'phar',
            'php3',
            'php4',
            'php5',
            'php7',
            'php8',

            'exe',
            'sh',
            'bash',
            'bat',
            'cmd',
            'com',

            'cgi',
            'pl',

            'js',
            'jsp',
            'asp',
            'aspx',
        ];

        if (
            in_array(
                $extension,
                $blockedExtensions,
                true
            )
        ) {

            throw new Exception(
                "Security Error: Dangerous extension blocked"
            );
        }

        // ====================================================
        // FILE SIZE VALIDATION
        // ====================================================

        $maxSize =
            10 * 1024 * 1024;

        if (
            $file->getSize()
            > $maxSize
        ) {

            throw new Exception(
                "Security Error: File too large"
            );
        }

        // ====================================================
        // FILENAME VALIDATION
        // ====================================================

        if (

            strpos(
                $originalName,
                "\0"
            ) !== false ||

            strpos(
                urldecode(
                    $originalName
                ),
                "\0"
            ) !== false ||

            preg_match(
                '/\.{2,}/',
                $originalName
            ) ||

            preg_match(
                '/\.(php|phtml|phar|exe|js|sh|bat)$/i',
                $originalName
            ) ||

            preg_match(
                '/[<>:"\/\\\\|?*\x00-\x1F]/',
                $originalName
            ) ||

            strlen(
                $originalName
            ) > 250

        ) {

            throw new Exception(
                "Security Error: Malicious filename detected"
            );
        }

        // ====================================================
        // REAL MIME
        // ====================================================

        $mime =
            $this->detectRealMime(
                $path
            );

        // ====================================================
        // CLIENT MIME
        // ====================================================

        $clientMime = strtolower(
            trim(
                $file->getClientMimeType()
            )
        );

        // ====================================================
        // BLOCK DANGEROUS CLIENT MIME
        // ====================================================

        $blockedClientMime = [

            'text/html',

            'application/javascript',

            'text/javascript',

            'application/x-httpd-php',

            'application/x-php',

            'image/svg+xml',

            'text/xml',

            'application/xml',
        ];

        if (
            in_array(
                $clientMime,
                $blockedClientMime,
                true
            )
        ) {

            throw new Exception(
                "Security Error: Dangerous client MIME detected ({$clientMime})"
            );
        }

        // ====================================================
        // BLOCK DANGEROUS REAL MIME
        // ====================================================

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

        foreach (
            $blockedMimePatterns
            as $blockedMime
        ) {

            if (
                stripos(
                    $mime,
                    $blockedMime
                ) !== false
            ) {

                throw new Exception(
                    "Security Error: Dangerous MIME type detected ({$mime})"
                );
            }
        }

        // ====================================================
        // ALLOWED MIME MAP
        // ====================================================

        $allowedMime = [

            // IMAGES
            'jpg' => [
                'image/jpeg'
            ],

            'jpeg' => [
                'image/jpeg'
            ],

            'png' => [
                'image/png'
            ],

            'gif' => [
                'image/gif'
            ],

            'webp' => [
                'image/webp'
            ],

            // PDF
            'pdf' => [
                'application/pdf'
            ],

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
                'video/mp4'
            ],
        ];

        // ====================================================
        // EXTENSION VALIDATION
        // ====================================================

        if (
            !isset(
                $allowedMime[$extension]
            )
        ) {

            throw new Exception(
                "Security Error: Extension not allowed ({$extension})"
            );
        }

        // ====================================================
        // REAL MIME VALIDATION
        // ====================================================

        if (
            !in_array(
                $mime,
                $allowedMime[$extension],
                true
            )
        ) {

            throw new Exception(
                "Security Error: MIME mismatch. Extension={$extension}, MIME={$mime}"
            );
        }

        // ====================================================
        // CLIENT MIME VALIDATION
        // ====================================================

        if (
            !in_array(
                $clientMime,
                $allowedMime[$extension],
                true
            )
        ) {

            throw new Exception(
                "Security Error: Client MIME mismatch. Client={$clientMime}"
            );
        }

        // ====================================================
        // MAGIC BYTE VALIDATION
        // ====================================================

        $this->validateMagicBytes(
            $path,
            $extension
        );

        // ====================================================
        // READ FILE CONTENT
        // ====================================================

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

        // ====================================================
        // GLOBAL MALICIOUS PAYLOAD SCAN
        // ====================================================

        $dangerousPatterns = [

            '/<script[^>]*>/i',

            '/<\/script>/i',

            '/javascript\s*:/i',

            '/vbscript\s*:/i',

            '/onload\s*=/i',

            '/onerror\s*=/i',

            '/onclick\s*=/i',

            '/onmouseover\s*=/i',

            '/document\.cookie/i',

            '/window\.location/i',

            '/eval\s*\(/i',

            '/base64_decode\s*\(/i',

            '/<iframe/i',

            '/<svg/i',

            '/<img/i',

            '/<body/i',

            '/<html/i',

            '/<!DOCTYPE/i',

            '/<\?php/i',

            '/<\?=/i',

            '/data:text\/html/i',

            '/burpcollaborator/i',
        ];

        foreach (
            $dangerousPatterns
            as $pattern
        ) {

            if (
                preg_match(
                    $pattern,
                    $content
                )
            ) {

                throw new Exception(
                    "Security Error: Malicious payload detected"
                );
            }
        }

        // ====================================================
        // PDF VALIDATION
        // ====================================================

        if (
            $extension === 'pdf'
        ) {

            $this->validatePdf(
                $content,
                $file
            );
        }

        // ====================================================
        // OFFICE MACRO DETECTION
        // ====================================================

        if (
            in_array(
                $extension,
                ['docx', 'xlsx'],
                true
            )
        ) {

            $zip = new ZipArchive();

            if (
                $zip->open($path)
                === true
            ) {

                $hasMacro =

                    $zip->locateName(
                        'word/vbaProject.bin'
                    ) !== false ||

                    $zip->locateName(
                        'xl/vbaProject.bin'
                    ) !== false;

                $zip->close();

                if ($hasMacro) {

                    throw new Exception(
                        'Security Error: Office macro detected'
                    );
                }
            }
        }

        // ====================================================
        // IMAGE RE-ENCODING
        // ====================================================

        $this->sanitizeImage(
            $path,
            $extension
        );

        return true;
    }

    /**
     * Validate magic bytes
     */
    protected function validateMagicBytes(
        string $path,
        string $extension
    ): void {

        $handle = fopen(
            $path,
            'rb'
        );

        $header = fread(
            $handle,
            20
        );

        fclose($handle);

        $magicMap = [

            'jpg' => [
                "\xFF\xD8\xFF"
            ],

            'jpeg' => [
                "\xFF\xD8\xFF"
            ],

            'png' => [
                "\x89PNG\r\n\x1a\n"
            ],

            'gif' => [
                "GIF87a",
                "GIF89a"
            ],

            'pdf' => [
                '%PDF-'
            ],

            'doc' => [
                "\xD0\xCF\x11\xE0"
            ],

            'docx' => [
                "PK"
            ],

            'xls' => [
                "\xD0\xCF\x11\xE0"
            ],

            'xlsx' => [
                "PK"
            ],

            'mp4' => [
                'ftyp'
            ],
        ];

        if (
            $extension === 'webp'
        ) {

            if (

                !(
                    substr(
                        $header,
                        0,
                        4
                    ) === 'RIFF'

                    &&

                    substr(
                        $header,
                        8,
                        4
                    ) === 'WEBP'
                )

            ) {

                throw new Exception(
                    'Security Error: Invalid WEBP file'
                );
            }

            return;
        }

        $matched = false;

        foreach (
            $magicMap[$extension]
            as $magic
        ) {

            if (
                strpos(
                    $header,
                    $magic
                ) !== false
            ) {

                $matched = true;

                break;
            }
        }

        if (!$matched) {

            throw new Exception(
                "Security Error: Magic bytes mismatch"
            );
        }
    }

    /**
     * Validate PDF
     */
    protected function validatePdf(
        string $content,
        UploadedFile $file
    ): void {

        $name =
            $file->getClientOriginalName();

        if (
            strpos(
                $content,
                '%PDF-'
            ) !== 0
        ) {

            throw new Exception(
                "Security Error: Invalid PDF header ({$name})"
            );
        }

        if (
            strpos(
                $content,
                '%%EOF'
            ) === false
        ) {

            throw new Exception(
                "Security Error: Corrupted PDF ({$name})"
            );
        }

        // BLOCK EMBEDDED FILES

        if (

            preg_match(
                '/\/EmbeddedFiles|\/Filespec/i',
                $content
            )

        ) {

            throw new Exception(
                "Security Error: Embedded files inside PDF"
            );
        }

        // BLOCK SCRIPT PAYLOADS

        if (

            preg_match(
                '/<script|javascript:|onload=|onerror=/i',
                $content
            )

        ) {

            throw new Exception(
                'Security Error: Script payload detected in PDF'
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

        foreach (
            $dangerousPdfKeys
            as $pattern
        ) {

            if (
                preg_match(
                    $pattern,
                    $content
                )
            ) {

                throw new Exception(
                    "Security Error: Dangerous PDF object detected"
                );
            }
        }
    }

    /**
     * Image sanitization
     */
    protected function sanitizeImage(
        string $path,
        string $extension
    ): void {

        if (
            in_array(
                $extension,
                ['jpg', 'jpeg'],
                true
            )
        ) {

            $image =
                @imagecreatefromjpeg($path);

            if (!$image) {

                throw new Exception(
                    'Invalid JPEG image'
                );
            }

            imagejpeg(
                $image,
                $path,
                90
            );

            imagedestroy($image);
        }

        if (
            $extension === 'png'
        ) {

            $image =
                @imagecreatefrompng($path);

            if (!$image) {

                throw new Exception(
                    'Invalid PNG image'
                );
            }

            // Preserve alpha transparency
            imagealphablending($image, false);
            imagesavealpha($image, true);

            imagepng(
                $image,
                $path
            );

            imagedestroy($image);
        }

        if (
            $extension === 'webp'
        ) {

            $image =
                @imagecreatefromwebp($path);

            if (!$image) {

                throw new Exception(
                    'Invalid WEBP image'
                );
            }

            // Preserve alpha transparency
            imagealphablending($image, false);
            imagesavealpha($image, true);

            imagewebp(
                $image,
                $path,
                90
            );

            imagedestroy($image);
        }
    }

    /**
     * Detect real MIME
     */
    protected function detectRealMime(
        string $path
    ): string {

        $finfo = finfo_open(
            FILEINFO_MIME_TYPE
        );

        $mime = finfo_file(
            $finfo,
            $path
        );

        finfo_close($finfo);

        return strtolower(
            trim($mime)
        );
    }

    /**
     * Safe extension from MIME
     */
    protected function extensionFromMime(
        string $mime
    ): string {

        return match ($mime) {

            'image/jpeg'
            => 'jpg',

            'image/png'
            => 'png',

            'image/gif'
            => 'gif',

            'image/webp'
            => 'webp',

            'application/pdf'
            => 'pdf',

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

            default
            => 'bin',
        };
    }
}
