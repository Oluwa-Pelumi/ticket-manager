<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Notification</title>
    <style>
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 40px 20px;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6 -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 16px;
            color: #1e293b;
        }
        .intro {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
            color: #475569;
        }
        .details-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        .details-row {
            border-bottom: 1px solid #e2e8f0;
        }
        .details-row:last-child {
            border-bottom: none;
        }
        .details-label {
            padding: 16px 20px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #f8fafc;
            width: 40%;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }
        .details-value {
            padding: 16px 20px;
            font-size: 15px;
            color: #1e293b;
            font-weight: 500;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-row:last-child .details-label,
        .details-row:last-child .details-value {
            border-bottom: none;
        }
        .footer {
            padding: 30px;
            background-color: #f8fafc;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 0;
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 9999px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 4px 6 -1px rgba(79, 70, 229, 0.2), 0 2px 4px -2px rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
        }
        .message-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .message-box p {
            margin: 0;
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $subject }}</h1>
            <p>Ticket Notification</p>
        </div>

        <div class="content">
            <h2 class="greeting">Hello {{ $recipientName }},</h2>
            <p class="intro">{{ $notificationMessage }}</p>
            
            @if($ticketUrl)
            <div class="btn-container">
                <a href="{{ $ticketUrl }}" class="btn">View Ticket</a>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="margin-top: 5px; font-size: 11px; color: #cbd5e1;">This is an automated notification regarding your support ticket.</p>
        </div>
    </div>
</body>
</html>
