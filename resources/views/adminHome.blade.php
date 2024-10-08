@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white">Dashboard</div>

                    <div class="card-body">
                        <h4 class="mt-2">สถิติการใช้งานห้องประชุม</h4>
                        <form method="GET" action="{{ route('admin.home') }}">
                            <label for="year">เลือกปี:</label>
                            <select name="year" id="year" class="form-control">
                                @for ($i = \Carbon\Carbon::now()->year; $i >= \Carbon\Carbon::now()->year - 5; $i--)
                                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">ดูสถิติ</button>
                        </form>

                        <div class="row justify-content-center">
                            {{-- Bar Chart: จำนวนการจองห้องประชุมทั้งหมดแต่ละเดือน --}}
                            <div class="col-md-6">
                                <canvas id="monthlyBookingChart"></canvas>
                            </div>

                            {{-- Horizontal Bar Chart: จำนวนการจองตามห้องประชุม --}}
                            <div class="col-md-6">
                                <canvas id="roomUsageChart"></canvas>
                            </div>
                        </div>
                        <hr>
                        <h4 class="mt-3">สถิติการใช้งานของผู้ใช้</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card" style="background-color: #A02334; color: white;">
                                    <div class="card-body text-center">
                                        <h3>{{ $userCount }}</h3>
                                        <p>จำนวนผู้ใช้งานทั้งหมด</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Table: ผู้ใช้ที่จองห้องประชุมมากที่สุด --}}
                            <div class="col-md-8">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ชื่อผู้ใช้</th>
                                            <th>จำนวนการจอง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topUsers as $user)
                                            <tr>
                                                <td>{{ $user->user->name }}</td>
                                                <td>{{ $user->total_bookings }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-center">
                            {{-- Pie Chart: การตอบรับของผู้เข้าร่วมประชุม --}}
                            <div class="col-md-6 ">
                                <canvas id="participantResponseChart"></canvas>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- แสดงการจองที่ต้องอนุมัติ -->
                @if ($pendingBookings->isNotEmpty())
                    <div class="card mt-5 shadow-lg border-0 rounded-lg">
                        <div class="card-header">
                            <h2>การจองที่ต้องอนุมัติ</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mt-2" id="approveTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th>ชื่อห้องประชุม</th>
                                            <th>วันที่</th>
                                            <th>เวลา</th>
                                            <th>การจัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingBookings as $booking)
                                            <tr>
                                                <td>{{ $booking->meetingRoom->name }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($booking->booking_start_date)->format('d/m/Y') }}
                                                </td>
                                                <td> {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('booking.respond', $booking->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" name="response" value="approve"
                                                            class="btn btn-success btn-sm">อนุมัติ</button>
                                                        <button type="submit" name="response" value="cancel"
                                                            class="btn btn-danger btn-sm">ไม่อนุมัติ</button>
                                                    </form>
                                                    <button class="btn btn-info btn-sm"
                                                        onclick="showBookingDetails({{ $booking->id }})">รายละเอียดการจอง</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <p class="mt-4">ไม่มีการจองที่รอการอนุมัติ</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal สำหรับแสดงรายละเอียดการจอง -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel"
        aria-hidden="true">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Bar chart for monthly bookings
        var ctx = document.getElementById('monthlyBookingChart').getContext('2d');
        var monthlyBookingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.',
                    'ธ.ค.'
                ],
                datasets: [{
                    label: 'จำนวนการจองทั้งหมด',
                    data: @json(array_values($monthlyBookings->toArray())),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Horizontal bar chart for room usage (Update here)
        var ctxRoom = document.getElementById('roomUsageChart').getContext('2d');
        var roomUsageChart = new Chart(ctxRoom, {
            type: 'bar',
            data: {
                labels: @json($roomBookings->pluck('name')),
                datasets: [{
                    label: 'จำนวนการจองในแต่ละห้อง',
                    data: @json($roomBookings->pluck('bookings_count')),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // This makes the bar chart horizontal
            }
        });

        // Pie chart for participant responses
        var ctxPie = document.getElementById('participantResponseChart').getContext('2d');
        var participantResponseChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['ตอบรับแล้ว', 'ปฏิเสธ', 'ยังไม่ตอบรับ'],
                datasets: [{
                    data: [
                        {{ $participantStats->get('approved', 0) }},
                        {{ $participantStats->get('cancel', 0) }},
                        {{ $participantStats->get('pending', 0) }}
                    ],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                }]
            }
        });

        function showBookingDetails(bookingId) {
            // ส่ง AJAX ไปดึงข้อมูลการจอง
            $.ajax({
                url: '{{ url('booking/details/ajax') }}/' + bookingId,
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
