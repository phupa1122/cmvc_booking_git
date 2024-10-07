@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header">
                <h1>รายงานการประชุม</h1>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <style>
        .fc-toolbar-title {
            color: black !important;
            font-weight: bold;
        }

        .fc-event-title {
            font-size: 12px;
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
                locale: 'th', // ตั้งค่าภาษาไทย
                events: [
                    @foreach ($bookings as $booking)
                        {
                            myid: '{{ $booking->id }}',
                            title: '{{ $booking->meetingRoom->name }}',
                            start: '{{ $booking->booking_start_date }}T{{ $booking->start_time }}',
                            end: '{{ $booking->booking_start_date }}T{{ $booking->end_time }}',
                            backgroundColor: '{{ $booking->status == 'approved' ? '#28a745' : '#ffc107' }}',
                            extendedProps: {
                                timeText: '{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} น. - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} น.'
                            }
                        },
                    @endforeach
                ],
                eventClick: function(info) {

                    // เรียกใช้ AJAX เมื่อคลิกที่ event
                    $.ajax({
                        url: '/booking/details/' + info.event._def.extendedProps.myid,
                        method: 'GET',
                        success: function(response) {
                            // แสดงข้อมูลที่ดึงมาใน modal
                            $('#meetingRoomName').text(response.meetingRoom.name);
                            $('#meetingDate').text(response.booking.booking_start_date);
                            $('#meetingTime').text(response.booking.start_time + ' - ' +
                                response.booking.end_time);
                            $('#meetingPurpose').text(response.purpose);

                            // อุปกรณ์
                            $('#meetingEquipments').empty();
                            response.equipments.forEach(function(equipment) {
                                $('#meetingEquipments').append('<li>' + equipment +
                                    '</li>');
                            });

                            // ผู้เข้าร่วมประชุม
                            $('#meetingParticipants').empty();
                            response.participants.forEach(function(participant) {
                                var statusClass = participant.status ===
                                    'approved' ? 'text-success' : (participant
                                        .status === 'pending' ? 'text-warning' :
                                        'text-danger');
                                $('#meetingParticipants').append('<li>' +
                                    participant.name + ' - <span class="' +
                                    statusClass + '">' + participant.status +
                                    '</span></li>');
                            });

                            // แสดง Modal
                            $('#meetingDetailsModal').modal('show');
                        },
                        error: function(xhr) {
                            alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
                        }
                    });
                },
                eventTimeFormat: { // ตั้งค่าการแสดงเวลาของ FullCalendar ให้เป็น 24 ชั่วโมง
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false // ใช้ 24 ชั่วโมง
                }
            });

            calendar.render();
        });
    </script>
    <!-- Modal แสดงรายละเอียดการประชุม -->
    <div class="modal fade" id="meetingDetailsModal" tabindex="-1" aria-labelledby="meetingDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="meetingDetailsModalLabel">รายละเอียดการประชุม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ห้องประชุม:</strong> <span id="meetingRoomName"></span></p>
                    <p><strong>วันที่:</strong> <span id="meetingDate"></span></p>
                    <p><strong>เวลา:</strong> <span id="meetingTime"></span></p>
                    <p><strong>วัตถุประสงค์:</strong> <span id="meetingPurpose"></span></p>
                    <p><strong>อุปกรณ์:</strong></p>
                    <ul id="meetingEquipments"></ul>
                    <p><strong>ผู้เข้าร่วมประชุม:</strong></p>
                    <ul id="meetingParticipants"></ul>
                </div>
            </div>
        </div>
    </div>
@endpush
