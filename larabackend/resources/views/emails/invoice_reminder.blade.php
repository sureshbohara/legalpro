<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; border-top: 4px solid #f59e0b;">
        <h2 style="color: #f59e0b; margin-top: 0;">💰 Invoice Payment Reminder</h2>
        <p>Dear <strong>{{ $invoice->client->name }}</strong>,</p>
        <p>We hope this email finds you well. This is a gentle reminder regarding the following invoice:</p>
        
        <div style="background: #fff; padding: 15px; border-radius: 6px; margin: 20px 0; border: 1px solid #e5e7eb;">
            <p style="margin: 5px 0;"><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
            <p style="margin: 5px 0;"><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}</p>
            <p style="margin: 5px 0;"><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
            <p style="margin: 5px 0; font-size: 18px; color: #16a34a;"><strong>Total Amount: NPR {{ number_format($invoice->total_amount, 2) }}</strong></p>
        </div>

        <p>Please arrange for the payment at your earliest convenience to avoid any late fees.</p>
        <p>If you have already made the payment, please ignore this reminder.</p>
        <p>Best Regards,<br><strong>LegalPro Firm</strong></p>
    </div>
</body>
</html>