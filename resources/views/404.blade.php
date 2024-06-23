<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Icon Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <title>Không tìm thấy trang</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        .main{
            height: 100vh;
            background-image: url('{{ asset('assets/img/404.jpg') }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
        button{
            border: none;
            background: none;
            font-size: 40px;
            margin: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div>
        <div class="main">
            <a href="{{ route('index') }}">
                <button>
                    <i class="fas fa-home"></i>
                </button>
            </a>
        </div>
    </div>
</body>
</html>