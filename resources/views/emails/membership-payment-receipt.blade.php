
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MemberHub Payment Receipt</title>
</head>

<body style="margin:0;padding:25px;background:#f7f5ff;
             font-family:Arial,Helvetica,sans-serif;color:#30236b;">

    @php
        $transaction = $payment->membershipTransaction;
        $member = $transaction->member;
    @endphp

    <div style="max-width:600px;margin:0 auto;
                background:white;padding:30px;
                border:1px solid #e6dfff;border-radius:12px;">

        <h2 style="color:#30236b;margin-top:0;">
            MemberHub
        </h2>

        <p style="color:#74649d;">
            Membership Management System
        </p>

        <hr style="border:0;border-top:1px solid #e6dfff;">

        <h2>Membership Payment Receipt</h2>

        <p>Hello, {{ $member->name }}!</p>

        <p>
            Your membership payment has been recorded
            successfully. Below are your payment details.
        </p>

        <table style="width:100%;border-collapse:collapse;">
            <tbody>
                <tr>
                    <td style="padding:12px;background:#f7f5ff;">
                        Payment Reference
                    </td>
                    <td style="padding:12px;">
                        {{ $payment->payment_reference }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px;background:#f7f5ff;">
                        Transaction Reference
                    </td>
                    <td style="padding:12px;">
                        {{ $transaction->reference_number }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px;background:#f7f5ff;">
                        Member Name
                    </td>
                    <td style="padding:12px;">
                        {{ $member->name }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px;background:#f7f5ff;">
                        Amount Paid
                    </td>
                    <td style="padding:12px;">
                        &#8369;{{ number_format($payment->amount, 2) }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px;background:#f7f5ff;">
                        Payment Method
                    </td>
                    <td style="padding:12px;">
                        {{ $payment->payment_method }}
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px;background:#f7f5ff;">
                        Payment Date
                    </td>
                    <td style="padding:12px;">
                        {{ $payment->payment_date->format('F d, Y') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top:25px;color:#74649d;font-size:13px;">
            This receipt summarizes a recorded membership payment.
            It does not replace an official invoice or tax receipt.
        </p>

        <p>Thank you for using MemberHub!</p>

    </div>

</body>
</html>
