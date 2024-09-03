@extends('layouts.app')
@section('title', 'แก้ไขบทความ')
@section('content')
    <h2 class="text text center py-2">แก้ไขห้องประชุม</h2>
    <form method="POST" action="{{route('update',$blog->id)}}">
        @csrf
        <div class="form-group">
            <label for="title">ชื่อห้องประชุม</label>
            <input type="text" name="title" class="form-control" value="{{$blog->title}}">
        </div>
        @error('title')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <div class="form-group">
            <label for="content">รายละเอียด</label>
            <textarea name="content" cols="30" rows="5" class="form-control">{{$blog->content}}</textarea>
        </div>
        @error('content')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <input type="submit" value="อัปเดต" class="btn btn-primary my-3">
        <a href="{{route('blog')}}" class="btn btn-success">ห้องประชุมทั้งหมด</a>
    </form>
@endsection