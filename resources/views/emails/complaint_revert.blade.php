<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style @cspNonce>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px 20px;
            color: #1f2937;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        .brand-header {
            background-color: #0f8546;
            padding: 24px;
            text-align: center;
            color: #ffffff;
        }
        .brand-header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .brand-header p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #e6f4ea;
            text-transform: none;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .brand-header p.gov {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #a7f3d0;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .card-body {
            padding: 32px;
        }
        h2 {
            margin: 0 0 16px 0;
            font-size: 18px;
            color: #111827;
            font-weight: 700;
        }
        .salutation {
            font-size: 15px;
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.5;
        }
        .info-box strong {
            color: #1f2937;
        }
        .response-box {
            background-color: #f0fdf4;
            border-left: 4px solid #0f8546;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 24px;
        }
        .response-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #15803d;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .response-content {
            font-size: 14px;
            color: #1f2937;
            line-height: 1.6;
            white-space: pre-line;
        }
        .signoff {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
        }
        .divider {
            height: 1px;
            background-color: #f3f4f6;
            margin-bottom: 24px;
            margin-top: 24px;
        }
        .help-text {
            font-size: 11px;
            color: #9ca3af;
            line-height: 1.5;
            text-align: center;
            margin: 0;
        }
        .brand-footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 24px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-header">
            <h1>Central Pollution Control Board</h1>
            <p>Ministry of Environment, Forest and Climate Change</p>
            <p class="gov">Government of India</p>
        </div>
        <div class="card-body">
            <h2>Official Response to Complaint</h2>
            
            <div class="salutation">Dear {{ $Complaint->full_name }},</div>
            <p class="intro-text">Thank you for submitting your complaint to the Central Pollution Control Board. We have processed your submission and an official response is detailed below:</p>

            <div class="info-box">
                <strong>Complaint Subject:</strong> {{ $Complaint->complaintSubject->title ?? 'N/A' }}<br>
                <strong>Submitted On:</strong> {{ $Complaint->created_at->format('d M Y, h:i A') }}
            </div>

            <div class="response-box">
                <div class="response-title">CPCB Official Response</div>
                <div class="response-content">{{ $revertMessage }}</div>
            </div>

            <p class="intro-text">If you have any further questions or need additional clarification, please feel free to reach out to us.</p>

            <div class="signoff">
                Best regards,<br>
                <strong>CPCB Support Team</strong>
            </div>

            <div class="divider"></div>
            <p class="help-text">This is an automated system response. Please do not reply directly to this email address.</p>
        </div>
    </div>
    <div class="brand-footer">
        © {{ date('Y') }} Central Pollution Control Board. All Rights Reserved.<br>
        Parivesh Bhawan, East Arjun Nagar, Delhi - 110032
    </div>
</body>
</html>