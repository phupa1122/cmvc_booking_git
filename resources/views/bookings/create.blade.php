@extends('layouts.app')

@section('title', 'Booking CMVC')

@section('content')
    <div class="container py-5">

        <h1>จองห้องประชุม</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button onclick="showAlert()">Click me</button>
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-5">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="meeting_room_id" class="form-label">ห้องประชุม:</label>
                            <select name="meeting_room_id" id="meeting_room_id" class="form-select" required>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} (ความจุ: {{ $room->capacity }}
                                        ที่นั่ง)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="booking_start_date" class="form-label">วันที่เริ่มต้น</label>
                            <input type="date" name="booking_start_date" id="booking_start_date" class="form-control"
                                required>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="booking_end_date" class="form-label">วันที่สิ้นสุด</label>
                            <input type="date" name="booking_end_date" id="booking_end_date" class="form-control"
                                required>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="start_time" class="form-label">เวลาเริ่มต้น:</label>
                            <input type="time" name="start_time" id="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="end_time" class="form-label">เวลาสิ้นสุด:</label>
                            <input type="time" name="end_time" id="end_time" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <button type="button" id="check_availability" class="btn btn-info">ตรวจสอบว่างหรือไม่</button>
                            <span id="availability_status" class="ms-3"></span> <!-- แสดงสถานะการจอง -->
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="purpose" class="form-label">จุดประสงค์ในการประชุม:</label>
                        <textarea name="purpose" id="purpose" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">อุปกรณ์ที่ต้องการ:</label>
                        <div class="row">
                            @foreach ($equipment as $item)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="equipments[]"
                                            value="{{ $item->id }}" id="equipment_{{ $item->id }}">
                                        <label class="form-check-label"
                                            for="equipment_{{ $item->id }}">{{ $item->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 mb-4">
                        <label for="participant_count" class="form-label">จำนวนผู้เข้าร่วมประชุม:</label>
                        <input type="number" name="participant_count" id="participant_count" class="form-control"
                            min="1" placeholder="กรอกจำนวนผู้เข้าร่วมประชุม">
                    </div>

                    <div class="mt-4" id="participant_fields">
                        <!-- ช่องกรอกผู้เข้าร่วมประชุมจะถูกเพิ่มที่นี่ -->
                    </div>

                    <div class="mt-4 ">
                        <label class="form-label">ผู้เข้าร่วมประชุม:</label>
                        <table class="table table-borderd" id="table">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td><input type="text" name="participants[0][name]"
                                        placeholder="กรอก ชื่อ-นามสกุล ผู้เข้าร่วม" class="form-control"></td>
                                <td><button type="button" name="add" id="add" class="btn btn-success"> เพิ่ม
                                    </button></td>
                            </tr>
                        </table>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">ยืนยันการจอง</button>
                    </div>
                </div>
            </div>
        </form>

        <h1 class="mt-5">รายการจองห้องประชุม</h1>
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="bookingTable">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>ชื่อห้องประชุม</th>
                                <th>วันที่</th>
                                {{-- <th>วันที่สิ้นสุด</th> --}}
                                <th>เวลาเริ่มต้น</th>
                                <th>เวลาสิ้นสุด</th>
                                {{-- <th>จุดประสงค์</th> --}}
                                <th>ผู้จอง</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td class="text-center">{{ $booking->meetingRoom->name }}</td>
                                    <td class="text-center">{{ $booking->booking_start_date }}</td>
                                    {{-- <td class="text-center">{{ $booking->booking_end_date }}</td> --}}
                                    <td class="text-center">{{ $booking->start_time }}</td>
                                    <td class="text-center">{{ $booking->end_time }}</td>
                                    {{-- <td>{{ $booking->purpose }}</td> --}}
                                    <td class="text-center">{{ $booking->user->name }}</td>
                                    <td class="text-center"><span
                                            class="badge bg-{{ $booking->status == 'approved' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }}">{{ $booking->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        var i = 0;
        $(document).ready(function() {
            $('#bookingTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/2.1.5/i18n/th.json'
                },
            });

            $('#participant_count').on('change', function() {
                var count = $(this).val();
                $('#participant_fields').empty(); // ล้างข้อมูลเก่าออก
                for (var i = 0; i < count; i++) {
                    $('#participant_fields').append(`
                        <div class="mb-3 row" id="row_${i}">
                            <div class="col-md-10">
                                <label for="participants[${i}][name]" class="form-label">ผู้เข้าร่วมประชุมคนที่ ${i + 1}</label>
                                <input type="text" name="participants[${i}][name]" class="form-control" placeholder="กรอกชื่อ-นามสกุล">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-participant" data-row-id="row_${i}">ลบ</button>
                            </div>
                        </div>
                    `);
                }
                updateParticipantCount(); // อัปเดตจำนวนผู้เข้าร่วม
            });

            // ฟังก์ชันลบผู้เข้าร่วมประชุม
            $(document).on('click', '.remove-participant', function() {
                var rowId = $(this).data('row-id');
                $('#' + rowId).remove(); // ลบ row ที่ระบุ
                updateParticipantCount(); // อัปเดตจำนวนผู้เข้าร่วม
            });

            // ฟังก์ชันอัปเดตจำนวนผู้เข้าร่วมประชุม
            function updateParticipantCount() {
                var rowCount = $('#participant_fields .row').length; // นับจำนวนแถวที่เหลือ
                $('#participant_count').val(rowCount); // อัปเดตค่าของ input จำนวนผู้เข้าร่วมประชุม
            }

            // ฟังก์ชันเรียงลำดับใหม่และคงข้อมูลเดิมไว้
            function reindexRows() {
                $('#participant_fields .row').each(function(index) {
                    // อัปเดต label
                    $(this).find('label').text('ชื่อผู้เข้าร่วมประชุม ' + (index + 1) + ':');

                    // อัปเดต name และ id ของ input fields
                    $(this).find('input').attr('name', 'participants[' + index + '][name]');
                    $(this).attr('id', 'row_' + index);
                });
            }

            // เมื่อผู้ใช้กดปุ่ม "ตรวจสอบว่างหรือไม่"
            $('#check_availability').on('click', function() {
                var bookingStartDate = $('#booking_start_date').val();
                var bookingEndDate = $('#booking_end_date').val();
                var startTime = $('#start_time').val();
                var endTime = $('#end_time').val();
                var meetingRoomId = $('#meeting_room_id').val();

                if (!bookingStartDate || !bookingEndDate || !startTime || !endTime || !meetingRoomId) {
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                    return;
                }

                // ส่งข้อมูลไปตรวจสอบสถานะการจองด้วย AJAX
                $.ajax({
                    url: '{{ route("bookings.checkAvailability") }}', // เส้นทางสำหรับตรวจสอบสถานะ
                    method: 'GET',
                    data: {
                        meeting_room_id: meetingRoomId,
                        booking_start_date: bookingStartDate,
                        booking_end_date: bookingEndDate,
                        start_time: startTime,
                        end_time: endTime,
                    },
                    success: function(response) {
                        // ตรวจสอบสถานะการจองจาก response
                        if (response.status === 'available') {
                            $('#availability_status').text('ว่าง').css('color', 'green');
                        } else {
                            $('#availability_status').text('ไม่ว่าง').css('color', 'red');
                        }
                    },
                    error: function() {
                        $('#availability_status').text('เกิดข้อผิดพลาดในการตรวจสอบ').css('color', 'orange');
                    }
                });
            });
        });


        // $('#add').click(function() {
        //     ++i;
        //     $('#table').append(
        //         `<tr>
        //             <td>
        //                 <input type="text" name="participants[` + i + `][name]" placeholder="กรอก ชื่อ-นามสกุล ผู้เข้าร่วม" class="form-control">
        //             </td>
        //             <td>
        //                 <button type="button" class="btn btn-danger remove-table-row">ลบ</button>
        //             </td>
                
        //         </tr>`);

        // });

        // $(document).on('click', '.remove-table-row', function() {
        //     $(this).parents('tr').remove();
        // });

        // function showAlert() {
        //     alert("You clicked the button!");
        // }
    </script>
@endpush

{{-- <div class="form-group">
            <label for="participants">ผู้เข้าร่วมประชุม:</label>
            <select name="participants[]" id="participants" class="form-control" multiple>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div> --}}


{{-- //v2<div class="row">
            @foreach ($users as $user)
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="participants[]"
                            value="{{ $user->id }}" id="participant_{{ $user->id }}">
                        <label class="form-check-label"
                            for="participant_{{ $user->id }}">{{ $user->name }}</label>
                    </div>
                </div>
            @endforeach
        </div> --}}
