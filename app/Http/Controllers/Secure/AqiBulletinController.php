<?php

namespace App\Http\Controllers\secure;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;


class AqiBulletinController extends Controller
{
   

    public function getTodayAqiBulletinFile()
    {
        $basePath = config('file_paths.DAILY_AQI_BULLETIN_FILE_PATH');

        // Check last 10 days first
        for ($i = 0; $i < 10; $i++) {
            $date = Carbon::now()->subDays($i)->format('Ymd');
            $fileName = "AQI_Bulletin_{$date}.pdf";
            $filePath = $basePath . '/' . $fileName;

            if (Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'status' => true,
                    'file_path' => base64_encode($filePath)
                ]);
            }
        }

        // If not found, get latest file from directory
        $files = Storage::disk('public')->files($basePath);

        $aqiFiles = collect($files)->filter(function ($file) {
            return str_contains($file, 'AQI_Bulletin_') && str_ends_with($file, '.pdf');
        });

        if ($aqiFiles->isNotEmpty()) {
            $latestFile = $aqiFiles->sortByDesc(function ($file) {
                return Storage::disk('public')->lastModified($file);
            })->first();

            return response()->json([
                'status' => true,
                'file_path' => base64_encode($latestFile)
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'File not found'
        ], 404);
    }

    public function getTodayDelhiNcrAqiBulletinFile()
    {
        $basePath = config('file_paths.DAILY_AQI_BULLETIN_FILE_PATH_DELHI_NCR', 'Downloads/Delhi-NCR');

        // Check last 10 days first
        for ($i = 0; $i < 10; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y_m_d');
            $fileName = "NCR_AQI_Bulletin_{$date}.pdf";
            $filePath = $basePath . '/' . $fileName;

            if (Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'status' => true,
                    'file_path' => base64_encode($filePath)
                ]);
            }
        }

        // If not found, get latest file from directory
        $files = Storage::disk('public')->files($basePath);

        $aqiFiles = collect($files)->filter(function ($file) {
            return str_contains($file, 'NCR_AQI_Bulletin_') && str_ends_with($file, '.pdf');
        });

        if ($aqiFiles->isNotEmpty()) {
            $latestFile = $aqiFiles->sortByDesc(function ($file) {
                return Storage::disk('public')->lastModified($file);
            })->first();

            return response()->json([
                'status' => true,
                'file_path' => base64_encode($latestFile)
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'File not found'
        ], 404);
    }

    public function uploadDailyAqiBulletin(Request $request)
    {
        // $origin = $request->header('Origin');
        // $allowedOrigins = config('cors.allowed_origins', []);
        // if ($origin && !in_array($origin, $allowedOrigins) && !in_array('*', $allowedOrigins)) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized Domain: CORS request denied.'
        //     ], 403);
        // }

        if ($request->input('cwtoken') !== '123hshfl9734h3khds@slfjs$uslfsjslsf') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'file_contents' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $basePath = config('file_paths.DAILY_AQI_BULLETIN_FILE_PATH');
            $file = $request->file('file_contents');
            $fileName = $file->getClientOriginalName();

            if (!preg_match('/^AQI_Bulletin_\d{8}\.pdf$/i', $fileName)) {
                $msg = "Invalid filename format: " . $fileName;
                Mail::to(env('MAIL_AQI_AQI_BULLETIN_FILE_UPLOAD_ERROR'))->send(new \App\Mail\AqiUploadFailedMail($msg, $fileName));
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid filename format.'
                ], 422);
            }

            Storage::disk('public')->putFileAs(
                $basePath,
                $file,
                $fileName
            );

            return response()->json([
                'status' => true,
                'message' => 'File uploaded successfully',
                // 'file_name' => $fileName,
                // 'file_url'  => asset('storage/' . $basePath . '/' . $fileName),
            ]);

        } catch (\Exception $e) {
            $msg = 'MOVE UPLOADED FILE FAILED! ' . $e->getMessage();
            Mail::to(env('MAIL_AQI_AQI_BULLETIN_FILE_UPLOAD_ERROR'))->send(new \App\Mail\AqiUploadFailedMail($msg, $fileName ?? 'Unknown File'));

            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'status' => false,
                    'message' => $msg
                ], 422);
            }

            return response()->json([
                'status' => false,
                'message' => 'File upload failed'
            ], 500);
        }
    }

    public function uploadDelhiNcrAqiBulletin(Request $request)
    {
        // $origin = $request->header('Origin');
        // $allowedOrigins = config('cors.allowed_origins', []);
        // if ($origin && !in_array($origin, $allowedOrigins) && !in_array('*', $allowedOrigins)) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized Domain: CORS request denied.'
        //     ], 403);
        // }

        if ($request->input('cwtoken') !== '123hshfl9734h3khds@slfjs$uslfsjslsf') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'file_contents' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $basePath = config('file_paths.DAILY_AQI_BULLETIN_FILE_PATH_DELHI_NCR');
            $file = $request->file('file_contents');
            $fileName = $file->getClientOriginalName();

            if (!preg_match('/^NCR_AQI_Bulletin_\d{4}_\d{2}_\d{2}\.pdf$/i', $fileName)) {
                $msg = "Invalid filename format for Delhi NCR AQI: " . $fileName;
                Mail::to(env('MAIL_AQI_AQI_BULLETIN_FILE_UPLOAD_ERROR'))->send(new \App\Mail\DelhiNcrAqiUploadFailedMail($msg, $fileName));
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid filename format. Expected format: NCR_AQI_Bulletin_YYYY_MM_DD.pdf'
                ], 422);
            }

            Storage::disk('public')->putFileAs(
                $basePath,
                $file,
                $fileName
            );

            return response()->json([
                'status' => true,
                'message' => 'File uploaded successfully',
            ]);

        } catch (\Exception $e) {
            $msg = 'MOVE UPLOADED FILE FAILED! ' . $e->getMessage();
            Mail::to(env('MAIL_AQI_AQI_BULLETIN_FILE_UPLOAD_ERROR'))->send(new \App\Mail\DelhiNcrAqiUploadFailedMail($msg, $fileName ?? 'Unknown File'));

            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'status' => false,
                    'message' => $msg
                ], 422);
            }

            return response()->json([
                'status' => false,
                'message' => 'File upload failed'
            ], 500);
        }
    }


    public function fetchAqiList(Request $request)
    {
        try {
            $basePath = config('file_paths.DAILY_AQI_BULLETIN_FILE_PATH');
            if (!$basePath) {
                throw new Exception("AQI file path configuration is missing.");
            }
            $bulletins = [];
            $searchDate = $request->input('date');
            if ($searchDate) {
                try {
                    $formattedDate = Carbon::parse($searchDate)->format('Ymd');
                    $fileName = "AQI_Bulletin_{$formattedDate}.pdf";

                    if (Storage::disk('public')->exists($basePath . '/' . $fileName)) {
                        $bulletins[] = $this->formatAqiResponse($fileName, $basePath);
                    }
                } catch (Exception $e) {
                    Log::warning("Invalid date format provided in AQI search: " . $searchDate);
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid date format provided.',
                        'data' => []
                    ], 400);
                }
            } else {
                for ($i = 0; $i < 30; $i++) {
                    $date = Carbon::now()->subDays($i)->format('Ymd');
                    $fileName = "AQI_Bulletin_{$date}.pdf";

                    if (Storage::disk('public')->exists($basePath . '/' . $fileName)) {
                        $bulletins[] = $this->formatAqiResponse($fileName, $basePath);
                    }

                    if (count($bulletins) >= 10)
                        break;
                }
            }
            $latestFileDate = !empty($bulletins) ? $bulletins[0]['publish_date'] : null;

            return response()->json([
                'status' => true,
                'message' => 'AQI list fetched successfully',
                'data' => $bulletins,
                'meta' => [
                    'lastUpdatedOn' => $latestFileDate,
                    'total_records' => count($bulletins)
                ]
            ], 200);

        } catch (Exception $e) {
            Log::error("Error in fetchAqiList: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the AQI list.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function formatAqiResponse($fileName, $basePath)
    {
        $datePart = str_replace(['AQI_Bulletin_', '.pdf'], '', $fileName);
        $dateObj = Carbon::createFromFormat('Ymd', $datePart);
        return [
            'title' => "AQI Bulletin " . $dateObj->format('D jS M Y'),
            'title_hi' => "वायु गुणवत्ता सूचकांक विज्ञप्ति " . $dateObj->format('D jS M Y'),
            'publish_date' => $dateObj->toDateString(),
            'file_path' => base64_encode($basePath . '/' . $fileName)
        ];
    }
}
