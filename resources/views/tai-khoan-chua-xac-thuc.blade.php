<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Tài khoản chưa được xác thực</title>
</head>
<body>
    <div style="">
        <div class="text-center border p-2" style="max-width: 980px;margin: 20vh auto;">
            <h4>Tài khoản của bạn chưa được xác thực! Vui lòng bổ sung thông tin cần thiết và chờ đợi khi thông tin được xác thực để sử dụng chức năng này!</h4>
            <a href="{{ route('partner.thong-tin-doi-tac.index') }}">
                <button class="btn btn-primary">Quay lại trang quản trị</button>
            </a>
        </div>
    </div>
</body>
</html>