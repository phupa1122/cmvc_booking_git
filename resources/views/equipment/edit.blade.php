@extends('layouts.app')

@section('content')
    <div class="container py-5">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header">
                <h1>แก้ไขอุปกรณ์</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label for="name" class="form-label">ชื่ออุปกรณ์:</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $equipment->name) }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description" class="form-label">รายละเอียด:</label>
                        <textarea name="description" id="description" rows="3" class="form-control" required>{{ old('description', $equipment->description) }}</textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('equipment.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>กลับไปยังรายการอุปกรณ์
            </a>
        </div>

    </div>
@endsection
