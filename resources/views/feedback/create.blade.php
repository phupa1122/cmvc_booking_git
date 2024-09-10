@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-black">เพิ่มข้อเสนอแนะ</h1>
    <form action="{{ route('feedback.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="content" >เนื้อหาข้อเสนอแนะ:</label>
            <textarea name="feedback" id="feedback" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">บันทึก</button>
    </form>
</div>
@endsection
