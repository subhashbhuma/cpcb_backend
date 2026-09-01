<?php

namespace App\Services;

use App\DTO\EmailLogDto;
use App\Mail\SendOtpEmail;

use App\Mail\EmployeeForgotPasswordOtpMail;
use App\Mail\PasswordResetMail;
use App\Mail\PledgeOtpMail;
use App\Mail\TributeOtpMail;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function __construct(
        private EmailLogService $emailLogService
    ) {}

    /**
     * Send password reset email
     */
    public static function sendPasswordResetEmail($email, $resetUrl, $name = '', $isNewUser = false)
    {
        $emailLogService = app(EmailLogService::class);

        try {
            Mail::to($email)->send(new PasswordResetMail($resetUrl, $email, $name, $isNewUser));
            $emailLogDto = new EmailLogDto(
                $email,
                'password_reset',
                'Reset Your Gallantry Awards Account Password',
                'sent',
                $name,
                '',
                [
                    'is_new_user' => $isNewUser,
                    'reset_url' => $resetUrl,
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log email sent ' . date('Y-m-d H:i:s'));
            }

            Log::info('Reset Password Mail Sent' . date('Y-m-d H:i:s') . ' to ' . $email . ' - ' . $resetUrl);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage());

            $emailLogDto = new EmailLogDto(
                $email,
                'password_reset',
                'Reset Your Gallantry Awards Account Password',
                'failed',
                $name,
                $e->getMessage(),
                [
                    'is_new_user' => $isNewUser,
                    'reset_url' => $resetUrl,
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log email sent ' . date('Y-m-d H:i:s'));
            }

            Log::info('Reset Password Mail Sent' . date('Y-m-d H:i:s') . ' to ' . $email . ' - ' . $resetUrl);
        }
    }

    /**
     * Send OTP email for backend login
     */
    public static function sendBackendOtpEmail($email, $otp)
    {
        $emailLogService = app(EmailLogService::class);

        try {
            Mail::to($email)->send(new SendOtpEmail($otp));

            $emailLogDto = new EmailLogDto(
                $email,
                'backend_otp',
                'Your Gallantry Awards Login OTP',
                'sent',
                '',
                '',
                [
                    'otp' => $otp
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log OTP email sent ' . date('Y-m-d H:i:s'));
            }

            Log::info('OTP Mail Sent' . date('Y-m-d H:i:s') . ' to ' . $email);
        } catch (\Exception $e) {
            $emailLogDto = new EmailLogDto(
                $email,
                'backend_otp',
                'Your Gallantry Awards Login OTP',
                'failed',
                '',
                $e->getMessage(),
                [
                    'otp' => $otp
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log OTP email sent ' . date('Y-m-d H:i:s'));
            }
            Log::error('Failed to send OTP email: ' . $e->getMessage());
        }
    }



    /**
     * Send OTP email for public pledge
     */
    public static function sendPledgeOtpEmail($email, $otp)
    {
        $emailLogService = app(EmailLogService::class);
        try {
            Mail::mailer('smtp_otp')->to($email)->send(new PledgeOtpMail($otp));

            $emailLogDto = new EmailLogDto(
                $email,
                'pledge_otp',
                'OTP to Take Pledge - Gallantry Awards',
                'sent',
                '',
                '',
                ['otp' => $otp],
                date('Y-m-d H:i:s')
            );
            $emailLogService->create($emailLogDto);
            Log::info('Pledge OTP Mail Sent' . date('Y-m-d H:i:s') . ' to ' . $email);
        } catch (\Exception $e) {
            $emailLogDto = new EmailLogDto(
                $email,
                'pledge_otp',
                'OTP to Take Pledge - Gallantry Awards',
                'failed',
                '',
                $e->getMessage(),
                ['otp' => $otp],
                date('Y-m-d H:i:s')
            );
            $emailLogService->create($emailLogDto);
            Log::error('Failed to send Pledge OTP email: ' . $e->getMessage());
        }
    }

    /**
     * Send OTP email for public tribute
     */
    public static function sendTributeOtpEmail($email, $otp)
    {
        $emailLogService = app(EmailLogService::class);
        try {
            Mail::mailer('smtp_otp')->to($email)->send(new TributeOtpMail($otp));

            $emailLogDto = new EmailLogDto(
                $email,
                'tribute_otp',
                'OTP to Pay Tribute - Gallantry Awards',
                'sent',
                '',
                '',
                ['otp' => $otp],
                date('Y-m-d H:i:s')
            );
            $emailLogService->create($emailLogDto);
            Log::info('Tribute OTP Mail Sent' . date('Y-m-d H:i:s') . ' to ' . $email);
        } catch (\Exception $e) {
            $emailLogDto = new EmailLogDto(
                $email,
                'tribute_otp',
                'OTP to Pay Tribute - Gallantry Awards',
                'failed',
                '',
                $e->getMessage(),
                ['otp' => $otp],
                date('Y-m-d H:i:s')
            );
            $emailLogService->create($emailLogDto);
            Log::error('Failed to send Tribute OTP email: ' . $e->getMessage());
        }
    }

    /**
     * Send OTP email for employee login
     */
    public static function sendForgotPasswordOtpEmail($email, $otp)
    {
        $emailLogService = app(EmailLogService::class);
        try {
            Mail::mailer('smtp_otp')->to($email)->send(new EmployeeForgotPasswordOtpMail($otp));

            // Email Logs
            $emailLogDto = new EmailLogDto(
                $email,
                'forgot_password_otp',
                'Your Gallantry Awards Forgot Password OTP',
                'sent',
                '',
                '',
                [
                    'otp' => $otp
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log OTP email sent ' . date('Y-m-d H:i:s'));
            }
            Log::info('OTP Mail Sent' . date('Y-m-d H:i:s') . ' to ' . $email);
        } catch (\Exception $e) {
            // Email Logs
            $emailLogDto = new EmailLogDto(
                $email,
                'forgot_password_otp',
                'Your Gallantry Awards Forgot Password OTP',
                'failed',
                '',
                $e->getMessage(),
                [
                    'otp' => $otp
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log OTP email sent ' . date('Y-m-d H:i:s'));
            }
            Log::error('Failed to send OTP email: ' . $e->getMessage());
        }
    }

    /**
     * Send initial password reset email for new users
     */
    public static function sendInitialPasswordResetEmail($email, $name, $resetUrl,  $isNewUser = true)
    {
        $emailLogService = app(EmailLogService::class);
        try {
            Mail::to($email)->send(new PasswordResetMail($resetUrl, $email, $name, $isNewUser));
            // Email Logs
            $emailLogDto = new EmailLogDto(
                $email,
                'password_reset',
                'Set Your Gallantry Awards Account Password',
                'sent',
                $name,
                '',
                [
                    'reset_url' => $resetUrl,
                    'is_new_user' => $isNewUser
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log password reset email sent ' . date('Y-m-d H:i:s'));
            }
            Log::info('Initial password reset email sent successfully to: ' . $email);
        } catch (Exception $e) {
            // Email Logs
            $emailLogDto = new EmailLogDto(
                $email,
                'password_reset',
                'Set Your Gallantry Awards Account Password',
                'failed',
                $name,
                $e->getMessage(),
                [
                    'reset_url' => $resetUrl,
                    'is_new_user' => $isNewUser
                ],
                date('Y-m-d H:i:s')
            );

            $emailLogResult = $emailLogService->create($emailLogDto);
            if (!$emailLogResult) {
                Log::error('Failed to log password reset email sent ' . date('Y-m-d H:i:s'));
            }
            Log::error('Failed to send initial password reset email: ' . $e->getMessage());
        }
    }


}
