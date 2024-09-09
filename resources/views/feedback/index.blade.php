@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รายการข้อเสนอแนะ</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ผู้ใช้</th>
                <th>เนื้อหา</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feedbacks as $feedback)
                <tr>
                    <td>{{ $feedback->user->name }}</td>
                    <td>{{ $feedback->feedback }}</td>
                    <td>
                        @if(Auth::id() == $feedback->user_id)
                            <a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                            <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                            </form>
                        @else
                            @if(auth()->user()->is_admin == 0)
                                <span class="text-muted">ไม่สามารถแก้ไขหรือลบได้</span>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{route('feedback.create')}}" class="btn btn-primary mt-2">เพิ่มข้อเสนอแนะ</a>    
</div>
@endsection
