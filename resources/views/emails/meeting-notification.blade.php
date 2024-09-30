<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแจ้งเตือนการประชุม</title>
</head>
<body>
    <h1>การแจ้งเตือนการประชุม</h1>

    <p>เรียน {{ $booking->user->name }},</p>

    <p>คุณมีการประชุมในห้องประชุม: {{ $booking->meetingRoom->name }}</p>
    <p>วันที่: {{ \Carbon\Carbon::parse($booking->booking_start_date)->format('d/m/Y') }}</p>
    <p>เวลาเริ่มต้น: {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</p>
    <p>เวลาสิ้นสุด: {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>

    <p>ผู้เข้าร่วมประชุม:</p>
    <ul>
        @foreach ($booking->participants as $participant)
            <li>{{ $participant->user->name }}</li>
        @endforeach
    </ul>

    <p>ขอบคุณที่ใช้บริการระบบจองห้องประชุม</p>
</body>
</html>
