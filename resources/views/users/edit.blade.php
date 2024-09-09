@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>แก้ไขข้อมูลผู้ใช้</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">ชื่อผู้ใช้:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">อีเมล:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">แผนก (ถ้ามี):</label>
            <input type="text" name="department" id="department" class="form-control" value="{{ $user->department }}">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">เบอร์โทรศัพท์ (ถ้ามี):</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
        </div>

        <div class="mb-3">
            <label for="is_admin" class="form-label">สิทธิ์ผู้ใช้:</label>
            <select name="is_admin" id="is_admin" class="form-select" required>
                <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>ผู้ใช้ทั่วไป</option>
                <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>
@endsection
