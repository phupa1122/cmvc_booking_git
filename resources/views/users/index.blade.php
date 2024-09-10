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
            <div class="card-header"><h1>จัดการผู้ใช้</h1></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="userTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>ชื่อผู้ใช้</th>
                                <th>อีเมล</th>
                                <th>สิทธิ์ผู้ใช้</th>
                                <th class="text-center">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="text-center">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->is_admin ? 'Admin' : 'ผู้ใช้ทั่วไป' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm me-2">
                                            <i class="fas fa-edit me-1"></i>แก้ไข
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('คุณแน่ใจว่าต้องการลบผู้ใช้นี้?')">
                                                <i class="fas fa-trash-alt me-1"></i>ลบ
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                language: {
                    url: '{{ asset('js/th.json') }}'
                },
                columnDefs: [{
                    orderable: false,
                    targets: 3
                }]
            });
        });
    </script>
@endpush
