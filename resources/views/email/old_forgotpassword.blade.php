{{-- This page is rendered in the email the user receives. --}}
{{-- Remember that the html needs to be "escaped": {!! $data !!} --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Password Reset</h1>
    <p>You have requested to reset your 7Sense password. Please do so by pressing 
        this link:</p> <a href="https://portal.7sense.no/password/reset/{!! $token !!}">RESET PASSWORD</a>
    <p>If this was not you, please contact 7Sense.</p>
</body>
</html>