@extends('Layouts.Partner.partner_layout')

@section('css')
    <style>
        .thong-tin-doi-tac .header, .thong-tin-doi-tac .body{
            display: flex;
        }
        .thong-tin-doi-tac .header{
            justify-content: space-between;
        }
        .thong-tin-doi-tac .header .left, .thong-tin-doi-tac .header .right{
            display: flex;
        }
        .thong-tin-doi-tac .header .avatar .avatar-img{
            border-radius: 50%;
        }
        .thong-tin-doi-tac .header .avatar button{
            width: 100%;
        }
        .thong-tin-doi-tac .header .right i{
            font-size: 20px;
            margin-top: 10px;
        }

        .thong-tin-doi-tac .header .infor{
            border-left: 1px solid #bdc3c7;
            margin-left: 10px;
            padding-left: 10px;
        }
        .thong-tin-doi-tac .body .item{
            width: 48%;
            margin-right: 1%;
            padding: 0 1%;
        }
        .thong-tin-doi-tac .body .thong-tin-co-ban{
            border-right: 1px solid #bdc3c7;
        }
    </style>
@endsection

@php
    $avatar = asset('assets/img/partner/partner_avatar.png');
    $avatarFileName = 0;
    if($partner->logo != null){
        $avatarFileName = $partner->logo;
        $avatar = asset('assets/img/partner') . '/' . $partner->logo;
    }
@endphp

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success text-center" id="alert-login" role="alert">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger text-center" id="alert-login" role="alert">
            {{ $message }}
        </div>
    @endif
    <div class="thong-tin-doi-tac">
        <div class="header">
            <div class="left">
                <div class="avatar">
                    <img class="avatar-img" src="{{ $avatar }}" alt="" width="100px" height="100px"><br>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#doiAnh">Đổi ảnh</button>

                    <div class="modal fade" id="doiAnh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalLabel">Tải lên ảnh:</h3>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('partner.thong-tin-doi-tac.doi-anh', ['oldImage' => $avatarFileName]) }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" accept=".jpg, .jpeg, .png" name="image" onchange="checkSizeImageUpload(this, 'message-doi-anh');hienAnhUpload(this, 'doi-anh')" required>
                                        @error('image')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                        <p class="text-danger" id="message-doi-anh"></p>
                                        <img src="https://cdn-icons-png.flaticon.com/512/1160/1160358.png" alt="" id="doi-anh" width="150px"><br><br>

                                        <button class="btn btn-primary">Lưu lại</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="infor">
                    <h3>{{ $partner->name }}</h3>
                    <h4>{{ Auth::user()->email }}</h4>
                </div>
            </div>

            <div class="right">
                @if ($partner->partner_status == 0)
                    <i class="fas fa-times-circle"></i>&nbsp;
                    <h4 class="">Chưa xác thực</h4>
                @else
                    <i class="fas fa-check-circle text-success"></i>&nbsp;
                    <h4 class="text-success">Đã xác thực</h4>
                @endif
            </div>
        </div>
        <hr>

        <div class="body">
            <div class="thong-tin-co-ban item">
                <h3>Thông tin cơ bản</h3>
                <hr>
                <form action="{{ route('partner.thong-tin-doi-tac.cap-nhat-thong-tin-co-ban') }}" method="POST">
                    @csrf
                    <label for="">Tên hiển thị(<b class="text-danger">*</b>):</label>
                    <input class="form-control" type="text" name="name" minlength="6" maxlength="255" value="{{ $partner->name }}" required>
                    <p class="text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Địa chỉ(<b class="text-danger">*</b>):</label>
                    <textarea class="form-control" name="address" id="" cols="10" rows="3">{{ $partner->address }}</textarea>
                    <p class="text-danger">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Số điện thoại(<b class="text-danger">*</b>):</label>
                    <input class="form-control" name="phone_number" type="tel" pattern="[0]{1}[0-9]{9}" value="{{ $partner->phone_number }}" required>
                    <p class="text-danger">
                        @error('phone_number')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Email:</label>
                    <input class="form-control" type="text" required value="{{ Auth::user()->email }}" disabled>

                    <br><br>
                    <button class="btn btn-primary">Lưu lại</button>
                    <button class="btn btn-warning" type="reset">Đặt lại</button>
                </form>
            </div>

            <div class="thong-tin-doanh-nghiep item">
                <h3>Thông tin doanh nghiệp</h3>
                <hr>
                <form action="{{ route('partner.thong-tin-doi-tac.cap-nhat-thong-tin-chi-tiet') }}" method="POST">
                    @csrf
                    <label for="">Tên doanh nghiệp(<b class="text-danger">*</b>):</label>
                    <textarea class="form-control" name="company_name" id="" cols="10" rows="3" required minlength="6" maxlength="255">{{ $partner->company_name }}</textarea>
                    <p class="text-danger">
                        @error('company_name')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Mã số thuế(<b class="text-danger">*</b>):</label>
                    <input class="form-control" type="tel" pattern="[0-9]{10}" name="tax_number" value="{{ $partner->tax_number }}" required>
                    <p class="text-danger">
                        @error('tax_number')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Địa chỉ(<b class="text-danger">*</b>):</label>
                    <textarea class="form-control" name="company_address" id="" cols="10" rows="3" required minlength="6" maxlength="500">{{ $partner->company_address }}</textarea>
                    <p class="text-danger">
                        @error('company_address')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Số điện thoại(<b class="text-danger">*</b>):</label>
                    <input class="form-control" type="tel" pattern="[0]{1}[0-9]{9}" name="company_phone_number" value="{{ $partner->company_phone_number }}" required>
                    <p class="text-danger">
                        @error('company_phone_number')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Email(<b class="text-danger">*</b>):</label>
                    <input class="form-control" type="email" name="company_email" value="{{ $partner->company_email }}" minlength="10" maxlength="255" required>
                    <p class="text-danger">
                        @error('company_email')
                            {{ $message }}
                        @enderror
                    </p>

                    <label for="">Website:</label>
                    <input class="form-control" type="text" name="company_website" value="{{ $partner->company_website }}" minlength="6" maxlength="255">
                    <p class="text-danger">
                        @error('company_website')
                            {{ $message }}
                        @enderror
                    </p>

                    <br>
                    <button class="btn btn-primary">Lưu lại</button>
                    <button class="btn btn-warning" type="reset">Đặt lại</button>
                </form>
            </div>
        </div>
    </div>

@endsection