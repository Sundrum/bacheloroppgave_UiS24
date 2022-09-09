{{-- This page is rendered in the email the user receives. --}}
{{-- Remember that the html needs to be "escaped": {!! $data !!} --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>{{$mailData['string']}}</h1>
    <p>This might need your immediate attention.</p>
    <p>Best regards</p>
    <p>7Sense Portal</p>
</body>
</html>