@php
    $checkLogin = Auth::check();
@endphp
@extends('Layouts.Main.main_layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/search.css') }}">
@endsection

@section('content')
    @if (!empty($hotelsList))
    {{-- Filter --}}
        <div class="filter border-radius-10 p-3 box-shadow-10-black back-color-white" id="bo-loc">
            <i class="fas fa-caret-left cursor close color-white back-color-main-1" onclick="setTranslateAnimationLeft('bo-loc')"></i>
            <div class="filter-content">
                <h4>Lọc để tìm kiếm dễ dàng hơn:</h4>
                <hr>
                <form action="" method="GET">
                    <input type="hidden" name="dia_diem" id="" value="{{ request()->dia_diem }}">
                    <h5>Giá thuê tối thiểu:</h5>
                    <div>
                        <input type="range" name="gia_toi_thieu" id="filter-range-min" min="100000" max="10000000" value="{{ !empty(request()->gia_toi_thieu) ? request()->gia_toi_thieu : 200000 }}" step="100000" oninput="setFilterPrice('filter-price-min', 'filter-range-min')" onchange="setStatusFilterPriceMin(this)">
                        <label id="filter-price-min">{{ !empty(request()->gia_toi_thieu) ? number_format(request()->gia_toi_thieu, 0, ',', '.') : '200.000' }} ₫</label>
                    </div>
                    <h5>Giá thuê tối đa:</h5>
                    <div>
                        <input type="range" name="gia_toi_da" id="filter-range-max" min="100000" max="10000000" value="{{ !empty(request()->gia_toi_da) ? request()->gia_toi_da : 2000000 }}" step="100000" oninput="setFilterPrice('filter-price-max', 'filter-range-max')" onchange="setStatusFilterPriceMax(this)">
                        <label id="filter-price-max">{{ !empty(request()->gia_toi_da) ? number_format(request()->gia_toi_da, 0, ',', '.') : '2.000.000' }} ₫</label>
                    </div>
                    <hr>

                    <h5>Lọc theo khu vực:</h5>
                    <div>
                        @if (!empty(getChildAreas(request()->dia_diem)))
                            @foreach (getChildAreas(request()->dia_diem) as $item)
                                @php
                                    $checkChildAreaSelected = 1;
                                @endphp
                                @if (!empty(request()->khu_vuc))
                                    @foreach (request()->khu_vuc as $i)
                                        @if ($i ==  $item->child_area_id)
                                            <input type="checkbox" class="check-filter-child-area" name="khu_vuc[]" id="filter-check-child-area-{{ $item->child_area_id }}" value="{{ $item->child_area_id }}" checked>
                                            <label for="filter-check-child-area-{{ $item->child_area_id }}">{{ $item->child_area_name }}</label>
                                            <br>
                                            @php
                                                $checkChildAreaSelected = 0;
                                            @endphp
                                            @break
                                        @endif
                                    @endforeach
                                @endif
                                @if ($checkChildAreaSelected == 0)
                                    @continue
                                @endif
                                <input type="checkbox" class="check-filter-child-area" name="khu_vuc[]" id="filter-check-child-area-{{ $item->child_area_id }}" value="{{ $item->child_area_id }}">
                                <label for="filter-check-child-area-{{ $item->child_area_id }}">{{ $item->child_area_name }}</label>
                                <br>
                            @endforeach
                        @endif
                        <input type="checkbox" id="check-child-areas-all" onchange="chonTatCa('.check-filter-child-area', 'uncheck-child-areas-all')">
                        <label for="check-child-areas-all"><b>Chọn tất cả</b></label>
                        <br>
                        <input type="checkbox" id="uncheck-child-areas-all" onchange="boChonTatCa('.check-filter-child-area', 'check-child-areas-all')">
                        <label for="uncheck-child-areas-all"><b>Bỏ chọn tất cả</b></label>
                    </div>
                    <hr>

                    <h5>Lọc theo loại hình cho thuê:</h5>
                    <div>
                        @if (!empty(getCategories()))
                            @foreach (getCategories() as $item)
                                @php
                                    $checkCatSelected = 1;
                                @endphp

                                @if (!empty(request()->loai_hinh_cho_thue))
                                    @foreach (request()->loai_hinh_cho_thue as $i)
                                        @if ($i ==  $item->category_id)
                                            <input type="checkbox" class="check-filter-category" id="filter-check-category-{{ $item->category_id }}" name="loai_hinh_cho_thue[]" value="{{ $item->category_id }}" checked>
                                            <label for="filter-check-category-{{ $item->category_id }}">{{ $item->category_name }}</label>
                                            <br>
                                            @php
                                                $checkCatSelected = 0;
                                            @endphp
                                            @break
                                        @endif
                                    @endforeach
                                @endif
                                
                                @if ($checkCatSelected == 0)
                                    @continue
                                @endif
                                <input type="checkbox" class="check-filter-category" id="filter-check-category-{{ $item->category_id }}" name="loai_hinh_cho_thue[]" value="{{ $item->category_id }}">
                                <label for="filter-check-category-{{ $item->category_id }}">{{ $item->category_name }}</label>
                                <br>
                            @endforeach
                        @endif
                        <input type="checkbox" id="check-categories-all" onchange="chonTatCa('.check-filter-category', 'uncheck-categories-all')">
                        <label for="check-categories-all"><b>Chọn tất cả</b></label>
                        <br>
                        <input type="checkbox" id="uncheck-categories-all" onchange="boChonTatCa('.check-filter-category', 'check-categories-all')">
                        <label for="uncheck-categories-all"><b>Bỏ chọn tất cả</b></label>
                        <hr>
                    </div>
                    <button class="button back-color-main-1 color-white width-100-percent hover-opacity-08-05 submit-filter">Lọc dữ liệu</button>
                </form>
                <hr>
            </div>
        </div>
    
    {{-- Search content --}}
        <div class="search-content">
            <div class="flex flex-between">
                <h4 class="p-2">Danh sách chỗ nghỉ tại <b>{{ request()->dia_diem }}</b></h4>
                <span class="material-symbols-outlined cursor p-2" id="toggle-filter" onclick="setTranslateAnimationLeft('bo-loc')">tune</span>
            </div>

        {{-- Order By --}}
            <div class="p-2">
                <span class="material-symbols-outlined position-absolute p-2">
                    sort
                </span>
                <select class="form-select text-center" name="" id="" onchange="window.location = location.href.toString().split('&sap_xep=')[0] + '&sap_xep=' + this.value">
                    <option value="danh_gia_tot_nhat" {{ request()->sap_xep == 'danh_gia_tot_nhat' ? 'selected' : '' }}>Đánh giá tốt nhất</option>
                    <option value="gia_cao_hon" {{ request()->sap_xep == 'gia_cao_hon' ? 'selected' : '' }}>Giá thuê cao hơn</option>
                    <option value="gia_thap_hon" {{ request()->sap_xep == 'gia_thap_hon' ? 'selected' : '' }}>Giá thuê thấp hơn</option>
                </select>
            </div>
            <hr>

        {{-- Hotels list --}}
            @if (count($hotelsList) != 0)
            {{-- Hotel --}}
                <div class="hotels-list">
                    @foreach ($hotelsList as $key => $value)
                        <div class="hotel p-2 border-radius-10 box-shadow-10-black">
                            <div class="left position-relative">
                                @if ($checkLogin)
                                    @if($checkSaveHotels[$key] && $checkSaveHotels[$key] != null)
                                        <i title="Bỏ lưu" class="fas fa-bookmark fs-4 text-warning position-absolute save-hotel cursor" id="unsave-{{ $value->hotel_id }}" onclick="luuChoO('{{ $value->hotel_id }}', '{{ route('danh-gia-va-da-luu.luu-cho-o') }}', '{{ route('danh-gia-va-da-luu.bo-luu-cho-o', ['id' => $value->hotel_id]) }}', '{{ csrf_token() }}', this)"></i>
                                    @else
                                        <i title="Lưu lại" class="far fa-bookmark fs-4 text-warning position-absolute save-hotel cursor" id="save-{{ $value->hotel_id }}" onclick="luuChoO('{{ $value->hotel_id }}', '{{ route('danh-gia-va-da-luu.luu-cho-o') }}', '{{ route('danh-gia-va-da-luu.bo-luu-cho-o', ['id' => $value->hotel_id]) }}', '{{ csrf_token() }}', this)"></i>
                                    @endif
                                @else
                                    <a href="{{ route('dang-nhap') }}">
                                        <i title="Đăng nhập để lưu" class="far fa-bookmark fs-4 text-warning position-absolute save-hotel cursor"></i>
                                    </a>
                                @endif
                                <a href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $value->hotel_id ]) }}">
                                    <img class="border-radius-10 cursor hover-opacity-08-05" src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $value->hotel_thumbnail }}" alt="" width="100%">
                                </a>
                            </div>
                            <div class="right">
                                <span class="flex">
                                    <span class="color-white p-1 back-color-green">{{ $value->category_name }}</span>
                                    <div style="width: 0;height: 0;border-left: 16px solid green;border-bottom: 16px solid green;border-right: 16px solid white;border-top: 16px solid green;">
                                    </div>
                                </span>

                                <div class="flex flex-wrap">
                                    <a class="text-decoration-none color-black" href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $value->hotel_id ]) }}">
                                        <h5 class="title"><b>{{ $value->hotel_name }}</b></h5>
                                    </a>&nbsp;&nbsp;&nbsp;
                                    <span class="text-warning">
                                        <i class="fas fa-star"></i>
                                        {{ $value->hotel_star }}
                                    </span>&nbsp;&nbsp;&nbsp;
                                    <span class="text-primary">{{ $value->hotel_rate_point }}/10</span><span>(12)</span>
                                </div>
                                <span class="flex">
                                    <i class="fas fa-map-marker-alt"></i>&nbsp;
                                    <p>{{ $value->hotel_address }}</p>
                                </span>
                            </div>
                            <div class="information-hotel">
                                <div class="border border-radius-10 content flex flex-between flex-wrap">
                                    <div>
                                        <div>
                                            <span><i class="fas fa-angle-right color-main-1"></i> Phòng giường đơn</span><br>
                                            <span><i class="fas fa-angle-right color-main-1"></i> Phòng giường đôi</span>
                                        </div>
                                    </div>
                                    <div class="price">
                                        <h6><del>{{ number_format($value->hotel_old_price, 0, ',', '.') }} VND</del></h6>
                                        <h5 class="text-primary">Từ {{ number_format($value->hotel_new_price, 0, ',', '.') }} VND</h5>
                                        <h6>Đã bao gồm thuế</h6>
                                        <a href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $value->hotel_id]) }}">
                                            <button class="button back-color-main-1 color-white hover-opacity-08-05">Xem chi tiết&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-right"></i></button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    @endforeach
                </div>

                <br>

            {{-- Pagination --}}
                @php
                    $pageNumber = ceil($hotelsList->total()/10);
                    $currentPage = $hotelsList->currentPage();
                @endphp

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @if ($pageNumber <= 3)
                            @for ($i = 1; $i <= $pageNumber; $i++)
                                <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                    <p class="page-link" onclick="window.location = location.href.toString().split('&page=')[0] + '&page=' + {{ $i }}">{{ $i }}</p>
                                </li>
                            @endfor
                        @else
                            @if ($currentPage != 1)
                                <li class="page-item cursor">
                                    <p class="page-link" onclick="window.location = location.href.toString().split('&page=')[0] + '&page=1'">Trang đầu</p>
                                </li>
                                <li class="page-item cursor">
                                    <p class="page-link" onclick="window.location = location.href.toString().split('&page=')[0] + '&page=' + {{ $currentPage - 1 }}">
                                        <i class="fas fa-angle-left"></i>
                                    </p>
                                </li>
                            @endif

                            @if ($currentPage >=3)
                                @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                                    @if ($i <= $pageNumber)
                                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                            <p class="page-link" onclick="window.location = location.href.toString().split('&page=')[0] + '&page=' + {{ $i }}">{{ $i }}</p>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i <= 3; $i++)
                                    <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                        <p class="page-link" onclick="window.location = location.href.toString().split('&page=')[0] + '&page=' + {{ $i }}">{{ $i }}</p>
                                    </li>
                                @endfor
                            @endif
                            
                            @if ($currentPage != $pageNumber)
                                <li class="page-item cursor">
                                    <p class="page-link" onclick="window.location = location.href.toString().split('&page=')[0] + '&page=' + {{ $currentPage + 1 }}">
                                        <i class="fas fa-angle-right"></i>
                                    </p>
                                </li>
                                <li class="page-item cursor">
                                    <p class="page-link" onclick="window.location = location.href.toString().split('&page=')[0] + '&page=' + {{ $pageNumber }}">Trang cuối</p>
                                </li>
                            @endif
                        @endif
                    </ul>
                </nav>
            @else
                <div class="alert alert-info text-center" role="alert">
                    <h4>Không có kết quả để hiển thị! Hãy thử tìm kiếm một kết quả khác!</h4>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">
            <h4>Không tìm thấy kết quả để hiển thị! Tìm kiếm theo địa điểm bạn muốn đến ngay!</h4>
        </div>
    @endif

    <div style="clear: both;"></div>
@endsection

@section('js')
    <script>
        function setFilterPrice(idFilterPrice, idRange){
            const VND = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
            });
            let price = document.getElementById(idRange).value;
            document.getElementById(idFilterPrice).innerHTML = VND.format(price);
        }
        function setStatusFilterPriceMin(minPrice){
            let maxPrice = document.getElementById('filter-range-max');
            if(parseInt(minPrice.value) >= parseInt(maxPrice.value)){
                maxPrice.value = parseInt(minPrice.value) + 100000;
                setFilterPrice('filter-price-max', 'filter-range-max');
                if(parseInt(minPrice.value) == 10000000){
                    minPrice.value = parseInt(minPrice.value) - 100000;
                    setFilterPrice('filter-price-min', 'filter-range-min');
                }
            }
        }
        function setStatusFilterPriceMax(maxPrice){
            let minPrice = document.getElementById('filter-range-min');
            if(parseInt(maxPrice.value) <= parseInt(minPrice.value)){
                minPrice.value = parseInt(maxPrice.value) - 100000;
                setFilterPrice('filter-price-min', 'filter-range-min');

                if(parseInt(maxPrice.value) == 100000){
                    maxPrice.value = parseInt(maxPrice.value) + 100000;
                    setFilterPrice('filter-price-max', 'filter-range-max');
                }
            }
        }

        function chonTatCa(classCheckbox, checkBox){
            document.querySelectorAll(classCheckbox).forEach(element => {
                element.checked = true;
            });
            document.getElementById(checkBox).checked = false;
        }
        function boChonTatCa(classCheckbox, checkBox){
            document.querySelectorAll(classCheckbox).forEach(element => {
                element.checked = false;
            });
            document.getElementById(checkBox).checked = false;
        }
    </script>
@endsection