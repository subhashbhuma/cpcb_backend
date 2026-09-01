<?php

namespace App\Http\Controllers\Api;

// Add this import
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\DTO\FeedbackDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicFeedbackResource;
use App\Services\FeedbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Mail\FeedbackConfirmationMail;
use App\Mail\NewFeedbackNotificationMail;
use App\Traits\FileUploadTrait;
use App\DTO\EmailLogDto;
use App\Services\EmailLogService;

class PublicFeedbackController extends Controller
{
    use FileUploadTrait;
    protected $feedbackService;
    public function __construct()
    {
        $this->feedbackService = new FeedbackService();
    }

    public function store(Request $request)
    {
        $rules = [
            'full_name' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:10|regex:/^[0-9]{10}$/',
            'message' => 'required|string|max:5000|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify that OTP was verified for email and phone
        $emailVerified = Cache::get("otp_verified_email_{$request->email}");
        $phoneVerified = Cache::get("otp_verified_phone_{$request->phone}");

        if (!$emailVerified || !$phoneVerified) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify both Email and Phone Number using OTP before submitting.',
                'errors' => [
                    'email' => !$emailVerified ? ['Email is not verified.'] : [],
                    'phone' => !$phoneVerified ? ['Phone number is not verified.'] : [],
                ]
            ], 422);
        }
        DB::beginTransaction();


        try {
            $fileName = null;

            // Handle file upload safely
            if ($request->hasFile('file')) {
                $file = $this->uploadFile($request->file('file'), Config::get('file_paths.FEEDBACK_FILE_PATH', 'feedback/files'));
                if (!$file['status']) {
                    return response()->json([
                        'success' => false,
                        'message' => $file['message'],
                    ], 422);
                }
                $fileName = $file['file_name'];
            }

            // Create DTO from validated data
            $dto = new FeedbackDto(
                $request->full_name,
                $request->email,
                $request->phone,
                $request->message,
                $fileName,
                'pending'
            );

            $result = $this->feedbackService->create($dto);

            if ($result) {


                // Send confirmation email to user
                try {
                    Mail::to($result->email)->send(new FeedbackConfirmationMail($result));

                    $emailLogService = new EmailLogService();
                    $emailLogDto = new EmailLogDto(
                        $result->email,
                        'feedback_confirmation',
                        'Feedback Received - CPCB',
                        'sent',
                        $result->full_name,
                        null,
                        ['feedback_id' => $result->id],
                        now()
                    );
                    $emailLogService->create($emailLogDto);
                } catch (\Exception $e) {
                    Log::error('Failed to send feedback confirmation email: ' . $e->getMessage());
                    try {
                        $emailLogService = new EmailLogService();
                        $emailLogDto = new EmailLogDto(
                            $result->email,
                            'feedback_confirmation',
                            'Feedback Received - CPCB',
                            'failed',
                            $result->full_name,
                            $e->getMessage(),
                            ['feedback_id' => $result->id],
                            now()
                        );
                        $emailLogService->create($emailLogDto);
                    } catch (\Exception $logEx) {
                        Log::error('Failed to create failed feedback email log: ' . $logEx->getMessage());
                    }
                }


                // Send notification email to officer
                try {
                    $toEmail = Config::get('mail.query_form.to');
                    Mail::to($toEmail)
                        ->cc(Config::get('mail.query_form.cc'))
                        ->bcc(Config::get('mail.query_form.bcc'))
                        ->send(new NewFeedbackNotificationMail($result));

                    $emailLogService = new EmailLogService();
                    $emailLogDto = new EmailLogDto(
                        $toEmail,
                        'feedback_notification',
                        'New Feedback Received - CPCB',
                        'sent',
                        'Concerned Officer',
                        null,
                        ['feedback_id' => $result->id],
                        now()
                    );
                    $emailLogService->create($emailLogDto);
                } catch (\Exception $e) {
                    Log::error('Failed to send officer notification: ' . $e->getMessage());
                    try {
                        $toEmail = Config::get('mail.query_form.to');
                        $emailLogService = new EmailLogService();
                        $emailLogDto = new EmailLogDto(
                            $toEmail,
                            'feedback_notification',
                            'New Feedback Received - CPCB',
                            'failed',
                            'Concerned Officer',
                            $e->getMessage(),
                            ['feedback_id' => $result->id],
                            now()
                        );
                        $emailLogService->create($emailLogDto);
                    } catch (\Exception $logEx) {
                        Log::error('Failed to create failed officer feedback email log: ' . $logEx->getMessage());
                    }
                }

                // Clear the OTP verification cache so it cannot be reused
                Cache::forget("otp_verified_email_{$request->email}");
                Cache::forget("otp_verified_phone_{$request->phone}");

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Feedback submitted successfully!',
                    'data' => new PublicFeedbackResource($result)
                ]);
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Feedback submission failed: ' . $e->getMessage());
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.']);
        }
    }
}