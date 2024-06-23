@extends('Layouts.Booking.booking_layout')

@section('content')
    @if ($message = Session::get('error'))
        <div class="alert alert-danger text-center" id="alert-login" role="alert">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success text-center" id="alert-login" role="alert">
            {{ $message }}
        </div>
    @endif

    <ul class="text-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    <form action="{{ route('kiem-tra-dat-lai-mat-khau') }}" method="POST">
        @csrf
        <label for="">Nhập email của bạn:</label>
        <input type="email" class="form-control" name="email">
        <br>
        <button class="btn btn-primary">Gửi yêu cầu</button>
    </form>
@endsection