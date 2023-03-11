@component('mail::message')
    Your password has been reset. Below is your new login credentials.

    E-mail: {{ $sendMail }}
    Password: {{ $newPassword }}

    Thanks,
    LF Exam Support
@endcomponent
