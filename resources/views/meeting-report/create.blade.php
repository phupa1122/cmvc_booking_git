@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>เพิ่มรายงานการประชุม</h1>
        <form action="{{ route('meeting-report.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="mb-3">
                <label for="meeting_title" class="form-label">หัวข้อรายงานการประชุม</label>
                <input type="text" name="meeting_title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="meeting_number" class="form-label">ครั้งที่</label>
                <input type="number" name="meeting_number" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="first_paragraph" class="form-label">ย่อหน้าแรก</label>
                <textarea name="first_paragraph" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="agenda_count" class="form-label">จำนวนระเบียบวาระ</label>
                <input type="number" name="agenda_count" class="form-control" min="1" max="10" required>
            </div>

            <div class="mb-3">
                
                <div id="agendaFields"></div>
            </div>

            <div class="mb-3">
                <label for="sign_count" class="form-label">จำนวนผู้ลงชื่อ</label>
                <input type="number" name="sign_count" class="form-control" min="1" max="10" required>
            </div>

            <div id="signFields"></div>

            <button type="submit" class="btn btn-primary">บันทึกรายงาน</button>
        </form>
    </div>

    <script>
        document.querySelector('input[name="agenda_count"]').addEventListener('change', function () {
            const count = this.value;
            const agendaFields = document.getElementById('agendaFields');
            agendaFields.innerHTML = '';

            for (let i = 0; i < count; i++) {
                agendaFields.innerHTML += `
                    <div class="mb-2">
                        <label>หัวข้อระเบียบวาระที่ ${i + 1}</label>
                        <input type="text" name="agenda_titles[]" class="form-control" required>
                        <label>เนื้อหาระเบียบวาระที่ ${i + 1}</label>
                        <textarea name="agenda_contents[]" class="form-control" rows="2" required></textarea>
                    </div>`;
            }
        });

        document.querySelector('input[name="sign_count"]').addEventListener('change', function () {
            const count = this.value;
            const signFields = document.getElementById('signFields');
            signFields.innerHTML = '';

            for (let i = 0; i < count; i++) {
                signFields.innerHTML += `
                    <div class="mb-2">
                        <label>ชื่อผู้ลงชื่อที่ ${i + 1}</label>
                        <input type="text" name="sign_names[]" class="form-control" required>
                        <label>ตำแหน่งผู้ลงชื่อที่ ${i + 1}</label>
                        <input type="text" name="sign_positions[]" class="form-control" required>
                    </div>`;
            }
        });
    </script>
@endsection
