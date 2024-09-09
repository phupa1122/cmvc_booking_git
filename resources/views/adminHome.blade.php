@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('ยินดีต้อนรับ Admin') }}
                </div>
            </div>

            <a href="{{route('bookings.create')}}" class="btn btn-primary mt-2">จอง</a>
            <a href="/meeting-rooms" class="btn btn-primary mt-2">ห้องประชุมทั้งหมด</a>
            <a href="{{route('feedback.index')}}" class="btn btn-primary mt-2">แสดงข้อเสนอแนะ</a>

            <!-- แสดงการจองที่ต้องอนุมัติ -->
            @if($pendingBookings->isNotEmpty())
                <h4 class="mt-4">การจองที่ต้องอนุมัติ</h4>
                <table class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>ชื่อห้องประชุม</th>
                            <th>วันที่</th>
                            <th>เวลา</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBookings as $booking)
                            <tr>
                                <td>{{ $booking->meetingRoom->name }}</td>
                                <td>{{ $booking->booking_start_date }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>
                                    <form action="{{ route('booking.respond', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" name="response" value="approve" class="btn btn-success btn-sm">อนุมัติ</button>
                                        <button type="submit" name="response" value="cancel" class="btn btn-danger btn-sm">ไม่อนุมัติ</button>
                                    </form>
                                    <button class="btn btn-info btn-sm" onclick="showBookingDetails({{ $booking->id }})">รายละเอียดการจอง</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="mt-4">ไม่มีการจองที่รอการอนุมัติ</p>
            @endif
        </div>
    </div>
</div>

<!-- Modal สำหรับแสดงรายละเอียดการจอง -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsModalLabel">รายละเอียดการจอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ชื่อห้องประชุม:</strong> <span id="roomName"></span></p>
                <p><strong>วันที่:</strong> <span id="bookingDate"></span></p>
                <p><strong>เวลา:</strong> <span id="bookingTime"></span></p>
                <p><strong>ผู้เข้าร่วม:</strong></p>
                <ul id="participantsList"></ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showBookingDetails(bookingId) {
        // ส่ง AJAX ไปดึงข้อมูลการจอง
        $.ajax({
            url: '{{ url("booking/details/ajax") }}/' + bookingId,
            method: 'GET',
            success: function(response) {
                // แสดงข้อมูลการจองใน Modal
                $('#roomName').text(response.meetingRoom.name);
                $('#bookingDate').text(response.booking.booking_start_date);
                $('#bookingTime').text(response.booking.start_time + ' - ' + response.booking.end_time);

                // แสดงรายชื่อผู้เข้าร่วม
                $('#participantsList').empty();
                response.participants.forEach(function(participant) {
                    $('#participantsList').append('<li>' + participant + '</li>');
                });

                // เปิด Modal
                $('#bookingDetailsModal').modal('show');
            }
        });
    }
</script>
@endpush
