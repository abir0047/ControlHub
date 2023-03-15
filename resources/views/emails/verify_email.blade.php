@component('mail::message')
    # Your new account credentials is below.

    E-mail: {{ $sendMail }}
    Password: {{ $password }}

    Thanks,
    LF Exam Support
@endcomponent
