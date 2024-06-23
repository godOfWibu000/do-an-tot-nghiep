<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/class&variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@300&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <!-- Icon Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    
    <title>Document</title>
</head>
<body>
    <main>
        <div class="login">
            <div class="form-login color-white p-2 border-radius-10">
                <div id="message-login">
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
                </div>
                <a class="text-decoration-none color-white" href="{{ route('index') }}">
                    <h1 class="text-center p-2"><i class="fas fa-home"></i> HLRent</h1>
                </a>
                <h1 class="text-center">Đăng nhập</h1>
                <form action="{{ route('dang-nhap') }}" method="POST" onsubmit="return validateDangNhap()">
                    @csrf
                    <div class="form-item">
                        <i class="fas fa-user icon1"></i>
                        <input type="email" name="email" class="width-100-percent" placeholder="Email đăng nhập..." minlength="10" maxlength="255" required autofocus>
                    </div>
                    <p class="text-danger">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </p>

                    <div class="form-item">
                        <i class="fas fa-lock icon1"></i>
                        <input type="password" name="password" class="width-100-percent" id="password" placeholder="Mật khẩu..." minlength="8" maxlength="255" required>
                        <i class="fas fa-eye icon2 cursor" id="icon-password" onclick="anHienMatKhau('password', this)"></i>
                    </div>
                    <p class="text-danger" id="message-password">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>

                    <div class="flex flex-between">
                        <div>
                            <input type="checkbox" id="ghi-nho-tk" checked>&nbsp;<label for="ghi-nho-tk">Ghi nhớ tôi</label>
                        </div>
                        <a href="{{ route('yeu-cau-dat-lai-mat-khau') }}" class="text-decoration-none color-white">Quên mật khẩu?</a>
                    </div>

                    <br><br>
                    <button class="button width-100-percent hover-opacity-08-05 color-white back-color-main-1" type="submit">Đăng nhập</button>
                    <br><br>
                    <p>Bạn chưa có tài khoản? <a href="{{ route('dang-ky') }}">Đăng ký</a></p>
                </form>

                <div class="width-100-percent" style="text-align: center;">
                    <h5>© Phát triển bởi HLSoft. Bản quyền thuộc về HLRent</h5>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/user.js') }}"></script>
</body>
</html>