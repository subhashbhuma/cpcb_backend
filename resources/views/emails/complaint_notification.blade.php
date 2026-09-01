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
            margin: 0 0 8px 0;
            font-size: 18px;
            color: #111827;
            font-weight: 700;
        }
        .ticket-badge {
            display: inline-block;
            background-color: #f3f4f6;
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 4px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .details-table th, .details-table td {
            text-align: left;
            padding: 10px 12px;
            font-size: 14px;
            border-bottom: 1px solid #f3f4f6;
        }
        .details-table th {
            color: #6b7280;
            font-weight: 500;
            width: 30%;
        }
        .details-table td {
            color: #1f2937;
            font-weight: 600;
        }
        .message-box {
            background-color: #f9fafb;
            border-left: 4px solid #0f8546;
            padding: 16px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 24px;
        }
        .message-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .message-content {
            font-size: 14px;
            color: #374151;
            line-height: 1.6;
            white-space: pre-line;
        }
        .attachment-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
        .attachment-icon {
            font-size: 20px;
            margin-right: 12px;
        }
        .attachment-text {
            font-size: 13px;
            color: #1e3a8a;
            font-weight: 500;
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
            <h2>New Public Air Complaint Submission</h2>
            <div class="ticket-badge">Complaint ID: ID–{{ $complaint->id }}</div>
            
            <table class="details-table">
                <tr>
                    <th>Complainant Name</th>
                    <td>{{ $complaint->full_name }}</td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><a href="mailto:{{ $complaint->email }}" style="color: #0f8546; text-decoration: none;">{{ $complaint->email ?? 'N/A' }}</a></td>
                </tr>
                <tr>
                    <th>Mobile Number</th>
                    <td>{{ $complaint->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Location / Address</th>
                    <td>{{ $complaint->location ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Subject Area</th>
                    <td>{{ $subjectData->title ?? 'N/A' }}</td>
                </tr>
            </table>

            <div class="message-box">
                <div class="message-title">Complaint Message</div>
                <div class="message-content">{{ $complaint->message }}</div>
            </div>

            @if($complaint->file_name)
                <div class="attachment-box">
                    <span class="attachment-icon">📎</span>
                    <span class="attachment-text">An attachment was uploaded with this complaint. Please review it in the admin dashboard.</span>
                </div>
            @endif

            <div class="divider"></div>
            <p class="help-text">This is an automated administrative notification. Please log in to the CPCB Admin Portal to take necessary action.</p>
        </div>
    </div>
    <div class="brand-footer">
        © {{ date('Y') }} Central Pollution Control Board. All Rights Reserved.<br>
        Parivesh Bhawan, East Arjun Nagar, Delhi - 110032
    </div>
</body>
</html>