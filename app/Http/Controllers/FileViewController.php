<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileViewController extends Controller
{

    private const ALLOWED_PUBLIC_PATHS = [
        'app/public/',
        'pages/',
        'circulars/',
        'uploads/',
        'announcements/',
        'annual_report/',
        'aq_manual_monitoring/',
        'comment_report/',
        'complaint/',
        'contact_detail/',
        'corrigendums/',
        'cpcb_portal_files/',
        'daily_aqi_bulletin/',
        'directions/',
        'directories/',
        'employee_excel_format/',
        'env_regulation_files/',
        'feedback/',
        'fortnightly_reports/',
        'gallery_event/',
        'government_portal_images/',
        'guidelines_for_complain/',
        'home_about/',
        'information_center_files/',
        'job_file/',
        'job_results/',
        'latest_cpcb/',
        'letters_issued/',
        'medias/',
        'menu_files/',
        'ngt_court_cases/',
        'page_category/',
        'photo_gallery/',
        'photo_gallery_event/',
        'photo_gallery_sub_event/',
        'publications/',
        'recruitment_announcements/',
        'site_settings/',
        'sliders/',
        'studies_reports/',
        'technical_report/',
        'tenders/',
        'user_profile/',
        'video_gallery/',
        'who_is_who/',
        'agra_air_quality/',
    ];

    public function showBackendFile(Request $request)
    {

        // -------- 1. Grab and validate the query param -------------
        $code = $request->query('code');

        if (!$code) {
            // Same behaviour as your CodeIgniter redirect
            return abort(404);
        }

        // -------- 2. Decode the URL we stored earlier ---------------
        $decodedPath = 'storage/' . customURIDecode($code);

        $filePath = str_replace(
            url('/'),
            public_path(),      // = FCPATH in CodeIgniter
            $decodedPath
        );

        if (PHP_OS_FAMILY === 'Windows') {
            $filePath = str_replace('/', '\\', $filePath);
        }

        // -------- 4. Return or 404 ---------------------------------
        if (!file_exists($filePath)) {
            return abort(404);
        }
        return response()->file($filePath);
    }

    /**
     * Stream a file back to the browser (public access).
     */
    public function show(Request $request)
    {
        // Validate and sanitize the code parameter
        $code = $request->query('code');

        if (!$code) {
            abort(404, 'File code not provided');
        }

        // Decode the path
        $decodedPath = customURIDecode($code);

        // Validate the decoded path format
        if (!filter_var($decodedPath, FILTER_VALIDATE_URL) && !str_starts_with($decodedPath, '/')) {
            abort(400, 'Invalid file path format');
        }

        // Strip protocol and host
        $pathOnly = preg_replace('/^https?:\/\/[^\/]+/', '', $decodedPath);
        $baseUrlPath = preg_replace('/^https?:\/\/[^\/]+/', '', url('/'));

        $relativePath = str_replace($baseUrlPath, '', $pathOnly);
        $relativePath = ltrim($relativePath, '/');

        // Security: Normalize path and prevent directory traversal
        $relativePath = str_replace(['../', '..\\', '\\'], '', $relativePath);

        // Check if path is in allowed directories
        $checkPath = preg_replace('/^storage\//', '', $relativePath);

        $isAllowed = false;
        foreach (self::ALLOWED_PUBLIC_PATHS as $allowedPath) {
            if (str_starts_with($checkPath, $allowedPath . '/') || $checkPath === $allowedPath) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            abort(403, 'Access to this directory is not allowed');
        }

        // Construct the full file path
        $filePath = public_path($relativePath);

        // Additional security: Ensure the resolved path is still within public directory
        $publicPath = realpath(public_path());
        $realFilePath = realpath($filePath);

        if (!$realFilePath || !str_starts_with($realFilePath, $publicPath)) {
            abort(403, 'Path traversal detected');
        }

        if (!file_exists($realFilePath)) {
            abort(404, 'File not found');
        }

        // Validate MIME type against allowed types
        $mimeType = mime_content_type($realFilePath);
        $allowedMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'video/mp4',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            abort(403, 'File type not allowed');
        }

        // Return the file
        return response()->file($realFilePath);
    }
}
