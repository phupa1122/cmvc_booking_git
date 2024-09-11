@extends('layouts.app')

@section('content')
    <div class="container py-5">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header">
                <h1>รายการข้อเสนอแนะ</h1>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ผู้ใช้</th>
                            <th>เนื้อหา</th>
                            @if (auth()->user()->is_admin == 0) <!-- เฉพาะผู้ใช้ทั่วไปเท่านั้นที่เห็นส่วนการจัดการ -->
                                <th>การจัดการ</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->user->name }}</td>
                                <td>{{ $feedback->feedback }}</td>
                                @if (auth()->user()->is_admin == 0) <!-- เฉพาะผู้ใช้ทั่วไปเท่านั้นที่เห็นส่วนการจัดการ -->
                                    <td>
                                        @if (Auth::id() == $feedback->user_id)
                                            <a href="{{ route('feedback.edit', $feedback->id) }}"
                                                class="btn btn-warning btn-sm">แก้ไข</a>
                                            <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                                            </form>
                                        @else
                                            <span class="text-muted">ไม่สามารถแก้ไขหรือลบได้</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if (auth()->user()->is_admin == 0) <!-- เฉพาะผู้ใช้ทั่วไปเท่านั้นที่เห็นปุ่มเพิ่มข้อเสนอแนะ -->
            <a href="{{ route('feedback.create') }}" class="btn btn-primary mt-2">เพิ่มข้อเสนอแนะ</a>
        @endif
    </div>
@endsection
