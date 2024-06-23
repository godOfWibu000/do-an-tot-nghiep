@php
    $checkLogin = Auth::check();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@300&display=swap" rel="stylesheet">

    <!-- icon google -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/class&variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user.css') }}">

    <!-- Icon Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <title>{{ $title }}</title>

    <style>
        header{
            background: none;
            background-color: #0984e3;
            height: 60px;
        }
    </style>
</head>
<body>
    <main>
        <!-- Header -->
        <header>
            @include('Layouts.Module.header')
        </header>

        <div class="content p-2">
            <div class="content p-2 manage-user flex flex-between flex-wrap">
                <div class="left border border-radius-10" style="height: fit-content;">
                    <div class="flex user p-2">
                        <h3 class="avatar text-center"><i class="fas fa-user"></i></h3>
                        <div class="p-1">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </div>
                    <hr>
                    <div class="">
                        <a class="text-decoration-none color-black" href="{{ route('danh-gia-va-da-luu.cho-o-da-luu') }}">
                            <h5 class="cursor p-2 hover-backcolor-cloud"><i class="fas fa-bookmark"></i> Đã lưu</h5>
                        </a>
                        <a class="text-decoration-none color-black" href="{{ route('dat-phong.dat-phong-cua-toi') }}">
                            <h5 class="cursor p-2 hover-backcolor-cloud"><i class="fas fa-clipboard-list"></i> Đặt chỗ của tôi</h5>
                        </a>
                    </div>
                    <hr>
                    <div>
                    <h5 class="cursor dang-xuat p-2 hover-backcolor-cloud" onclick="if(confirm('Bạn có chắc chắn muốn đăng xuất?')) dangXuat('{{ route('dang-xuat') }}')">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </h5>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>

        <hr>
        <footer>
            @include('Layouts.Module.footer')
        </footer>
    </main>

    <!-- JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/user.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script>
        
    </script>
</body>
</html>