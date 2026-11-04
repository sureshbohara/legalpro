<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Client Portal</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px; border-top: 4px solid #16a34a;">
        <h2 style="color: #16a34a; margin-top: 0;">Welcome to LegalPro Client Portal!</h2>
        <p>Dear <strong>{{ $clientName }}</strong>,</p>
        <p>Your law firm has granted you access to our secure Client Portal. You can now view your case updates, documents, and invoices online.</p>
        
        <div style="background: #fff; padding: 20px; border-radius: 6px; margin: 20px 0; border: 1px solid #e5e7eb;">
            <p style="margin: 10px 0;"><strong>🌐 Portal Link:</strong> <a href="{{ $portalUrl }}" style="color: #16a34a;">{{ $portalUrl }}</a></p>
            <p style="margin: 10px 0;"><strong>📧 Email:</strong> {{ $email }}</p>
            <p style="margin: 10px 0;"><strong>🔑 Temporary Password:</strong> <code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-size: 16px; font-weight: bold; color: #dc2626;">{{ $tempPassword }}</code></p>
        </div>

        <p style="color: #dc2626; font-weight: bold;">⚠️ Security Notice:</p>
        <p>For your security, please log in and change this temporary password immediately.</p>
        
        <p>Best Regards,<br><strong>LegalPro Firm Administration</strong></p>
    </div>
</body>
</html>