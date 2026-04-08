{{-- resources/views/emails/forgot_password.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Password Reset Request') }}</title>
</head>
<body>
    <h1>{{ __('Password Reset Request') }}</h1>
    <p>Hi,</p>
    <p>You requested a password reset. Click the link below to reset your password:</p>
    <a href="{{ url('reset_password?token=' . $token . '&email=' . $email) }}">{{ __('Reset Password') }}</a>
    <p>If you did not request this, please ignore this email.</p>
    <p>Thanks!</p>
</body>
</html>
