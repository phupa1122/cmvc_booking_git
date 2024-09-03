@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1>รายการห้องประชุม</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('meeting-rooms.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>เพิ่มห้องประชุม
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="meetingRoomTable">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อห้องประชุม</th>
                            <th>ความจุ (ที่นั่ง)</th>
                            <th>สถานที่</th>
                            <th class="text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->capacity }} ที่นั่ง</td>
                            <td>{{ $room->location }}</td>
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
        $('#meetingRoomTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json'
            },
            columnDefs: [
                { orderable: false, targets: 3 }
            ]
        });
    });
</script>
@endpush
