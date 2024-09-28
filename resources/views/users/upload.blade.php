@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-dark">นำเข้าข้อมูลผู้ใช้งานจาก Excel</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">เลือกไฟล์ Excel (.xlsx .xls .csv)</label>

                <input type="file" name="file" class="form-control" required>
                <a
                    href="https://docs.google.com/spreadsheets/d/1zEZxKl23rBK9g58kM92kXUbMmVLIt1lR3wS09BqYfzc/edit?usp=sharing">ตัวอย่างตารางผู้ใช้</a>
            </div>
            <button type="submit" class="btn btn-primary mt-3">นำเข้าข้อมูล</button>
        </form>
    </div>
@endsection
