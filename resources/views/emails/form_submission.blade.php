<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Form Submission</title>
    </head>

    <body style="font-family: Arial, sans-serif; margin:0; padding:0;">

        @if (!empty($form->logo))
            <div style="text-align:center; margin-bottom:10px;">
                <img src="{{ asset('storage/' . $form->logo) }}" alt="{{ $form->title }}"
                    style="max-width:120px; height:auto;">
            </div>
        @endif


        {{-- Form Title --}}
        <h2 style="text-align:center; margin:0 0 20px 0;">
            {{ $form->title }}
        </h2>

        {{-- Admin only heading --}}
        @if ($recipientType === 'admin')
            <p style="text-align:center; font-weight:bold; margin-bottom:20px;">
                New Form Submission
            </p>
        @endif

        {{-- Submission Table --}}
        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
            @foreach ($fields as $label => $value)
                <tr>
                    <td style="border:1px solid #ddd; padding:8px; font-weight:bold; width:40%;">
                        {{ $label }}
                    </td>
                    <td style="border:1px solid #ddd; padding:8px;">
                        {{ $value }}
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
