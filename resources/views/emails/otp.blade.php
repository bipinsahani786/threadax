<x-mail::message>
# Your Login OTP

Use the code below to login to your ThreadAx account. This code is valid for 5 minutes.

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

If you did not request this OTP, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
