<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแจ้งเตือนการประชุม</title>
</head>
<body>
    <h2 style="color: #6b238e;">การแจ้งเตือนการประชุม</h2>

    <p>เรียน คุณ {{ $recipientName }},</p>


    <p>คุณมีการประชุมในห้องประชุม: {{ $booking->meetingRoom->name }}</p>
    <p>วันที่: {{ \Carbon\Carbon::parse($booking->booking_start_date)->format('d/m/Y') }}</p>
    <p>เวลาเริ่มต้น: {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</p>
    <p>เวลาสิ้นสุด: {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>

    <h4>ผู้เข้าร่วมประชุม:</h4>
    <ul>
        @foreach ($booking->participants as $participant)
            <li>{{ $participant->user->name }}</li>
        @endforeach
    </ul>

    <a href="http://127.0.0.1:8000/login"></a>
</body>
</html>
