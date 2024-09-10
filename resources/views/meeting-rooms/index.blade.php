@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-lg mt-3">
        <div class="card-header"><h1>รายการห้องประชุม</h1></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="meetingRoomTable">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>รูปภาพ</th>
                            <th>ชื่อห้องประชุม</th>
                            <th>รายละเอียด</th>
                            <th>ความจุ (ที่นั่ง)</th>
                            <th>สถานที่</th>
                            @if(auth()->user()->is_admin == 1) <!-- เฉพาะ Admin ที่จะเห็นคอลัมน์การจัดการ -->
                            <th class="text-center">การจัดการ</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr class="text-center">
                            <td>
                                @if($room->image)
                                    <img src="{{ asset('images/' . $room->image) }}" alt="{{ $room->name }}" width="100">
                                @else
                                    ไม่มีรูปภาพ
                                @endif
                            </td>                
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->des }}</td>
                            <td>{{ $room->capacity }} ที่นั่ง</td>
                            <td>{{ $room->location }}</td>
                            @if(auth()->user()->is_admin == 1) <!-- เฉพาะ Admin เท่านั้นที่เห็นปุ่มการจัดการ -->
                            <td class="text-center">
                                <a href="{{ route('meeting-rooms.edit', $room->id) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>แก้ไข
                                </a>
                                <form action="{{ route('meeting-rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจว่าต้องการลบห้องประชุมนี้?')">
                                        <i class="fas fa-trash-alt me-1"></i>ลบ
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(auth()->user()->is_admin == 1) <!-- เฉพาะ Admin เท่านั้นที่เห็นปุ่มเพิ่มห้องประชุม -->
        <div class="mt-3">
            <a href="{{ route('meeting-rooms.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>เพิ่มห้องประชุม
            </a>
        </div>
        @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#meetingRoomTable').DataTable({
            language: {
                url: '{{ asset('js/th.json') }}'
            },
            columnDefs: [
                { orderable: false, targets: {{ auth()->user()->is_admin == 1 ? 3 : '' }} }
            ]
        });
    });
</script>
@endpush
