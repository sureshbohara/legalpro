<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hearing Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; border-top: 4px solid #16a34a;">
        <h2 style="color: #16a34a; margin-top: 0;">⚖️ Court Hearing Reminder</h2>
        <p>Dear <strong>{{ $hearing->case->client->name ?? 'Client' }}</strong>,</p>
        <p>This is a reminder regarding your upcoming court hearing.</p>
        
        <div style="background: #fff; padding: 15px; border-radius: 6px; margin: 20px 0; border: 1px solid #e5e7eb;">
            <p style="margin: 5px 0;"><strong>Case Number:</strong> {{ $hearing->case->case_number }}</p>
            <p style="margin: 5px 0;"><strong>Case Title:</strong> {{ $hearing->case->title }}</p>
            <p style="margin: 5px 0;"><strong>Hearing Date:</strong> {{ $hearing->hearing_date->format('d M Y') }}</p>
            <p style="margin: 5px 0;"><strong>Time:</strong> {{ $hearing->hearing_time ?? 'TBA' }}</p>
            <p style="margin: 5px 0;"><strong>Court:</strong> {{ $hearing->court_name }}</p>
            @if($hearing->judge_name)
            <p style="margin: 5px 0;"><strong>Judge:</strong> {{ $hearing->judge_name }}</p>
            @endif
        </div>

        <p>Please ensure all necessary documents are prepared and reach the court on time.</p>
        <p>Best Regards,<br><strong>LegalPro Firm</strong></p>
    </div>
</body>
</html>