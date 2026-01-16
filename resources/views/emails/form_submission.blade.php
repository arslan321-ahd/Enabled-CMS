<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Form Submission</title>
    </head>

    <body style="font-family: Arial, sans-serif;">

        @if ($recipientType === 'admin')
            <h2>New Form Submission</h2>
        @endif
        <p><strong>Form:</strong> {{ $form->title }}</p>
        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
            @foreach ($fields as $label => $value)
                <tr>
                    <td style="border:1px solid #ddd; padding:8px; font-weight:bold; width:40%;">
                        {{ $label }}
                    </td>
                    <td style="border:1px solid #ddd; padding:8px;">
                        {{ $value }} {{-- raw ID / 1/0 here --}}
                    </td>
                </tr>
            @endforeach
        </table>
        <br>
        Regards,<br>
        <strong>EnabledCMS</strong><br>
        © {{ date('Y') }} EnabledCMS
    </body>

</html>
