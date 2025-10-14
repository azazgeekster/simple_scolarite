<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Activate Your Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <p>Dear {{ $student->nom }},</p>

    <p>You requested to activate your student account. Please click the link below to set your password and activate
        your account:</p>

    <p>

        <a class="btn btn-primary"
            href="{{ route('student.activate.setpassword', ['token' => $student->activation_token]) }}">
            Activate</a>
    </p>

    <p>If button does not work, please past this link in your browser: <span> {{ route('student.activate.setpassword', ['token' => $student->activation_token])  }}</span></p>

    <p>If you did not request this, please ignore this email.</p>

    <p>Best regards,</p>
    <p>Faculty of Applied Sciences - Ait Melloul</p>
</body>

</html>
