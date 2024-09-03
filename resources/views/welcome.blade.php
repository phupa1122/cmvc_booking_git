//ไม่ได้ใช้
@extends('layouts.app')

@section('title', 'Booking CMVC')

@section('content')

    <div class="container mt-5">
        <h1>จองห้องประชุม</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('booking.store') }}" method="POST" class="card p-4 shadow-sm bg-white">
            @csrf
            <div class="mb-3">
                <label for="room_name" class="form-label">ชื่อห้องประชุม:</label>
                <select name="room_name" id="room_name" class="form-select">
                    @foreach ($rooms as $room)
                        <option value="{{ $room }}">{{ $room }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="meeting_topic" class="form-label">หัวข้อการประชุม:</label>
                <input type="text" id="meeting_topic" name="meeting_topic" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">ผู้เข้าร่วมประชุม:</label>
                <div id="participants">
                    <div class="input-group mb-2">
                        <input type="text" name="participants[0][name]" class="form-control" placeholder="ชื่อ">
                        <input type="text" name="participants[0][phone]" class="form-control"
                            placeholder="เบอร์โทรศัพท์">
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm"
                    onclick="addParticipant()">เพิ่มผู้เข้าร่วม</button>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">วันที่เริ่มต้นประชุม:</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">วันที่สิ้นสุดประชุม:</label>
                <input type="date" id="end_date" name="end_date" class="form-control">
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">เวลาเริ่มต้นประชุม:</label>
                <input type="time" id="start_time" name="start_time" class="form-control">
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">เวลาสิ้นสุดประชุม:</label>
                <input type="time" id="end_time" name="end_time" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">อุปกรณ์:</label><br>
                <div class="form-check">
                    <input type="checkbox" name="equipment[]" value="เก้าอี้" class="form-check-input">
                    <label class="form-check-label">เก้าอี้</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="equipment[]" value="เครื่องคอมพิวเตอร์" class="form-check-input">
                    <label class="form-check-label">เครื่องคอมพิวเตอร์</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="equipment[]" value="จอโปรเจคเตอร์" class="form-check-input">
                    <label class="form-check-label">จอโปรเจคเตอร์</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="additional_details" class="form-label">รายละเอียดเพิ่มเติม:</label>
                <textarea id="additional_details" name="additional_details" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">จองห้องประชุม</button>
        </form>
    </div>

    <script>
        function addParticipant() {
            const index = document.querySelectorAll('#participants div').length;
            const newParticipant = document.createElement('div');
            newParticipant.innerHTML = `
            <div id="participants">
              <div class="input-group mb-2">
                  <input type="text" name="participants[0][name]" class="form-control" placeholder="ชื่อ">
                  <input type="text" name="participants[0][phone]" class="form-control" placeholder="เบอร์โทรศัพท์">
              </div>
            </div>
          `;
            document.getElementById('participants').appendChild(newParticipant);
        }
    </script>

@endsection


