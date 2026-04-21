<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply from Hawi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #334155;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 32px 32px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .header p {
            margin: 8px 0 0;
            color: #94a3b8;
            font-size: 14px;
        }
        .body {
            padding: 32px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin: 24px 0 8px;
        }
        .message-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 14px;
            color: #475569;
            white-space: pre-wrap;
        }
        .reply-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 14px;
            color: #166534;
            white-space: pre-wrap;
        }
        .footer {
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
        }
        .footer p {
            margin: 0;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Hawi</h1>
            <p>We've replied to your message</p>
        </div>

        <div class="body">
            <p class="greeting">Hi {{ $contact->name }},</p>
            <p style="font-size: 14px; color: #475569;">
                Thank you for reaching out. Here is our response to your inquiry:
            </p>

            <p class="section-label">Your Message</p>
            <div class="message-box">{{ $contact->message }}</div>

            <p class="section-label">Our Reply</p>
            <div class="reply-box">{{ $contact->reply }}</div>

            <p style="font-size: 14px; color: #475569; margin-top: 24px;">
                If you have any further questions, feel free to reply to this email.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Hawi. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
