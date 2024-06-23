@php
    $checkLogin = Auth::check();
@endphp
@extends('Layouts.Main.main_layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/hotel.css') }}">
    <link href="{{ asset('assets/css/datepicker/datepicker.css') }}" rel="stylesheet" />
@endsection

@section('content')
    @if (!empty($hotel))
        <div class="redirect-page p-2">
            <span>
                <a class="text-decoration-none" href="{{ route('index') }}">
                    <span>
                        Trang chủ
                    </span>
                </a>
                &nbsp;
                <i class="fas fa-angle-right"></i>
                &nbsp;
            </span>

            <span>
                <a class="text-decoration-none" href="{{ route('cho-o.index') }}">
                    <span>
                        Chỗ ở
                    </span>
                </a>
                &nbsp;
                <i class="fas fa-angle-right"></i>
                &nbsp;
            </span>

            <span>
                <a class="text-decoration-none" href="{{ route('cho-o.danh-muc', ['id' => $hotel->category_id]) }}">
                    <span>
                        {{ $hotel->category_name }}
                    </span>
                </a>
                &nbsp;
                <i class="fas fa-angle-right"></i>
                &nbsp;
            </span>
            <span>
                <a class="text-decoration-none" href="{{ route('cho-o.tim-kiem-cho-o') }}?dia_diem={{ $hotel->hotel_area }}">
                    <span>
                        {{ $hotel->hotel_area }}
                    </span>
                </a>
                &nbsp;
                <i class="fas fa-angle-right"></i>
                &nbsp;
            </span>
            <span>
                <a class="text-decoration-none" href="">
                    <span>
                        {{ $hotel->hotel_name }}
                    </span>
                </a>
            </span>
        </div>
        <hr>

        <div class="hotel">
        {{-- Infor hotel --}}
            <div class="infor-hotel flex flex-wrap p-2">
                <div class="left border border-radius-10 p-1">
                    <h4>{{ $hotel->hotel_name }}</h4>
                    <h6>
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $hotel->hotel_address }}
                    </h6>
                    <div class="flex flex-between">
                        <span class="flex">
                            <h6 class="text-warning">
                                @for ($i = 0; $i < $hotel->hotel_star; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                {{ $hotel->hotel_star }}
                            </h6>
                            &nbsp;&nbsp;&nbsp;
                            <h6>
                                <span class="color-main-1">{{ $hotel->hotel_rate_point }}/10</span>({{ $hotel->hotel_number_rate }})
                            </h6>
                        </span>

                        <span>
                            @if ($checkLogin)
                                @if($checkSaveHotel && $checkSaveHotel != null)
                                    <i title="Bỏ lưu" class="fas fa-bookmark fs-3 cursor text-warning" id="unsave-{{ $hotel->hotel_id }}" onclick="luuChoO('{{ $hotel->hotel_id }}', '{{ route('danh-gia-va-da-luu.luu-cho-o') }}', '{{ route('danh-gia-va-da-luu.bo-luu-cho-o', ['id' => $hotel->hotel_id]) }}', '{{ csrf_token() }}', this)"></i>
                                @else
                                    <i title="Lưu lại" class="far fa-bookmark fs-3 cursor text-warning" id="save-{{ $hotel->hotel_id }}" onclick="luuChoO('{{ $hotel->hotel_id }}', '{{ route('danh-gia-va-da-luu.luu-cho-o') }}', '{{ route('danh-gia-va-da-luu.bo-luu-cho-o', ['id' => $hotel->hotel_id]) }}', '{{ csrf_token() }}', this)"></i>
                                @endif
                            @else
                                <a href="{{ route('dang-nhap') }}"><i class="far fa-bookmark fs-3 cursor text-warning"></i></a>
                            @endif
                            &nbsp;&nbsp;&nbsp;
                            <i class="fas fa-share-alt fs-3 cursor color-main-1"></i>
                        </span>
                    </div>
                    <hr>

                    <span class="flex">
                        <span class="color-white p-1 back-color-green">{{ $hotel->category_name }}</span>
                        <div style="width: 0;height: 0;border-left: 16px solid green;border-bottom: 16px solid green;border-right: 16px solid white;border-top: 16px solid green;">
                        </div>
                    </span>
                    <div class="flex flex-between">
                        <div id="rooms-hotels-infor">
                                
                        </div>
                    </div>
                    <hr>
                    
                    <div>
                        <h6><del>{{ number_format($hotel->hotel_old_price, 0, ',', '.') }} VND</del></h6>
                        <h5 class="text-primary">Từ {{ number_format($hotel->hotel_new_price, 0, ',', '.') }} VND</h5>
                        <h6>Đã bao gồm thuế và phí</h6>
                    </div>

                    <a href="#book">
                        <button class="button width-100-percent color-white back-color-main-1 hover-opacity-08-05">
                            Đặt phòng ngay&nbsp;&nbsp;&nbsp;<i class="fas fa-chevron-right"></i>
                        </button>
                    </a>
                </div>

                <div class="images-hotel">
                    <div class="image text-center border border-radius-10">
                        <img class="p-1" src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $hotel->hotel_thumbnail }}" alt="" height="100%" id="image-hotel-main">
                    </div>

                    <div class="library-images filtering" style="width: 80%;margin: auto;">
                        @foreach ($imagesHotelList as $item)
                            <div class="border p-1">
                                <img class="cursor" src="{{ asset('assets/img/hotels/images') }}/{{ $item->images_hotel_file_name }}" alt="" width="100%" onclick="openImageHotel(this)">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr>

        {{-- Description & booking --}}
            <div class="p-2 description-and-book">
            {{-- Description --}}
                <div class="description-hotel p-1">
                    <h4 class="width-60-percent border-bottom-1">Mô tả chỗ ở:</h4>
                    <p>{{ $hotel->hotel_description }}</p>
                </div>
            
            {{-- Booking --}}
                <div id="book" class="book box-shadow-10-black border-radius-10 p-1">
                    <h4 class="width-60-percent border-bottom-1">Đặt phòng ngay</h4>
                    @if ($checkLogin)
                        <h5>Danh sách phòng:</h5>
                        <table class="table" id="rooms-list">
                            
                        </table>
                        <hr>
                        <h5>Danh sách đặt phòng</h5>
                        <form>
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Loại phòng</th>
                                        <th>Giá 1 đêm</th>
                                        <th>Số lượng phòng</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="books-list">
                                    
                                </tbody>
                            </table>
                            <p class="flex">
                                <b>Giá cho <span id="so-dem">0</span> đêm:&nbsp;</b>
                                <span id="all-price" data-all-price="0">
                                    0 ₫
                                </span>
                            </p>
                            <label for="">Ngày nhận phòng:</label>
                            <input type="date" id="timeCheckIn" class="form-control" onblur="tinhTongTien()" readonly />
                            <label for="">Ngày trả phòng:</label>
                            <input type="date" id="timeCheckOut" class="form-control" oninput="tinhTongTien()" readonly/><br><br>
                           
                            <button type="button" class="button back-color-main-1 color-white hover-opacity-08-05" onmouseover="tinhTongTien()" onclick="datPhong('{{ route('dat-phong.dat-phong') }}', '{{ request()->id }}')">
                                Đặt phòng
                            </button>
                        </form>
                    @else
                        <h5><a class="text-decoration-none" href="{{ route('dang-nhap') }}">Đăng nhập</a> để đặt phòng</h5>
                    @endif
                </div>
            </div>
            <hr>

        {{-- Rate --}}
            <div class="rates-hotel p-2">
                <h4 class="width-60-percent border-bottom-1">Đánh giá về chỗ ở này</h4>

            {{-- Rates list --}}
                <div class="rates-list responsive width-80-percent">
                    @foreach ($ratesList as $item)
                        <div class="p-2">
                            <div class="p-1 rate border border-radius-10">
                                <div class="user p-2">
                                    <div class="avatar back-color-main-1">
                                        <i class="fas fa-user color-white fs-4 p-2"></i>
                                    </div>
                                    <h5>{{ $item->name }}</h5>
                                </div>
                                <h6 class="color-main-1">Điểm đánh giá: {{ $item->rate_point }}/10</h6>
                                <h6 class="comment">{{ $item->rate_comment }}</h6>
                                <h6 class="color-main-2"><i class="fas fa-clock"></i>&nbsp;Vào {{ $item->created_at }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
                <br>
                <div class="text-center">
                    <button class="button color-white back-color-main-1 hover-opacity-08-05" onclick="setTranslateAnimationRight('all-rates')">Xem toàn bộ đánh giá</button>
                </div>
                <br>

            {{-- Rate form --}}
                <div class="rate-form border-radius-10 box-shadow-10-black p-2">
                        <h4>Để lại đánh giá về chỗ ở này</h4>
                        <hr>
                    @if ($checkLogin)
                        @if($checkRateHotel && $checkRateHotel != null)
                            <div class="alert alert-success text-center" id="alert-login" role="alert">
                                <h4><i class="fas fa-clipboard-check"></i>&nbsp;Bạn đã đánh giá chỗ ở này!</h4>
                            </div>

                            {{-- Show rated --}}
                            <div class="border p-1 border-radius-10">
                                <h5>Đánh giá của bạn:</h5>
                                <hr>
                                <h6 class="color-main-1"><b>Điểm đánh giá:</b> {{ $rate->rate_point }}/10</h6>
                                <h6><b class="color-main-1">Bình luận: </b>{{ $rate->rate_comment }}</h6>
                                <h6 class="color-main-2"><i class="fas fa-clock"></i> {{ $rate->created_at }}</h6>
                                <hr>
                                <div class="flex">
                                    <h5 class="cursor text-primary" onclick="moCuaSo('cua-so-sua-danh-gia', 'nen-cua-so-sua-danh-gia')">Sửa</h5>&nbsp;&nbsp;&nbsp;
                                    <h5 class="cursor text-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) dieuHuong('{{ route('danh-gia-va-da-luu.xoa-danh-gia', ['id' => $rate->hotel_id]) }}')">Xóa</h5>
                                </div>
                            </div>

                            {{-- Edit rate --}}
                            <div class="cua-so cua-so-60 p-3 box-shadow-10-black" id="cua-so-sua-danh-gia">
                                <h4>Chỉnh sửa đánh giá:</h4>
                                <hr>
                                <p class="text-center">Điểm đánh giá</p>
                                <div class="rate-stars fs-1 text-center cursor text-warning">
                                    @for ($i = 1; $i <= $rate->rate_point; $i++)
                                        <i class="fas fa-star rate-star" onclick="rateStar({{ $i }})"></i>
                                    @endfor
                                    @if ($rate->rate_point < 10)
                                        @for ($i = $rate->rate_point + 1; $i <= 10; $i++)
                                            <i class="far fa-star rate-star" onclick="rateStar({{ $i }})"></i>
                                        @endfor
                                    @endif
                                </div>
                                <p class="fs-3 text-center text-warning" id="rate-number-star">{{ $rate->rate_point }}</p>
                                <form action="{{ route('danh-gia-va-da-luu.sua-danh-gia', ['id' => $hotel->hotel_id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="rate-number-star-input" type="number" name="rate_point" value="{{ $rate->rate_point }}">
                                    <label for="">Bình luận:</label>
                                    <textarea class="width-100-percent textarea" maxlength="1000" name="comment">{{ $rate->rate_comment }}</textarea>
                                    <input type="hidden" name="rate_time" id="rate_time_01">
                                    <br><br>
                                    <button type="reset" class="button back-color-main-2 color-white hover-opacity-08-05">Đặt lại</button>
                                    <button class="button back-color-main-1 color-white hover-opacity-08-05" type="submit" onclick="document.getElementById('rate_time_01').value = getThoiGian()">Gửi đánh giá</button>    
                                </form>
                            </div>
                            <div class="nen-cua-so" id="nen-cua-so-sua-danh-gia" onclick="dongCuaSo('cua-so-sua-danh-gia', 'nen-cua-so-sua-danh-gia')"></div>
                        @else
                            <p class="text-center">Điểm đánh giá</p>
                            <div class="rate-stars fs-1 text-center cursor text-warning">
                                <i class="fas fa-star rate-star" onclick="rateStar(1)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(2)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(3)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(4)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(5)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(6)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(7)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(8)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(9)"></i>
                                <i class="fas fa-star rate-star" onclick="rateStar(10)"></i>
                            </div>
                            <p class="fs-3 text-center text-warning" id="rate-number-star">10</p>
                            <form action="{{ route('danh-gia-va-da-luu.danh-gia', ['id' => $hotel->hotel_id]) }}" method="POST">
                                @csrf
                                <input class="hidden" id="rate-number-star-input" type="number" name="rate_point" value="10">
                                <label for="">Bình luận:</label>
                                <textarea class="width-100-percent textarea" maxlength="1000" name="comment"></textarea>
                                <input type="hidden" name="rate_time" id="rate_time">
                                <br><br>
                                <button type="reset" class="button back-color-main-2 color-white hover-opacity-08-05">Đặt lại</button>
                                <button class="button back-color-main-1 color-white hover-opacity-08-05" type="submit" onclick="document.getElementById('rate_time').value = getThoiGian()">Gửi đánh giá</button>    
                            </form>
                        @endif
                    @else
                        <h5><a class="text-decoration-none" href="{{ route('dang-nhap') }}">Đăng nhập</a> để đánh giá</h5>
                    @endif
                </div>

            {{-- All rates list --}}
                <div class="all-rates back-color-white box-shadow-10-black" id="all-rates">
                    <i class="fas fa-times cursor back-color-main-1 p-2 fs-3 color-white close position-absolute hover-opacity-08-05" onclick="setTranslateAnimationRight('all-rates')"></i>
                    <div class="content p-2">
                    {{-- Rate hotel Infor --}}
                        <div class="rate-score">
                            <h4>Điểm đánh giá trung bình</h4>
                            <h5>
                                <span class="color-main-1">{{ $hotel->hotel_rate_point }}</span>/10
                                ({{ $hotel->hotel_number_rate }} đánh giá)
                            </h5>
                        </div>
                        <hr>
                    
                    {{-- Rates List --}}
                        <div class="rates">
                        {{-- Order By --}}
                            <div class="flex flex-between">
                                <h4>Đánh giá của khách hàng</h4>
                                <select class="form-select text-center width-fit-content" name="" id="sap-xep-danh-gia" onchange="locDanhGia(1, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">
                                    <option value="danh_gia_tot_nhat">Đánh giá tốt nhất</option>
                                    <option value="moi_hon">Mới hơn</option>
                                    <option value="cu_hon">Cũ hơn</option>
                                </select>
                            </div>
                        
                            <div id="all-rates-list">
                                {{-- List --}}
                                    <div class="rates-list">
                                        @foreach ($ratesList as $item)
                                            <div class="p-2">
                                                <div class="p-1 rate border border-radius-10">
                                                    <div class="user p-2">
                                                        <div class="avatar back-color-main-1">
                                                            <i class="fas fa-user color-white fs-4 p-2"></i>
                                                        </div>
                                                        <h5>{{ $item->name }}</h5>
                                                    </div>
                                                    <h6 class="color-main-1">Điểm đánh giá: {{ $item->rate_point }}/10</h6>
                                                    <h6>{{ $item->rate_comment }}</h6>
                                                    <h6 class="color-main-2"><i class="fas fa-clock"></i>&nbsp;Vào {{ $item->created_at }}</h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                        
                                {{-- Pagination --}}
                                    @php
                                        $pageNumber = ceil($ratesList->total()/1);
                                        $currentPage = $ratesList->currentPage();
                                    @endphp
                                    
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            @if ($pageNumber <= 3)
                                                @for ($i = 1; $i <= $pageNumber; $i++)
                                                    <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                                        <p class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">{{ $i }}</p>
                                                    </li>
                                                @endfor
                                            @else
                                                @if ($currentPage != 1)
                                                    <li class="page-item cursor">
                                                        <p class="page-link" onclick="locDanhGia(1, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">Trang đầu</p>
                                                    </li>
                                                    <li class="page-item cursor">
                                                        <p class="page-link" onclick="locDanhGia({{ $currentPage - 1 }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">
                                                            <i class="fas fa-angle-left"></i>
                                                        </p>
                                                    </li>
                                                @endif
                    
                                                @if ($currentPage >=3)
                                                    @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                                                        @if ($i <= $pageNumber)
                                                            <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                                                <p class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')",>{{ $i }}</p>
                                                            </li>
                                                        @endif
                                                    @endfor
                                                @else
                                                    @for ($i = 1; $i <= 3; $i++)
                                                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                                            <p class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">{{ $i }}</p>
                                                        </li>
                                                    @endfor
                                                @endif
                                                
                                                @if ($currentPage != $pageNumber)
                                                    <li class="page-item cursor">
                                                        <p class="page-link" onclick="locDanhGia({{ $currentPage + 1 }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">
                                                            <i class="fas fa-angle-right"></i>
                                                        </p>
                                                    </li>
                                                    <li class="page-item cursor">
                                                        <p class="page-link" onclick="locDanhGia({{ $pageNumber }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">Trang cuối</p>
                                                    </li>
                                                @endif
                                            @endif
                                        </ul>
                                    </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="same-hotels">
                <h4 class="width-60-percent border-bottom-1">Chỗ ở tương tự</h4>
                <div class="responsive ds-cho-nghi-noi-bat">
                    @foreach ($sameHotelsList as $item)
                        <div class="cho-nghi-noi-bat back-color-white p-1">
                            <div class="box-shadow-10-black border-radius-10 position-relative cursor">
                                <i title="Lưu lại" class="far fa-bookmark fs-4 text-warning position-absolute save-hotel"></i>
                                <a href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $item->hotel_id]) }}" class="text-decoration-none color-black a-none-hover">
                                    <div class="hinh-thu-nho">
                                        <img class="border-radius-10" src="{{ asset('assets/img/hotels/thumbnail') }}/demo-list.jpg" alt="" width="100%" height="200px">
                                    </div>
                                    <div class="noi-dung p-1">
                                        <h3 class="text-center">{{ $item->hotel_name }}</h3>
                                        <div class="text-center flex flex-between">
                                            <div>
                                                <i class="fas fa-star color-main-2"></i>
                                                <i class="fas fa-star color-main-2"></i>
                                                <i class="fas fa-star color-main-2"></i>
                                                <i class="color-main-2">{{ $item->hotel_star }}</i>
                                            </div>
                                            <div>
                                                <h6 class="color-main-1">{{ $item->hotel_rate_point }}/10(<span class="color-black">79</span>)</h6>
                                            </div>
                                        </div>
                                        <h6>
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $item->hotel_address }}
                                        </h6>
                                        <div class="flex">
                                            <h6><del>{{ number_format($item->hotel_old_price, 0, ',', '.') }} VND</del></h6>&nbsp;&nbsp;&nbsp;
                                            <h6>Từ&nbsp;</h6>
                                            <h6 class="color-main-2">{{ number_format($item->hotel_new_price, 0, ',', '.') }} VND</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-primary" role="alert">
            <h4>Không tìm thấy kết quả để hiển thị!</h4>
        </div>
    @endif
@endsection

@section('js')
    <script src="{{ asset('assets/js/hotel.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/bootstrap-datepicker.js') }}"></script>
    <script>
        document.getElementById('timeCheckIn').value = getNgayThang();
        document.getElementById('timeCheckOut').value = getNgayTiepTheo();
        document.querySelector('body').addEventListener("click", loadRoomsHotel({{ request()->id }}));
        // Date picker
        $(function () {
            'use strict';
            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            var checkin = $('#timeCheckIn').datepicker({
                onRender: function (date) {
                    return date.valueOf() < now.valueOf() ? 'disabled' : '';
                }
            }).on('changeDate', function (ev) {
                kTraTatCaSoPhongConLai();
                tinhTongTien();
                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    var newDate = new Date(ev.date)
                    newDate.setDate(newDate.getDate() + 1);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('#timeCheckOut')[0].focus();
            }).data('datepicker');
            var checkout = $('#timeCheckOut').datepicker({
                onRender: function (date) {
                    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
                }
            }).on('changeDate', function (ev) {
                kTraTatCaSoPhongConLai();
                tinhTongTien();
                checkout.hide();
            }).data('datepicker');
        });
    </script>
@endsection