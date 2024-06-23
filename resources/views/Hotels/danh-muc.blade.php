@php
    $checkLogin = Auth::check();
@endphp
@extends('Layouts.Main.main_layout')

@section('content')
    <div>
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
                <a class="text-decoration-none" href="{{ route('cho-o.danh-muc', ['id' => request()->id]) }}">
                    <span>
                        {{ $title }}
                    </span>
                </a>
            </span>
        </div>
        <hr>

        <div class="cho-o-container">
            <h3 class="border-bottom-1 width-60-percent">{{ $title }}</h3>
            <br>
            <div class="ds-cho-nghi-noi-bat flex flex-wrap">
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
                                            <i class="fas fa-star"></i>
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

            {{ $hotelsList->links() }}
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
    </div>
@endsection