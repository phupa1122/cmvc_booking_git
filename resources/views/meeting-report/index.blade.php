@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header">
                <h1>รายงานการประชุม</h1>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="meetingTable">
                    <thead>
                        <tr class="text-center">
                            <th>ชื่อห้องประชุม</th>
                            <th>วันที่</th>
                            <th>เวลา</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="text-center">{{ $booking->meetingRoom->name }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($booking->booking_start_date)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                <td class="text-center">
                                    @if (!$user->is_admin)
                                        @if (!$booking->meetingReport)
                                            <a href="{{ route('meeting-report.create', ['booking_id' => $booking->id]) }}"
                                                class="btn btn-success">เพิ่มรายงาน</a>
                                        @endif
                                        @if ($booking->meetingReport)
                                            <a href="{{ url('pdf_files/' . $booking->meetingReport->report_content) }}"
                                                class="btn btn-info" target="_blank">ดูรายงาน</a>
                                            <!-- ปุ่มลบ -->
                                            <form
                                                action="{{ route('meeting-report.destroy', ['id' => $booking->meetingReport->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายงานนี้?')">ลบ</button>
                                            </form>
                                        @endif
                                    @else
                                        @if ($booking->meetingReport)
                                            <a href="{{ url('pdf_files/' . $booking->meetingReport->report_content) }}"
                                                class="btn btn-info" target="_blank">ดูรายงาน</a>
                                            <!-- ปุ่มลบ -->
                                            <form
                                                action="{{ route('meeting-report.destroy', ['id' => $booking->meetingReport->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายงานนี้?')">ลบ</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#meetingTable').DataTable();
            });
        </script>
    @endpush
