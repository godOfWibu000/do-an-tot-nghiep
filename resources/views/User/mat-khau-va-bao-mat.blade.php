@extends('Layouts.User.user_layout')

@section('content')
    <div class="right">
        <h3 class="p-2">Cài đặt tài khoản</h3>
        <div class="menu-user-setting flex">
            <a class="text-decoration-none color-black" href="{{ route('tai-khoan.quan-ly-tai-khoan') }}">
                <h5 class="menu-item p-2 cursor" id="menu-item-user-setting">Thông tin tài khoản</h5>
            </a>
            <a class="text-decoration-none color-black" href="{{ route('tai-khoan.mat-khau-va-bao-mat') }}">
                <h5 class="menu-item p-2 cursor color-main-1 border-bottom-1" id="menu-item-password-and-security">Mật khẩu & bảo mật</h5>
            </a>
        </div>
        <hr>
        <div class="content-user-setting password-and-security p-3 box-shadow-10-black border-radius-10" id="password-and-security">
            <h4>Mật khẩu và bảo mật</h4>
            <hr>
            <h5>Đổi mật khẩu:</h5>
            <div>
                @if ($message = Session::get('successUpdatePassword'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('errorUpdatePassword'))
                    <div class="alert alert-danger text-center" role="alert">
                        {{ $message }}
                    </div>
                @endif
            </div>
            <form action="{{ route('tai-khoan.doi-mat-khau') }}" method="POST" >
                @csrf
                <label for="">Nhập mật khẩu cũ:</label> | Bạn <a href="{{ route('yeu-cau-dat-lai-mat-khau') }}" class="text-decoration-none">quên mật khẩu?</a>
                <div class="password">
                    <input type="password" name="password" class="input input-password width-100-percent" id="old-password" current-password required>
                    <i class="fas fa-eye cursor" onclick="anHienMatKhau('old-password', this)"></i>
                </div>
                <p class="text-danger">
                    @error('password')
                        {{ $message }}
                    @enderror
                </p>
                
                <label for="">Nhập mật khẩu mới:</label>
                <div class="password">
                    <input type="password" name="new_password" class="input input-password width-100-percent" id="password" required>
                    <i class="fas fa-eye cursor" onclick="anHienMatKhau('password', this)"></i>
                </div>
                <p class="text-danger" id="message-password">
                    @error('new_password')
                        {{ $message }}
                    @enderror
                </p>

                <label for="">Nhập lại mật khẩu mới:</label>
                <div class="password">
                    <input type="password" class="input input-password width-100-percent" id="repassword" required>
                    <i class="fas fa-eye cursor" onclick="anHienMatKhau('repassword', this)"></i>
                </div>
                <p class="text-danger" id="message-repassword"></p>

                <br><br>
                <button type="reset" class="button hover-opacity-08-05">Đặt lại</button>
                <button type="submit" class="button back-color-main-1 color-white hover-opacity-08-05">Lưu lại</button>
            </form>
            <hr>
            <button class="button width-100-percent hover-opacity-08-05 color-delete text-danger"><i class="fas fa-window-close"></i> Xóa tài khoản</button>
            <b class="text-danger">Lưu ý: Sau khi xóa tài khoản, toàn bộ dữ liệu của bạn sẽ bị mất</b>
        </div>
    </div>
@endsection