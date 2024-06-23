@extends('Layouts.User.user_layout')

@section('content')
    <div class="right">
        <h3 class="p-2">Cài đặt tài khoản</h3>
        <div class="menu-user-setting flex">
            <a class="text-decoration-none color-black" href="{{ route('tai-khoan.quan-ly-tai-khoan') }}">
                <h5 class="menu-item p-2 cursor color-main-1 border-bottom-1" id="menu-item-user-setting">Thông tin tài khoản</h5>
            </a>
            <a class="text-decoration-none color-black" href="{{ route('tai-khoan.mat-khau-va-bao-mat') }}">
                <h5 class="menu-item p-2 cursor" id="menu-item-password-and-security">Mật khẩu & bảo mật</h5>
            </a>
        </div>
        <hr>
        <div class="content-user-setting user-setting p-3 box-shadow-10-black border-radius-10" id="user-setting">
            <h4>Thông tin người dùng</h4>
            <hr>
            <div>
                @if ($message = Session::get('successUpdateUser'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ $message }}
                    </div>
                @endif
            </div>
            <form method="POST" action="{{ route('tai-khoan.sua-tai-khoan', ['id' => Auth::user()->id ]) }}">
                @csrf
                <label for="">Họ tên:</label>
                <input type="text" name="name" class="input width-100-percent" value="{{ $user->name }}" minlength="6" maxlength="255" required>
                <p class="text-danger">
                    @error('name')
                        {{ $message }}
                    @enderror
                </p>

                <label for="">Địa chỉ:</label>
                <input type="text" name="address" class="input width-100-percent" value="{{ $user->address }}" minlength="10" maxlength="500">
                <p class="text-danger">
                    @error('user_address')
                        {{ $message }}
                    @enderror
                </p>

                <label for="">Số điện thoại:</label>
                <input type="tel" name="phone_number" class="input width-100-percent" pattern="[0]{1}[0-9]{9}" value="{{ $user->phone_number }}">
                <p class="text-danger">
                    @error('user_phone_number')
                        {{ $message }}
                    @enderror
                </p>

                <label for="">Email:</label>
                <input type="email" class="input width-100-percent" value="{{ Auth::user()->email }}" disabled>

                <br><br>
                <button type="reset" class="button hover-opacity-08-05">Đặt lại</button>
                <button type="submit" class="button back-color-main-1 color-white hover-opacity-08-05">Lưu lại</button>
            </form>

            <div class="cua-so cua-so-40 p-3 box-shadow-10-black" id="cua-so-thay-doi-email">
                <div class="flex flex-between">
                    <h4>Thay đổi email:</h4>
                    <i class="fas fa-window-close fs-4 cursor text-danger" onclick="dongCuaSo('cua-so-thay-doi-email', 'nen-cua-so-thay-doi-email')"></i>
                </div>
                <hr>
                <form action="">
                    <label for="">Nhập email mới*:</label>
                    <input type="text" class="input width-100-percent" required>
                    <label for="">Nhập mật khẩu*:</label>
                    <div class="password">
                        <input type="password" class="input width-100-percent input-password" id="nhap-mat-khau-doi-email" required>
                        <i class="fas fa-eye cursor" onclick="anHienMatKhau('nhap-mat-khau-doi-email', this)"></i>
                    </div>

                    <br><br>
                    <button type="reset" class="button hover-opacity-08-05">Đặt lại</button>
                    <button type="submit" class="button hover-opacity-08-05 back-color-main-1 color-white">Lưu lại</button>
                </form>
            </div>
            <div class="nen-cua-so" id="nen-cua-so-thay-doi-email" onclick="dongCuaSo('cua-so-thay-doi-email', 'nen-cua-so-thay-doi-email')"></div>
        </div>
    </div>
@endsection