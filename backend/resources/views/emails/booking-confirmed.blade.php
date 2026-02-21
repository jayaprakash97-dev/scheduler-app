<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
</head>
<body>
    <h2>Booking Confirmed!</h2>
    <p>Hi {{ $bookingData['name'] }},</p>
    <p>Your booking has been confirmed for <strong>{{ $bookingData['date'] }}</strong> at <strong>{{ $bookingData['start_time'] }} - {{ $bookingData['end_time'] }}</strong>.</p>
    <p>Thank you for using Scheduler App!</p>
</body>
</html>