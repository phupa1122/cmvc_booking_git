@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แบบฟอร์มสรุปรายงานการประชุม</h1>
    <form action="{{ route('meeting-report.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="meeting_title" class="form-label">หัวข้อการประชุม</label>
            <input type="text" name="meeting_title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="meeting_number" class="form-label">ครั้งที่</label>
            <input type="text" name="meeting_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="meeting_date" class="form-label">วันที่ประชุม</label>
            <input type="date" name="meeting_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="meeting_location" class="form-label">สถานที่ประชุม</label>
            <input type="text" name="meeting_location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="participants" class="form-label">ผู้เข้าร่วมประชุม</label>
            <textarea name="participants[]" class="form-control" rows="5"></textarea>
        </div>

        <div class="mb-3">
            <label for="issues" class="form-label">ประเด็นการประชุม</label>
            <textarea name="issues[]" class="form-control" rows="5"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">สร้างรายงาน</button>
    </form>
</div>
@endsection
