<?php
function formatDateThai($date)
{
    $thai_months = [
        1 => 'มกราคม',
        2 => 'กุมภาพันธ์',
        3 => 'มีนาคม',
        4 => 'เมษายน',
        5 => 'พฤษภาคม',
        6 => 'มิถุนายน',
        7 => 'กรกฎาคม',
        8 => 'สิงหาคม',
        9 => 'กันยายน',
        10 => 'ตุลาคม',
        11 => 'พฤศจิกายน',
        12 => 'ธันวาคม',
    ];

    $day = \Carbon\Carbon::parse($date)->format('j');
    $month = $thai_months[\Carbon\Carbon::parse($date)->format('n')];
    $year = \Carbon\Carbon::parse($date)->format('Y') + 543;

    return "$day $month $year";
}
?>
<html>
<header>
    <title>รายงานการประชุม</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'sarabun_new', sans-serif;
            font-size: 20px;
        }

        .center-text {
            text-align: center;
        }

        .right-text {
            text-align: right;
        }

        .table-participants {
            margin-left: 50px;
            width: 100%;
        }

        .table-participants td {
            padding: 5px;
        }

        .name-column {
            margin-left: 40px;
            text-align: left;
        }

        .surname-column {
            text-align: left;
        }

        .indented {
            margin-left: 80px;
        }
    </style>
</header>

<body>
    <div class="center-text" style="line-height: 0.2;">
        <h4>รายงานการประชุม{{ $validated['meeting_title'] }}</h4>
        <h4>ครั้งที่ {{ $validated['meeting_number'] }}</h4>
        <h4>ในวัน {{ formatDateThai($booking->booking_start_date) }} เวลา
            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} น.</h4>
        <h4>ณ {{ $booking->meetingRoom->name }}</h4>
    </div>
    <hr>

    <h4>ผู้เข้าร่วมประชุม</h4>
    <p>จำนวน {{ $booking->participants->where('status', 'approved')->count() }} คน</p>
    <table class="table-participants">
        @php $counter = 1; @endphp <!-- เริ่มต้นลำดับผู้เข้าร่วม -->
        @foreach ($booking->participants->where('status', 'approved') as $participant)
            <tr>
                <!-- แสดงลำดับ -->
                <td class="name-column">{{ $counter++ }}. {{ explode(' ', $participant->user->name)[0] }}</td>
                <!-- ชื่อ -->
                <td class="surname-column">{{ explode(' ', $participant->user->name)[1] ?? '' }}</td> <!-- นามสกุล -->
            </tr>
        @endforeach
    </table>

    <h4>ผู้ไม่เข้าร่วมประชุม</h4>
    <p>จำนวน {{ $booking->participants->where('status', 'cancel')->count() }} คน</p>

    <table class="table-participants">
        @php $cancelCounter = 1; @endphp <!-- เริ่มต้นลำดับผู้ไม่เข้าร่วม -->
        @foreach ($booking->participants->where('status', 'cancel') as $participant)
            <tr>
                <td>{{ $cancelCounter++ }}.</td> <!-- แสดงลำดับ -->
                <td class="name-column">{{ $counter++ }}. {{ explode(' ', $participant->user->name)[0] }}</td>
                <!-- ชื่อ -->
                <td class="surname-column">{{ explode(' ', $participant->user->name)[1] ?? '' }}</td> <!-- นามสกุล -->
            </tr>
        @endforeach
    </table>

    <h4>เริ่มประชุมเวลา {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} น.</h4>
    <p style="margin-left: 50px;"> {{ $validated['first_paragraph'] }}</p>

    @for ($i = 0; $i < $validated['agenda_count']; $i++)
        <h4>ระเบียบวาระที่ {{ $i + 1 }} {{ $validated['agenda_titles'][$i] }}</h4>
        <p class="indented"> {{ $validated['agenda_contents'][$i] }}</p>
    @endfor

    <h4>ประธานที่ประชุม : ปิดการประชุม</h4>
    <h4>เลิกประชุม {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} น.</h4>

    @for ($i = 0; $i < $validated['sign_count']; $i++)
        <div class="right-text">
            <p>ลงชื่อ.....................................................</p>
            <p>{{ $validated['sign_names'][$i] }}</p>
            <p>{{ $validated['sign_positions'][$i] }}</p>
            <br>
        </div>
    @endfor
</body>

</html>
