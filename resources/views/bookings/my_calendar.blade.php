@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="text-black">ตารางประชุมของฉัน</h1>
        <div id="calendar"></div>
    </div>
    <style>
        .fc-toolbar-title {
            color: black !important;
            font-weight: bold;
        }

        .fc-event-title {
            font-size: 9px;
            white-space: normal !important;

            word-wrap: break-word !important;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($bookings as $booking)
                        {
                            title: '{{ $booking->meetingRoom->name }} {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} น. - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} น.',
                            start: '{{ $booking->booking_start_date }}T{{ $booking->start_time }}',
                            end: '{{ $booking->booking_start_date }}T{{ $booking->end_time }}',
                            backgroundColor: '{{ $booking->status == 'approved' ? '#28a745' : '#ffc107' }}',
                        },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>
@endpush
