@extends('Layouts.Booking.booking_layout')

@section('css')
    <style>
        .input-password{
            padding-right: 40px;
        }
        .icon-hidden-show-pass{
            top: 10px;
            right: 10px;
        }
    </style>
@endsection

@section('content')
    <h4>Đặt lại mật khẩu</h4>
    <hr>
    <form action="{{ route('luu-dat-lai-mat-khau', ['email' => $email]) }}" method="POST" onsubmit="return validate();">
        @csrf
        <label for="">Nhập mật khẩu mới:</label>
        <div class="position-relative">
            <input type="password" class="form-control input-password" id="input-password" name="password" minlength="8" required>
            <i class="fas fa-eye position-absolute cursor icon-hidden-show-pass" id="icon-hidden-show-pass" onclick="anHienMatKhau('input-password', this)"></i>
        </div>
        <p class="text-danger" id="input-password-message"></p>
        @error('password')
            <p class="text-danger" id="input-password-message">{{ $message }}</p>
        @enderror
        <label for="">Nhập lại mật khẩu mới:</label>
        <div class="position-relative">
            <input type="password" class="form-control input-password" id="input-repassword" name="re_password" required>
            <i class="fas fa-eye position-absolute cursor icon-hidden-show-pass" d="icon-hidden-show-repass" onclick="anHienMatKhau('input-repassword', this)"></i>
        </div>
        <p class="text-danger" id="input-repassword-message"></p>
        @error('re_password')
            <p class="text-danger" id="input-password-message">{{ $message }}</p>
        @enderror
        <br>
        <button class="btn btn-primary">Gửi</button>
        <button type="reset" class="btn btn-warning">Đặt lại</button>
    </form>
@endsection

@section('js')
    <script>
        function validate(){
            if(validateMatKhau('input-password', 'input-password-message') && validateNhapLaiMatKhau('input-password', 'input-repassword', 'input-repassword-message'))
                return true;
            return false;
        }
    </script>
@endsection