<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CPCB Alert: Delhi NCR AQI Upload Failure</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Segoe UI', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #171717;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06); overflow: hidden; border: 1px solid #e5e5e5;">
        <!-- Header -->
        <tr>
            <td style="background-color: #171717; padding: 24px 30px; text-align: center; border-bottom: 3px solid #ef4444;">
                <h2 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px;">Delhi NCR AQI Upload Alert</h2>
            </td>
        </tr>
        
        <!-- Body -->
        <tr>
            <td style="padding: 35px 30px;">
                <p style="font-size: 16px; margin-top: 0; margin-bottom: 20px; font-weight: 500;">Attention Administrator,</p>
                <p style="font-size: 15px; line-height: 1.6; margin-bottom: 25px; color: #404040;">
                    The automated service was interrupted while attempting to process a Delhi NCR AQI file upload. Please review the specific file and error details below:
                </p>
                
                <!-- File Details -->
                <div style="background-color: #fafafa; border-left: 4px solid #171717; padding: 18px 20px; border-radius: 0 6px 6px 0; margin-bottom: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: #171717; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">File Uploaded</h4>
                    <p style="margin: 0; font-size: 15px; font-family: 'Courier New', Courier, monospace; background-color: #ffffff; padding: 8px 12px; border-radius: 4px; color: #171717; word-break: break-all; border: 1px solid #e5e5e5;">
                        {{ $fileName ?? 'Unknown File' }}
                    </p>
                </div>

                <!-- Error Box -->
                <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 18px 20px; border-radius: 0 6px 6px 0; margin-bottom: 30px;">
                    <h4 style="margin: 0 0 10px 0; color: #ef4444; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Error Message</h4>
                    <p style="margin: 0; font-size: 15px; font-family: 'Courier New', Courier, monospace; background-color: #ffffff; padding: 8px 12px; border-radius: 4px; color: #b91c1c; word-break: break-all; border: 1px solid #fecaca;">
                        {{ $errorMessage }}
                    </p>
                </div>

                <div style="background-color: #fafafa; border: 1px solid #e5e5e5; border-radius: 6px; padding: 15px;">
                    <p style="font-size: 14px; color: #525252; margin: 0; line-height: 1.5;">
                        <strong style="color: #171717;">Resolution:</strong> If correcting this issue manually, please ensure that the filename precisely adheres to the format: <span style="font-family: monospace; background: #e5e5e5; padding: 2px 6px; border-radius: 3px; color: #171717;">NCR_AQI_Bulletin_YYYY_MM_DD.pdf</span>
                    </p>
                </div>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td style="background-color: #f5f5f5; border-top: 1px solid #e5e5e5; padding: 20px 30px;">
                <table width="100%">
                    <tr>
                        <td align="center">
                            <p style="margin: 0; font-size: 12px; color: #737373; line-height: 1.6;">
                                This is an automated diagnostic message from the <br>
                                <strong style="color: #404040;">Central Pollution Control Board (CPCB)</strong> System <br>
                                Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
