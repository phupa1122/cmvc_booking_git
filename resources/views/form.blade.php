@extends('layouts.app')
@section('title', 'Booking CMVC')
@section('content')
    <h2 class="text text center py-2">เพิ่มห้องประชุมใหม่</h2>
    
    <form method="POST" action="{{route('insert')}}">
        @csrf
        <div class="form-group">
            <label for="title">ชื่อห้อง</label>
            <input type="text" name="title" class="form-control">
        </div>
        @error('title')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <div class="form-group">
            <label for="content">รายละเอียด</label>
            <textarea name="content" cols="30" rows="5" class="form-control" id="editor"></textarea>
        </div>
        @error('content')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <input type="submit" value="บันทึก" class="btn btn-primary my-3">
        <a href="{{route('blog')}}" class="btn btn-success">ห้องประชุมทั้งหมด</a>
    </form>
    
    
@endsection

