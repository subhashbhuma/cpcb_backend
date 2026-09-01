<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #dc3545; color: #ffffff; padding: 20px; text-align: center; }
        .header h2 { margin: 0; font-size: 20px; }
        .body { padding: 25px; }
        .alert-box { background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .alert-box p { margin: 0; color: #856404; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        table td:first-child { font-weight: bold; color: #555; width: 140px; }
        table td:last-child { color: #333; word-break: break-all; }
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>⚠️ Geo-Fence Security Alert</h2>
        </div>
        <div class="body">
            <div class="alert-box">
                <p>An unauthorized access attempt was detected from outside the allowed region.</p>
            </div>

            <table>
                <tr>
                    <td>IP Address</td>
                    <td>{{ $ipAddress }}</td>
                </tr>
                <tr>
                    <td>Country Code</td>
                    <td>{{ $country }}</td>
                </tr>
                <tr>
                    <td>Requested URL</td>
                    <td>{{ $uri }}</td>
                </tr>
                <tr>
                    <td>HTTP Method</td>
                    <td>{{ $method }}</td>
                </tr>
                <tr>
                    <td>User Agent</td>
                    <td>{{ $userAgent }}</td>
                </tr>
                <tr>
                    <td>Timestamp</td>
                    <td>{{ $attemptTime }}</td>
                </tr>
            </table>

            <p style="margin-top: 20px; font-size: 13px; color: #666;">
                This is an automated security alert from the CPCB Admin Panel. Please review the access logs for further details.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CPCB — Central Pollution Control Board. All rights reserved.
        </div>
    </div>
</body>
</html>
