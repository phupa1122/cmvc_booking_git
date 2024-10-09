@extends('layouts.app')

@section('content')
    <div class="container py-5">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header">
                <h1>จัดการอุปกรณ์</h1>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="equipmentTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>ชื่ออุปกรณ์</th>
                                <th>รายละเอียด</th>
                                @if (auth()->user()->is_admin == 1)
                                    <!-- เฉพาะ Admin ที่จะเห็นคอลัมน์การจัดการ -->
                                    <th class="text-center">การจัดการ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($equipment as $item)
                                <tr class="text-center">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    @if (auth()->user()->is_admin == 1)
                                        <!-- เฉพาะ Admin เท่านั้นที่เห็นปุ่มการจัดการ -->
                                        <td class="text-center">
                                            <a href="{{ route('equipment.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm me-2">
                                                <i class="fas fa-edit me-1"></i>แก้ไข
                                            </a>
                                            <form action="{{ route('equipment.destroy', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('คุณแน่ใจว่าต้องการลบอุปกรณ์นี้?')">
                                                    <i class="fas fa-trash-alt me-1"></i>ลบ
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if (auth()->user()->is_admin == 1)
            <!-- เฉพาะ Admin เท่านั้นที่เห็นปุ่มเพิ่มอุปกรณ์ -->
            <div class="mt-3">
                <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>เพิ่มอุปกรณ์
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush
