@php
    $checkLogin = Auth::check();
@endphp
@extends('Layouts.Main.main_layout')

@section('content')
    <div class="content p-2">
        <div class="uu-dai-container">
            <div class="margin-bottom-20">
                <h3>Nhận vô vàn ưu đãi</h3>
                <h5>Khuyến mại, giảm giá và ưu đãi đặc biệt</h5>
            </div>
            <div class="flex flex-between flex-wrap">
                <div class="box-shadow-10-black color-white uu-dai uu-dai-1" style="background-image: url({{ asset('assets/img/hotels/khuyen-mai.jpg') }});">
                    <div class="padding-10px-2percent" style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.9) , rgba(255, 255, 255, 0.1));height: 100%;width: 100%;">
                        <h4>Khuyễn mại đặt phòng</h4>
                        <h5>Nhận những chương trình khuyễn mại mới nhất</h5>
                        <a href="{{ route('cho-o.khuyen-mai') }}">
                            <button class="button button-radius-10 back-color-main-1 color-white hover-opacity-08-05">Nhận ưu đãi</button>
                        </a>
                    </div>
                </div>
                <div class="box-shadow-10-black padding-10px-2percent uu-dai uu-dai-2" style="background-image: url({{ asset('assets/img/hotels/uu-dai.jpg') }});background-repeat: no-repeat;background-size: cover;">
                    <h4>Ưu đãi đặc biệt</h4>
                    <h5>Ưu đãi dành cho khách hàng</h5>
                    <a href="{{ route('cho-o.khuyen-mai') }}">
                        <button class="button button-radius-10 back-color-main-2 color-white hover-opacity-08-05">Bắt đầu ngay</button>
                    </a>
                </div>
            </div>
        </div>
        <hr>

        <div class="cho-o-container">
            <h3 class="border-bottom-1 width-60-percent">Chỗ ở nổi bật</h3>
            <br>

            <div class="responsive ds-cho-nghi-noi-bat">
                @foreach ($hotelsList as $key => $value)
                    <div class="cho-nghi-noi-bat back-color-white p-1">
                        <div class="box-shadow-10-black border-radius-10 position-relative cursor">
                            @if ($checkLogin)
                                @if($checkSaveHotels[$key] && $checkSaveHotels[$key] != null)
                                    <i title="Bỏ lưu" class="fas fa-bookmark fs-4 text-warning position-absolute save-hotel" id="unsave-{{ $value->hotel_id }}" onclick="luuChoO('{{ $value->hotel_id }}', '{{ route('danh-gia-va-da-luu.luu-cho-o') }}', '{{ route('danh-gia-va-da-luu.bo-luu-cho-o', ['id' => $value->hotel_id]) }}', '{{ csrf_token() }}', this)"></i>
                                @else
                                    <i title="Lưu lại" class="far fa-bookmark fs-4 text-warning position-absolute save-hotel" id="save-{{ $value->hotel_id }}" onclick="luuChoO('{{ $value->hotel_id }}', '{{ route('danh-gia-va-da-luu.luu-cho-o') }}', '{{ route('danh-gia-va-da-luu.bo-luu-cho-o', ['id' => $value->hotel_id]) }}', '{{ csrf_token() }}', this)"></i>
                                @endif
                            @else
                                <a href="{{ route('dang-nhap') }}">
                                    <i title="Đăng nhập để lưu" class="far fa-bookmark fs-4 text-warning position-absolute save-hotel"></i>
                                </a>
                            @endif
                            <a href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $value->hotel_id]) }}" class="text-decoration-none color-black a-none-hover">
                                <div class="hinh-thu-nho">
                                    <img class="border-radius-10" src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $value->hotel_thumbnail }}" alt="" width="100%" height="200px">
                                </div>
                                <div class="noi-dung p-1">
                                    <h3 class="text-center">{{ $value->hotel_name }}</h3>
                                    <div class="text-center flex flex-between">
                                        <div class="text-warning">
                                            @for ($i = 0; $i < $value->hotel_star; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            <i class="">{{ $value->hotel_star }}</i>
                                        </div>
                                        <div>
                                            <h6 class="color-main-1">{{ $value->hotel_rate_point }}/10(<span class="color-black">{{ $value->hotel_number_rate }}</span>)</h6>
                                        </div>
                                    </div>
                                    <h6>
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $value->hotel_address }}
                                    </h6>
                                    <div class="flex">
                                        <h6><del>{{ number_format($value->hotel_old_price, 0, ',', '.') }} VND</del></h6>&nbsp;&nbsp;&nbsp;
                                        <h6>Từ&nbsp;</h6>
                                        <h6 class="color-main-2">{{ number_format($value->hotel_new_price, 0, ',', '.') }} VND</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <br>
            <div class="text-center">
                <a href="{{ route('cho-o.index') }}">
                    <button class="button button-radius-10 back-color-main-1 color-white margin-auto hover-opacity-08-05">Xem tất cả...</button>
                </a>
            </div>
        </div>
        <hr>

        <div class="danh-muc-container">
            <h3 class="border-bottom-1 width-60-percent">Danh mục chỗ ở</h3>
            <div class="flex ds-danh-muc flex-wrap">
                @foreach (getCategories() as $item)
                    <div class="hover-opacity-08-05 cursor danh-muc position-relative">
                        <a href="{{ route('cho-o.danh-muc', ['id' => $item->category_id]) }}">
                            <h4 class="position-absolute p-2 color-white border-radius-10">{{ $item->category_name }}</h4>
                            <img class="border-radius-10" src="{{ asset('assets/img/categories') }}/{{ $item->category_image }}" alt="" width="100%">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <hr>

        <div class="diem-den-pho-bien-container">
            <h3 class="border-bottom-1 width-60-percent">Điểm đến phổ biến</h3>
            <div class="ds-diem-den">
                <div class="diem-den diem-den-1 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Hà Nội">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Hà Nội</h4>
                        <img class="border-radius-10" src="https://r-xx.bstatic.com/xdata/images/city/170x136/688853.jpg?k=f6427c8fccdf777e4bbc75fcd245e7c66204280181bea23350388c76c57348d1&o=" alt="" width="100%">
                    </a>
                </div>
                <div class="diem-den diem-den-2 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Hạ Long">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Hạ Long</h4>
                        <img class="border-radius-10" src="https://r-xx.bstatic.com/xdata/images/city/170x136/688853.jpg?k=f6427c8fccdf777e4bbc75fcd245e7c66204280181bea23350388c76c57348d1&o=" alt="" width="100%">
                    </a>
                </div>
                <div class="diem-den diem-den-3 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Huế">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Huế</h4>
                        <img class="border-radius-10" src="https://r-xx.bstatic.com/xdata/images/city/170x136/688886.jpg?k=91c47e49d89f3a4c2408a360bbbe8b08d11e35e3d6d253c7efb27b5ca4d40a61&o=" alt="" width="100%">
                    </a>
                </div>

                <div class="diem-den diem-den-4 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Đà Nẵng">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Đà Nẵng</h4>
                        <img class="border-radius-10" src="https://r-xx.bstatic.com/xdata/images/city/170x136/688844.jpg?k=02892d4252c5e4272ca29db5faf12104004f81d13ff9db724371de0c526e1e15&o=" alt="" width="100%">
                    </a>
                </div>
                <div class="diem-den diem-den-5 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Đà Lạt">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Đà Lạt</h4>
                        <img class="border-radius-10" src="https://r-xx.bstatic.com/xdata/images/city/170x136/688831.jpg?k=7b999c7babe3487598fc4dd89365db2c4778827eac8cb2a47d48505c97959a78&o=" alt="" width="100%">
                    </a>
                </div>

                <div class="diem-den diem-den-6 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Vũng Tàu">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Vũng Tàu</h4>
                        <img class="border-radius-10" src="https://q-xx.bstatic.com/xdata/images/city/170x136/688956.jpg?k=fc88c6ab5434042ebe73d94991e011866b18ee486476e475a9ac596c79dce818&o=" alt="" width="100%">
                    </a>
                </div>
                <div class="diem-den diem-den-7 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Hồ Chí Minh">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Hồ Chí Minh</h4>
                        <img class="border-radius-10" src="https://r-xx.bstatic.com/xdata/images/city/170x136/688893.jpg?k=d32ef7ff94e5d02b90908214fb2476185b62339549a1bd7544612bdac51fda31&o=" alt="" width="100%">
                    </a>
                </div>
                <div class="diem-den diem-den-8 back-color-main-1 border-radius-10 cursor hover-opacity-08-05 position-relative">
                    <a href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem=Phú Quốc">
                        <h4 class="padding-10px-2percent color-white position-absolute border-radius-10">Phú Quốc</h4>
                        <img class="border-radius-10" src="https://r-xx.bstatic.com/xdata/images/city/170x136/688844.jpg?k=02892d4252c5e4272ca29db5faf12104004f81d13ff9db724371de0c526e1e15&o=" alt="" width="100%">
                    </a>
                </div>
            </div>
        </div>
        <hr>
    </div>
@endsection