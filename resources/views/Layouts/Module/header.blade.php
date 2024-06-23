<div class="header">
    <div class="nav flex flex-between color-white">
        <div class="nav-item nav-left">
            <a class="text-decoration-none color-white" href="{{ route('index') }}">
                <h2 class="title">HLRent</h2>
            </a>
        </div>
        <div class="flex nav-right">
            <div class="menu flex" id="menu">
                <ul class="flex nav-item list-unstyled">
                    <li><a class="text-decoration-none fs-5 p-4 color-white hover-opacity-08-05" href="{{ route('index') }}">Trang chủ</a></li>
                    <li><a class="text-decoration-none fs-5 p-4 color-white hover-opacity-08-05" href="{{ route('cho-o.index') }}">Chỗ ở</a></li>
                    <li><a class="text-decoration-none fs-5 p-4 color-white hover-opacity-08-05" href="{{ route('dat-phong.dat-phong-cua-toi') }}">Đặt phòng</a></li>
                </ul>
                <div class="nav-item nav-item-account flex">
                    @if($checkLogin)
                        <div class="flex">
                            <a class="color-white" href="{{ route('danh-gia-va-da-luu.cho-o-da-luu') }}"><i class="fas fa-bookmark cursor fs-4 hover-opacity-08-05" title="Đã lưu" style="padding: 5px"></i></a>&nbsp;&nbsp;&nbsp;
                            <div class="border border-radius-20" style="padding: 5px">
                                &nbsp;
                                <i class="fas fa-user fs-4"></i>&nbsp;&nbsp;&nbsp;
                                @if (Auth::user()->role == 'Customer')
                                    <a class="text-decoration-none color-white" href="{{ route('tai-khoan.quan-ly-tai-khoan') }}">{{ Auth::user()->email }}</a>
                                    &nbsp;
                                    <i class="fas fa-angle-down cursor" id="mo-cua-so-tuy-chon" style="position: relative;top: 0;" onclick="moCuaSoTuyChon('cua-so-tai-khoan', 'mo-cua-so-tuy-chon')"></i>
                                    &nbsp;
                                @elseif(Auth::user()->role == 'Partner')
                                    <a class="text-decoration-none color-white" href="{{ route('partner.thong-tin-doi-tac.index') }}">{{ Auth::user()->email }}</a>
                                    &nbsp;
                                @elseif(Auth::user()->role == 'Admin')
                                    <a class="text-decoration-none color-white" href="{{ route('admin.index') }}">Admin</a>
                                    &nbsp;
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex">
                            <a class="color-white" href="{{ route('dang-nhap') }}"><i class="fas fa-bookmark cursor fs-4 hover-opacity-08-05" title="Đã lưu"></i></a>&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('dang-nhap') }}" class="color-white"><i class="fas fa-user cursor fs-4 hover-opacity-08-05" title="Đăng nhập"></i></a>&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('dang-nhap') }}"><button class="button button-radius-20 back-color-main-2 color-white hover-opacity-08-05" >Đăng nhập</button></a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="nav-item cursor" id="toggle" onclick="toggleMenu()">
                <span class="material-symbols-outlined" id="icon-toggle" style="font-size: 30px;">
                    menu
                </span>
            </div>
        </div>
    </div>

    <div class="cua-so-tai-khoan back-color-white box-shadow-10-black" id="cua-so-tai-khoan">
        <h4 class="padding-10px-2percent">Tài khoản</h4>
        <a class="color-black text-decoration-none" href="{{ route('tai-khoan.quan-ly-tai-khoan') }}">
            <h5 class="cursor"><i class="fas fa-id-card"></i> Hồ sơ</h5>
        </a>
        <a class="color-black text-decoration-none" href="{{ route('dat-phong.dat-phong-cua-toi') }}">
            <h5 class="cursor"><i class="fas fa-clipboard-list"></i> Đặt phòng của tôi</h5>
        </a>
        <br>
        <a href="#" class="text-decoration-none color-black" id="dang-xuat-1">
            <h5 class="cursor dang-xuat" onclick="dangXuat('{{ route('dang-xuat') }}')"><i class="fas fa-sign-out-alt"></i> Đăng xuất</h5>
        </a>
    </div>
</div>