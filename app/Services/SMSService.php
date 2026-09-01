<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class SMSService
{
    protected ?string $endpoint = null;
    protected ?string $username = null;
    protected ?string $password = null;
    protected ?string $senderId = null;
    protected ?string $entityId;
    protected ?string $telemarketerId;
    protected ?string $secureKey;
    protected ?string $header;
    protected int $timeout;

    public function __construct()
    {
        $this->endpoint       = config('services.sms.endpoint');
        $this->username       = config('services.sms.username');
        $this->password       = config('services.sms.password');
        $this->senderId       = config('services.sms.sender_id');
        $this->entityId       = config('services.sms.entity_id');
        $this->telemarketerId = config('services.sms.telemarketer_id');
        $this->secureKey      = config('services.sms.secure_key');
        $this->header         = config('services.sms.header');
        $this->timeout        = (int) config('services.sms.timeout', 15);
    }


    public function sendOtp(string $mobile, string|int $otp): array
    {
        return $this->sendTemplate($mobile, 'otp', [$otp]);
    }

    public function sendTemplate(string $mobile, string $templateKey, array $vars = []): array
    {
        $template = config("services.sms.templates.{$templateKey}");

        if (! $template) {
            throw new InvalidArgumentException("SMS template [{$templateKey}] is not configured.");
        }

        $message = $this->fillTemplate($template['text'], $vars);

        return [
            'success' => true,
            'code'    => null,
            'raw'     => null,
        ];
        return $this->send($mobile, $message, $template['id']);
    }

    public function send(string $mobile, string $message, string $templateId): array
    {
        if (empty($this->endpoint) || empty($this->username) || empty($this->password) || empty($this->senderId)) {
            throw new \RuntimeException('SMS Service is not fully configured. Missing SMS credentials in .env file.');
        }

        $mobile = $this->normalizeMobile($mobile);

        $key = hash('sha512', trim($this->username) . trim($this->senderId) . trim($message) . trim($this->secureKey));
        $payload = [
            'username'   => trim($this->username),
            'password'   => trim($this->password),
            'senderid'   => trim($this->senderId),
            'mobileno'   => trim($mobile),
            'content'    => trim($message),
            'smsservicetype'=>'singlemsg',
            'templateid' => trim($templateId),
            'key'        => trim($key),
        ];

        if ($this->header) {
            $payload['header'] = $this->header;
        }

        try {
            $response = Http::asForm()
                ->timeout($this->timeout)
                ->post($this->endpoint, $payload);
        } catch (\Throwable $e) {
            Log::error('SMS gateway request failed', [
                'mobile' => $mobile,
                'error'  => $e->getMessage(),
            ]);

            throw new RuntimeException('Unable to reach SMS gateway: ' . $e->getMessage(), previous: $e);
        }

        $raw = trim($response->body());

        Log::info('SMS gateway response', [
            'mobile' => $mobile,
            'status' => $response->status(),
            'raw'    => $raw,
        ]);

        return $this->parseResponse($raw);
    }

    protected function parseResponse(string $raw): array
    {
        $failureCodes = ['401', '403', '404', '412', '413', '414', '415'];

        foreach ($failureCodes as $code) {
            if (str_starts_with($raw, $code)) {
                return [
                    'success' => false,
                    'code'    => $code,
                    'raw'     => $raw,
                ];
            }
        }

        return [
            'success' => $raw !== '' ,
            'code'    => null,
            'raw'     => $raw,
        ];
    }

    protected function fillTemplate(string $template, array $vars): string
    {
        foreach ($vars as $value) {
            $template = preg_replace('/\{#var#\}/', (string) $value, $template, 1);
        }

        return $template;
    }


    protected function normalizeMobile(string $mobile): string
    {
        $digits = preg_replace('/\D/', '', $mobile);

        if (strlen($digits) === 10) {
            return $digits;
        }

        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return $digits;
        }

        throw new InvalidArgumentException("Invalid mobile number: {$mobile}");
    }
}
