@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>รายละเอียดการจอง</h1>

        <p><strong>ชื่อห้องประชุม:</strong> {{ $booking->meetingRoom->name }}</p>
        <p><strong>วันที่:</strong> {{ $booking->booking_start_date }}</p>
        <p><strong>เวลา:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>

        <h3>ผู้เข้าร่วม:</h3>
        <ul>
            @foreach ($booking->participants as $participant)
                <li>{{ $participant->user->name }} - สถานะ: {{ $participant->status }}</li>
            @endforeach
        </ul>
    </div>
@endsection
