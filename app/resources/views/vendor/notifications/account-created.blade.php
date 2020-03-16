@component('mail::message')

<h2>Hello {{ $user->first_name }},</h2>

<p>Thank you for joining us on <strong>{{ config('app.name') }}</strong>!</p>

<p>
    Your new account credentials are:<br>
    <strong>Email:</strong> {{ $user->email }}<br>
    <strong>Password:</strong> {{ $password }}
</p>

<p>
    Before logging in, please follow this link in order to verify your email address:
    <a href="{{ $user->getVerificationURL() }}">{{ $user->getVerificationURL() }}</a>
</p>

<p>
    After verification, you will be able to sign into your account using the login page:
    <a href="{{ route('login') }}">{{ route('login') }}</a>
</p>

<p>
    Best regards,<br>
    {{ config('app.name') }}
</p>

@endcomponent
