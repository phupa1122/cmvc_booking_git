@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5 shadow-lg border-0 rounded-lg">
                    <div class="card-header text-white">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- {{ __('ยินดีต้อนรับ') }}
                        {{ Auth::user()->name }} --}}

                        <!-- Personal Booking Statistics -->
                        <h4 class="mt-2">สถิติการจองของคุณ</h4>

                        <div class="row justify-content-center">
                            <!-- Bar Chart: จำนวนการจองที่ทำในแต่ละเดือน -->
                            <div class="col-md-6">
                                <canvas id="monthlyBookingChart"></canvas>
                            </div>

                            <!-- Line Chart: การเข้าร่วมประชุม -->
                            <div class="col-md-6">
                                <canvas id="participationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-5 shadow-lg border-0 rounded-lg">
                    <div class="card-header text-white">{{ __('การประชุมที่คุณต้องตอบรับ') }}</div>

                    <div class="card-body">
                        @if ($pendingMeetings->isNotEmpty())
                            <h4 class="mt-4">การประชุมที่คุณต้องตอบรับ</h4>
                            <table class="table table-bordered mt-2">
                                <thead>
                                    <tr class="text-center">
                                        <th>ชื่อห้องประชุม</th>
                                        <th>วันที่</th>
                                        <th>เวลา</th>
                                        <th>การตอบรับ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingMeetings as $meeting)
                                        <tr>
                                            <td>{{ $meeting->booking->meetingRoom->name }}</td>
                                            <td>{{ $meeting->booking->booking_start_date }}</td>
                                            <td> {{ \Carbon\Carbon::parse($meeting->booking->start_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($meeting->booking->end_time)->format('H:i') }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('meeting.respond', $meeting->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" name="response" value="approve"
                                                        class="btn btn-success btn-sm">ตกลง</button>
                                                    <button type="submit" name="response" value="cancel"
                                                        class="btn btn-danger btn-sm">ปฏิเสธ</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="mt-4">ไม่มีการประชุมที่ต้องตอบรับ</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Bar Chart for Monthly Bookings
        const monthlyBookingCtx = document.getElementById('monthlyBookingChart').getContext('2d');
        const monthlyBookingChart = new Chart(monthlyBookingCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach (range(1, 12) as $month)
                        "{{ $month }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'จำนวนการจองในแต่ละเดือน',
                    data: [
                        @foreach (range(1, 12) as $month)
                            {{ $monthlyBookings[$month] ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Line Chart for Participation
        const participationCtx = document.getElementById('participationChart').getContext('2d');
        const participationChart = new Chart(participationCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach (range(1, 12) as $month)
                        "{{ $month }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'การเข้าร่วมประชุมในแต่ละเดือน',
                    data: [
                        @foreach (range(1, 12) as $month)
                            {{ $participationStats[$month] ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
