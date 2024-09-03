@extends('layouts.app')

@section('content')
    @if (count($blogs)>0)
    <h2 class="text text-center py-2">จัดการห้องประชุม</h2>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th scope="col">ชื่อห้องประชุม</th>
                <th scope="col">สถานะ</th>
                <th scope="col">แก้ไข</th>
                <th scope="col">ลบ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($blogs as $item)
                <tr>
                    <td>{{$item->title}}</td>
                    <!--<td>{{Str::limit($item->content,30)}}</td>-->
                    <td>
                        @if($item->status==true)
                            <a href="{{route('change',$item->id)}}" class="btn btn-success">ใช้งานได้</a>
                        @else
                            <a href="{{route('change',$item->id)}}" class="btn btn-secondary">ปิดปรับปรุง</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('edit',$item->id)}}"class="btn btn-warning ">แก้ไข</a>
                    </td>
                    <td>
                        <a 
                        href="{{route('delete',$item->id)}}" 
                        class="btn btn-danger"
                        onclick="return confirm('คุณต้องการลบห้องประชุมใช่หรือไม่ {{$item->title}} หรือไม่ ?')">
                        <i class="bi bi-trash"></i> ลบ
                    </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$blogs->links()}}
    @else
        <h1 class="text text-center">ไม่มีบทความในระบบ</h1>
    @endif
@endsection


