@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('ยินดีต้อนรับ Admin') }}
                </div>
                
            </div>
            <a href="{{route('bookings.create')}}" class="btn btn-primary mt-2">จอง</a>
            <a href="/meeting-rooms" class="btn btn-primary mt-2">ห้องประชุมทั้งหมด</a>
            
        </div>
    </div>
</div>
@endsection