@extends('Layouts.Partner.partner_layout')

@section('redirect')
    <li class="active">{{ $title }}</li>
@endsection

@section('content')
    <div>
        <h3>Đổi mật khẩu</h3><span>Bạn <a href="{{ route('yeu-cau-dat-lai-mat-khau') }}">Quên mật khẩu?</a></span>
        <hr>
        <label for="">Mật khẩu cũ:</label>
        <input class="form-control" type="text" name="">
        <label for="">Mật khẩu mới:</label>
        <input class="form-control" type="text" name="">
        <label for="">Nhập lại mật khẩu mới:</label>
        <input class="form-control" type="text" name="">

        <br>
        <button class="btn btn-primary">Lưu lại</button>
        <button type="reset" class="btn btn-warning">Đặt lại</button>
    </div>
@endsection