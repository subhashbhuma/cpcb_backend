<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CMSFileController extends Controller
{
    public function getfileurl(Request $request)
    {
        $encoded = $request->query('code');
        
        if (!$encoded) {
            return response()->json(['error' => 'Missing code'], 400);
        }

        // --- FIX 1: Handle Base64 "+" Issue ---
        // Browsers often convert "+" to space in URLs. We must revert it.
        $encoded = str_replace(' ', '+', $encoded);

        // --- FIX 2: Decode Base64 ---
        $decodedUrl = base64_decode($encoded);
        if ($decodedUrl === false) {
             return response()->json(['error' => 'Invalid encoding'], 400);
        }

        // --- FIX 3: Parse URL & Clean Path ---
        // We parse the URL first to isolate the path, then clean it.
        $parsedUrlPath = parse_url($decodedUrl, PHP_URL_PATH);
        
        // Remove '/storage/' or '/cpcb/storage/' prefix safely
        $relativePath = preg_replace('/^.*?\/?storage\//', '', $parsedUrlPath, 1);
        
        // Decode spaces/special chars in the filename (e.g. "My%20File.pdf" -> "My File.pdf")
        $relativePath = urldecode($relativePath);

        // --- FIX 4: Cross-Platform Path Building (Windows & Linux) ---
        // Build the absolute path
        $fullPath = public_path('storage/' . $relativePath);
        
        // Standardize slashes for the current OS (converts / to \ on Windows)
        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);

        // IDOR/Path Traversal Protection: Ensure resolved path stays within storage directory
        $realPath = realpath($filePath);
        $storageRoot = realpath(public_path('storage'));
        if (!$realPath || !$storageRoot || !str_starts_with($realPath, $storageRoot)) {
            return response()->json(['error' => 'Access denied'], 403);
        }
        $filePath = $realPath;

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // 1. Force clear any previous output buffers to avoid file corruption
        if (ob_get_level()) ob_end_clean();

        // 2. Robust MIME type detection
        $mimeType = $this->getSafeMimeType($filePath);
        $fileName = basename($filePath);

        // 3. BinaryFileResponse is essential for large files
        $response = new BinaryFileResponse($filePath);

        // 4. Set Headers
        if($mimeType == 'application/pdf' || $mimeType == "application/octet-stream"){
            $response->headers->set('Content-Type', 'application/pdf');
        }else{
            $response->headers->set('Content-Type', $mimeType);
        }  
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName
        );

        return $response;
    }

    /**
     * Safely detect MIME type using multiple methods
     */
    private function getSafeMimeType($path)
    {
        $mime = false;
        
        // Method A: FileInfo (Best)
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path);
            finfo_close($finfo);
        }

        // Method B: Mime Content Type (Backup)
        if (!$mime && function_exists('mime_content_type')) {
            $mime = mime_content_type($path);
        }

        // Default fallback
        return $mime ?: 'application/octet-stream';
    }


    /**
     * Resolve a file from the old CPCB site's openpdffile.php URL format.
     * Decodes base64 id, extracts filename, searches across module directories.
     */
    public function resolveOldSiteFile(Request $request)
    {
        $encoded = $request->query('id');

        if (!$encoded) {
            return response()->json(['status' => false, 'error' => 'Missing id parameter'], 400);
        }

        // Handle Base64 "+" issue (browsers convert "+" to space)
        $encoded = str_replace(' ', '+', $encoded);

        $decoded = base64_decode($encoded, true);
        if ($decoded === false) {
            return response()->json(['status' => false, 'error' => 'Invalid encoding'], 400);
        }

        // Extract just the filename from the decoded path (e.g. "TenderFiles/938_xxx.pdf" -> "938_xxx.pdf")
        $fileName = basename($decoded);

        if (empty($fileName)) {
            return response()->json(['status' => false, 'error' => 'Invalid file path'], 400);
        }

        // All module directories to search (from config/file_paths.php)
        $searchPaths = [
            'directions/en',
            'directions/hi',
            'latest_cpcb/en',
            'latest_cpcb/hi',
            'announcements/en',
            'announcements/hi',
            'comment_report/en',
            'comment_report/hi',
            'annual_report/en',
            'annual_report/hi',
            'tenders/en',
            'tenders/hi',
            'circulars/en',
            'circulars/hi',
            'job_file/en',
            'job_file/hi',
            'directories/profile_images',
            'recruitment_announcements/en',
            'recruitment_announcements/hi',
        ];

        $storageRoot = public_path('storage');

        foreach ($searchPaths as $dir) {
            $fullPath = $storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir) . DIRECTORY_SEPARATOR . $fileName;

            if (file_exists($fullPath)) {
                // Build the relative path as "storage/<dir>/<filename>" and base64 encode it
                $relativePath = 'storage/' . $dir . '/' . $fileName;
                $code = base64_encode($relativePath);

                return response()->json([
                    'status' => true,
                    'code'   => $code,
                    'name'   => $fileName,
                    'path'   => $dir,
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error'  => 'File not found',
        ], 404);
    }

    public function getFileDetail(Request $request){
        $encoded = $request->query('code');
        
        if (!$encoded) {
            return response()->json(['error' => 'Missing code'], 400);
        }
        $encoded = str_replace(' ', '+', $encoded);
        $decodedUrl = base64_decode($encoded);
        if ($decodedUrl === false) {
             return response()->json(['error' => 'Invalid encoding'], 400);
        }
        $parsedUrlPath = parse_url($decodedUrl, PHP_URL_PATH);
        
        // Remove '/storage/' or '/cpcb/storage/' prefix safely
        $relativePath = preg_replace('/^.*?\/?storage\//', '', $parsedUrlPath, 1);
        
        // Decode spaces/special chars in the filename (e.g. "My%20File.pdf" -> "My File.pdf")
        $relativePath = urldecode($relativePath);

        // --- FIX 4: Cross-Platform Path Building (Windows & Linux) ---
        // Build the absolute path
        $fullPath = public_path('storage/' . $relativePath);
        
        // Standardize slashes for the current OS (converts / to \ on Windows)
        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);

        // IDOR/Path Traversal Protection: Ensure resolved path stays within storage directory
        $realPath = realpath($filePath);
        $storageRoot = realpath(public_path('storage'));
        if (!$realPath || !$storageRoot || !str_starts_with($realPath, $storageRoot)) {
            return response()->json(['error' => 'Access denied'], 403);
        }
        $filePath = $realPath;

        return response()->json([
            'status'=>true,
            'data'=>getFileMeta($filePath),
        ]);
                                         
    }
}