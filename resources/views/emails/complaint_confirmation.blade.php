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
            max-width: 560px;
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
            padding: 40px 32px;
            text-align: center;
        }
        .icon-box {
            width: 56px;
            height: 56px;
            background-color: #e6f4ea;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }
        .icon {
            font-size: 24px;
            color: #0f8546;
            line-height: 56px;
        }
        h2 {
            margin: 0 0 12px 0;
            font-size: 22px;
            color: #111827;
            font-weight: 700;
        }
        .intro-text {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0 0 32px 0;
        }
        .id-container {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 16px 24px;
            display: inline-block;
            margin-bottom: 24px;
        }
        .id-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #15803d;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .id-val {
            font-size: 24px;
            font-weight: 700;
            color: #0f8546;
            margin: 0;
        }
        .divider {
            height: 1px;
            background-color: #f3f4f6;
            margin-bottom: 24px;
        }
        .help-text {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.5;
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
            <div class="icon-box">
                <span class="icon">✓</span>
            </div>
            <h2>Complaint Confirmed!</h2>
            <p class="intro-text">Thank you for contacting the Central Pollution Control Board. Your complaint has been received and forwarded to our concerned officer for review and resolution.</p>
            <div class="id-container">
                <div class="id-label">Complaint ID</div>
                <div class="id-val">ID–0{{ $result->id }}</div>
            </div>
            <div class="divider"></div>
            <p class="help-text">This is an automated acknowledgment email. Please quote your Complaint ID in all future communications regarding this submission.</p>
        </div>
    </div>
    <div class="brand-footer">
        © {{ date('Y') }} Central Pollution Control Board. All Rights Reserved.<br>
        Parivesh Bhawan, East Arjun Nagar, Delhi - 110032
    </div>
</body>
</html>