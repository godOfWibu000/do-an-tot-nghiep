@php
    $checkLogin = Auth::check();
@endphp
@extends('Layouts.Main.main_layout')

@section('content')
    <div class="alert alert-warning text-center" role="alert">
        <h3>Hiện tại chưa có khuyễn mại và ưu đãi nào!</h3>
    </div>
@endsection