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

                </div>
                <a class="text-decoration-none color-white" href="{{ route('index') }}">
                    <h1 class="text-center p-2"><i class="fas fa-home"></i> HLRent</h1>
                </a>
                <h1 class="text-center">Đăng ký tài khoản</h1>
                <form action="{{ route('dang-ky') }}" method="POST" onsubmit="return validateDangKy()">
                    @csrf
                    <div class="form-item">
                        <i class="fas fa-user icon1"></i>
                        <input type="text" name="name" class="width-100-percent" placeholder="Tên của bạn" minlength="6" maxlength="255" required autofocus>
                    </div>
                    <p class="text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </p>

                    <div class="form-item">
                        <i class="fas fa-map-marked-alt icon1"></i>
                        <input type="text" name="address" class="width-100-percent" placeholder="Địa chỉ" minlength="6" maxlength="500" required>
                    </div>
                    <p class="text-danger">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </p>

                    <div class="form-item">
                        <i class="fas fa-phone icon1"></i>
                        <input type="tel" name="phone_number" class="width-100-percent" pattern="[0]{1}[0-9]{9}" placeholder="Số điện thoại" minlength="10" maxlength="20" required autofocus>
                    </div>
                    <p class="text-danger">
                        @error('phone_number')
                            {{ $message }}
                        @enderror
                    </p>

                    <div class="form-item">
                        <i class="fas fa-envelope icon1"></i>
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

                    <div class="form-item">
                        <i class="fas fa-lock icon1"></i>
                        <input type="password" name="repassword" class="width-100-percent" id="repassword" placeholder="Nhập lại mật khẩu..." required>
                        <i class="fas fa-eye icon2 cursor" id="icon-repassword" onclick="anHienMatKhau('repassword', this)"></i>
                    </div>
                    <p class="text-danger" id="message-repassword">
                        @error('repassword')
                            {{ $message }}
                        @enderror
                    </p>

                    <div class="form-item">
                        <label for="">Loại tài khoản:</label>
                        <select name="role" id="" class="form-control">
                            <option value="Customer">Khách hàng</option>
                            <option value="Partner">Đối tác</option>
                        </select>
                    </div>
                    <p class="text-danger" id="message-repassword">
                        @error('role')
                            {{ $message }}
                        @enderror
                    </p>

                    <br>
                    <button class="button width-100-percent hover-opacity-08-05 color-white back-color-main-1" type="submit">Đăng ký</button>
                    <br><br>
                    <p>Bạn đã có tài khoản? <a href="{{ route('dang-nhap') }}">Đăng nhập</a></p>
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
<script>
</script>
</html>