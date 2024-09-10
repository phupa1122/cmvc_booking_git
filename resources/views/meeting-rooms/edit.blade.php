@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header">
                    <h2 class="text-center mb-0">แก้ไขห้องประชุม</h2>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('meeting-rooms.update', $meetingRoom->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label">ชื่อห้องประชุม:</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg" value="{{ $meetingRoom->name }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="capacity" class="form-label">ความจุ (ที่นั่ง):</label>
                            <div class="input-group">
                                <input type="number" name="capacity" id="capacity" class="form-control form-control-lg" value="{{ $meetingRoom->capacity }}" required min="1">
                                <span class="input-group-text">ที่นั่ง</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label">สถานที่:</label>
                            <input type="text" name="location" id="location" class="form-control form-control-lg" value="{{ old('location', $meetingRoom->location) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="des" class="form-label">รายละเอียด</label>
                            <textarea class="form-control" id="des" name="des" rows="3">{{ old('des',$meetingRoom->des) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">อัปโหลดรูปภาพ</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    
                        @if ($meetingRoom->image)
                            <img src="{{ asset('images/' . $meetingRoom->image) }}" alt="{{ $meetingRoom->name }}" width="200">
                        @endif
                        
                        <div class="d-flex justify-content-end mt-5">
                            <a href="{{ route('meeting-rooms.index') }}" class="btn btn-secondary btn-lg me-2">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary btn-lg">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection