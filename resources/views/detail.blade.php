@extends('layouts.app')

@section('title',)
    {{$blog->title}}
@endsection
@section('content')
    <h2 class="text text-center">{{$blog->title}}</h2>
    <hr>
    <p>{{$blog->content}}</p>
   
@endsection
