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

                    {{ __('ยินดีต้อนรับ') }}
                    {{ Auth::user()->name }}
                    

                    <!-- แสดงการตอบรับการเข้าร่วมประชุม -->
                    @if($pendingMeetings->isNotEmpty())
                        <h4 class="mt-4">การประชุมที่คุณต้องตอบรับ</h4>
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>ชื่อห้องประชุม</th>
                                    <th>วันที่</th>
                                    <th>เวลา</th>
                                    <th>การตอบรับ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingMeetings as $meeting)
                                    <tr>
                                        <td>{{ $meeting->booking->meetingRoom->name }}</td>
                                        <td>{{ $meeting->booking->booking_start_date }}</td>
                                        <td>{{ $meeting->booking->start_time }} - {{ $meeting->booking->end_time }}</td>
                                        <td>
                                            <form action="{{ route('meeting.respond', $meeting->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" name="response" value="approve" class="btn btn-success btn-sm">ตกลง</button>
                                                <button type="submit" name="response" value="cancel" class="btn btn-danger btn-sm">ปฏิเสธ</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="mt-4">ไม่มีการประชุมที่ต้องตอบรับ</p>
                    @endif
                    <!-- ข้อเสนอแนะ -->
                    <a href="{{route('feedback.index')}}" class="btn btn-primary mt-2">ข้อเสนอแนะ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
