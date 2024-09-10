@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-black">แก้ไขข้อเสนอแนะ</h1>
    <form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="content">เนื้อหาข้อเสนอแนะ:</label>
            <textarea name="feedback" id="feedback" class="form-control" rows="4" required>{{ $feedback->feedback }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">อัปเดต</button>
    </form>
</div>
@endsection
