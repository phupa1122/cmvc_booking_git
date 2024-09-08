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
                        {{-- <div class="col-md-3 mb-3 mb-md-0">
                            <label for="booking_end_date" class="form-label">วันที่สิ้นสุด</label>
                            <input type="date" name="booking_end_date" id="booking_end_date" class="form-control"
                                required>
                        </div> --}}
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

                    {{-- p2 --}}

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
        $(document).ready(function() {
            var i = 0;
            var selectedParticipants = [];
            $('#bookingTable').DataTable({
                pageLength: 10,
                responsive: true,
                // language: {
                //     url: '//cdn.datatables.net/plug-ins/2.1.5/i18n/th.json'
                // },
            });

            // ปิดการใช้งานฟิลด์ตั้งแต่เริ่มต้น
            disableFormFields();

            // ตรวจสอบความพร้อมของห้องประชุม
            $('#check_availability').on('click', function() {
                var bookingStartDate = $('#booking_start_date').val();
                //var bookingEndDate = $('#booking_end_date').val();
                var startTime = $('#start_time').val();
                var endTime = $('#end_time').val();
                var meetingRoomId = $('#meeting_room_id').val();

                if (!bookingStartDate || !startTime || !endTime || !meetingRoomId) {
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                    return;
                }

                // ส่งข้อมูลไปตรวจสอบสถานะการจองด้วย AJAX
                $.ajax({
                    url: '{{ route('bookings.checkAvailability') }}', // เส้นทางสำหรับตรวจสอบสถานะ
                    method: 'GET',
                    data: {
                        meeting_room_id: meetingRoomId,
                        booking_start_date: bookingStartDate,
                        //booking_end_date: bookingEndDate,
                        start_time: startTime,
                        end_time: endTime,
                    },
                    success: function(response) {
                        // ตรวจสอบสถานะการจองจาก response
                        if (response.status === 'available') {
                            $('#availability_status').text('ว่าง').css('color', 'green');

                            // เปิดใช้งานฟิลด์ที่ถูกปิดไว้
                            enableFormFields();
                        } else {
                            $('#availability_status').text('ไม่ว่าง').css('color', 'red');

                            // ปิดการใช้งานฟิลด์ต่างๆ ถ้าห้องไม่ว่าง
                            disableFormFields();
                        }
                    },
                    error: function() {
                        $('#availability_status').text('เกิดข้อผิดพลาดในการตรวจสอบ').css(
                            'color', 'orange');
                    }
                });
            });

            // ปิดการใช้งานฟิลด์เมื่อมีการเปลี่ยนแปลงห้องประชุม, วันที่ หรือเวลา
            $('#meeting_room_id, #booking_start_date, #start_time, #end_time').on('change', function() {
                disableFormFields();
                $('#availability_status').text(''); // ล้างสถานะการจอง
            });

            // ฟังก์ชันปิดการใช้งานฟิลด์
            function disableFormFields() {
                $('#purpose').prop('disabled', true);
                $('input[name="equipments[]"]').prop('disabled', true);
                $('#participant_count').prop('disabled', true);
                $('#participant_fields').empty(); // ล้างช่องกรอกผู้เข้าร่วมประชุม
            }

            // ฟังก์ชันเปิดการใช้งานฟิลด์
            function enableFormFields() {
                $('#purpose').prop('disabled', false);
                $('input[name="equipments[]"]').prop('disabled', false);
                $('#participant_count').prop('disabled', false);
            }

            // เมื่อผู้ใช้กรอกจำนวนผู้เข้าร่วมประชุม
            $('#participant_count').on('change', function() {
                var count = $(this).val();
                $('#participant_fields').empty(); // ล้างข้อมูลเก่าออก
                selectedParticipants = [];

                for (var i = 0; i < count; i++) {
                    $('#participant_fields').append(`
                        <div class="mb-3 row" id="row_${i}">
                            <div class="col-md-10">
                                <label for="participants[${i}][name]" class="form-label">ผู้เข้าร่วมประชุมคนที่ ${i + 1}</label>
                                <input type="text" name="participants[${i}][name]" class="form-control participant-name" placeholder="กรอกชื่อ-นามสกุล" id="participant_${i}">
                                <input type="hidden" name="participants[${i}][id]" id="participant_id_${i}">
                                </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-participant" data-row-id="row_${i}">ลบ</button>
                            </div>
                        </div>
                    `);

                    // ใช้ AutoComplete สำหรับฟิลด์ที่เพิ่มใหม่
                    attachAutocomplete(`#participant_${i}`, `#participant_id_${i}`);
                }

                updateParticipantCount(); // อัปเดตจำนวนผู้เข้าร่วม
            });

            // ฟังก์ชัน AutoComplete
            function attachAutocomplete(inputSelector, hiddenFieldSelector) {
                $(inputSelector).autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: '{{ route('users.autocomplete') }}',
                            data: {
                                term: request.term,
                                booking_start_date: $('#booking_start_date').val(),
                                start_time: $('#start_time').val(),
                                end_time: $('#end_time').val(),
                            },
                            success: function(data) {
                                let results = [];

                                // ผู้ใช้ที่ยังว่างอยู่ (แสดงก่อน)
                                if (data.available_users.length > 0) {
                                    results.push({
                                        label: "ผู้ใช้ที่ว่าง",
                                        value: ""
                                    });
                                    $.each(data.available_users, function(index, user) {
                                        results.push({
                                            label: user.name,
                                            value: user.id,
                                            available: true
                                        });
                                    });
                                }

                                // ผู้ใช้ที่ถูกจองในสถานะ approved หรือ pending (แสดงด้วยสีแดง)
                                if (data.booked_users.length > 0) {
                                    results.push({
                                        label: "ผู้ใช้ที่ถูกจอง",
                                        value: ""
                                    });
                                    $.each(data.booked_users, function(index, user) {
                                        results.push({
                                            label: `${user.name} (มีประชุมที่ ${user.meeting_room}, ${user.start_time} - ${user.end_time})`,
                                            value: user.id,
                                            available: false // ไม่อนุญาตให้เลือกผู้ใช้นี้
                                        });
                                    });
                                }

                                response(results);
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        if (!ui.item.available) {
                            alert('ผู้เข้าร่วมประชุมนี้ถูกจองแล้วในสถานะ approved หรือ pending');
                            return false; // ป้องกันการเลือกผู้ใช้ที่ถูกจองแล้ว
                        }

                        $(inputSelector).val(ui.item.label); // แสดงชื่อผู้ใช้ที่เลือก
                        $(hiddenFieldSelector).val(ui.item.value); // เก็บ user_id ใน hidden input
                        return false;
                    }
                }).data('ui-autocomplete')._renderItem = function(ul, item) {
                    let content = item.available ? item.label : `<span style="color:red;">${item.label}</span>`;
                    return $('<li>')
                        .append(content)
                        .appendTo(ul);
                };
            }



            // ฟังก์ชันลบผู้เข้าร่วมประชุม
            $(document).on('click', '.remove-participant', function() {
                var rowId = $(this).data('row-id');
                $('#' + rowId).remove(); // ลบ row ที่ระบุ
                var userId = $(`#${rowId} input[type="hidden"]`).val(); // ดึง user_id ของผู้เข้าร่วมประชุม

                // ลบ user_id ออกจากรายการที่เลือก
                selectedParticipants = selectedParticipants.filter(function(id) {
                    return id !== userId;
                });

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


        });
    </script>
@endpush
