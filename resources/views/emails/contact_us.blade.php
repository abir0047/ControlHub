@component('mail::message')
    # Contact Us LF Exam

    Subject: {{ $subject }}
    <br>
    Qustions: {{ $query }}
    <br>
    User E-mail: {{ $userEmail }}

    Thanks You.
@endcomponent
