@component('mail::message')

<h2>Hello {{ $user->first_name }},</h2>

<p>A recent password change was made to your account.</p>

<p>Your new password is: <strong>{{ $password }}</strong></p>

<p>
    If you haven't requested this password change, please ignore.
</p>

<p>
    Best regards,<br>
    {{ config('app.name') }}
</p>

@endcomponent
