<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\DTO\ComplaintDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicComplaintResource;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Mail\ComplaintConfirmationMail;
use App\Mail\NewComplaintNotificationMail;
use App\Services\ComplaintFormSubjectService;
use App\Traits\FileUploadTrait;
use App\DTO\EmailLogDto;
use App\Services\EmailLogService;

class PublicComplaintController extends Controller
{
    use FileUploadTrait;
    protected $complaintService;
    protected $complaintFormSubjectService;

    public function __construct()
    {
        $this->complaintService = new ComplaintService();
        $this->complaintFormSubjectService = new ComplaintFormSubjectService();
    }

    public function store(Request $request)
    {
        $rules = [
            'complaint_subject_id' => 'required|exists:complaint_form_subjects,id',
            'full_name' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:10|regex:/^[0-9]{10}$/',
            'location' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\p{M}\s\.\,\-_&\(\)\:\;\'’"\/@#\$%?!\+=\[\]\*।॥–—]*$/u',
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
            if ($request->hasFile('file')) {
                $file = $this->uploadFile($request->file('file'), Config::get('file_paths.COMPLAINT_FILE_PATH', 'complaint/files'));
                if (!$file['status']) {
                    return response()->json([
                        'success' => false,
                        'message' => $file['message'],
                    ], 422);
                }
                $fileName = $file['file_name'];
            }

            $dto = new ComplaintDto(
                $request->complaint_subject_id,
                $request->full_name,
                $request->email,
                $request->phone,
                $request->location,
                $request->message,
                $fileName,
                'pending'
            );

            $result = $this->complaintService->create($dto);

            if ($result) {
                // Fetch subject details for notification
                $subject = $this->complaintFormSubjectService->findById($request->complaint_subject_id);

                // Send confirmation email to user
                try {
                    Mail::to($result->email)->send(new ComplaintConfirmationMail($result, $subject));
                    
                    $emailLogService = new EmailLogService();
                    $emailLogDto = new EmailLogDto(
                        $result->email,
                        'complaint_confirmation',
                        $subject->title ?? 'Complaint Received - CPCB',
                        'sent',
                        $result->full_name,
                        null,
                        ['complaint_id' => $result->id],
                        now()
                    );
                    $emailLogService->create($emailLogDto);
                } catch (\Exception $e) {
                    Log::error('Failed to send complaint confirmation email: ' . $e->getMessage());
                    try {
                        $emailLogService = new EmailLogService();
                        $emailLogDto = new EmailLogDto(
                            $result->email,
                            'complaint_confirmation',
                            $subject->title ?? 'Complaint Received - CPCB',
                            'failed',
                            $result->full_name,
                            $e->getMessage(),
                            ['complaint_id' => $result->id],
                            now()
                        );
                        $emailLogService->create($emailLogDto);
                    } catch (\Exception $logEx) {
                        Log::error('Failed to create failed email log: ' . $logEx->getMessage());
                    }
                }

                // Send notification email to officer
                try {
                    $toEmail = Config::get('mail.complaint_form.to');
                    Mail::to($toEmail)
                        ->cc(Config::get('mail.complaint_form.cc'))
                        ->bcc(Config::get('mail.complaint_form.bcc'))
                        ->send(new NewComplaintNotificationMail($result, $subject));

                    $emailLogService = new EmailLogService();
                    $emailLogDto = new EmailLogDto(
                        $toEmail,
                        'complaint_notification',
                        $subject->title ?? 'New Complaint Received - CPCB',
                        'sent',
                        'Concerned Officer',
                        null,
                        ['complaint_id' => $result->id],
                        now()
                    );
                    $emailLogService->create($emailLogDto);
                } catch (\Exception $e) {
                    Log::error('Failed to send complaint officer notification: ' . $e->getMessage());
                    try {
                        $toEmail = Config::get('mail.complaint_form.to');
                        $emailLogService = new EmailLogService();
                        $emailLogDto = new EmailLogDto(
                            $toEmail,
                            'complaint_notification',
                            $subject->title ?? 'New Complaint Received - CPCB',
                            'failed',
                            'Concerned Officer',
                            $e->getMessage(),
                            ['complaint_id' => $result->id],
                            now()
                        );
                        $emailLogService->create($emailLogDto);
                    } catch (\Exception $logEx) {
                        Log::error('Failed to create failed officer email log: ' . $logEx->getMessage());
                    }
                }

                // Clear the OTP verification cache so it cannot be reused
                Cache::forget("otp_verified_email_{$request->email}");
                Cache::forget("otp_verified_phone_{$request->phone}");

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Complaint submitted successfully!',
                    'data' => new PublicComplaintResource($result)
                ]);
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complaint submission failed: ' . $e->getMessage());
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
