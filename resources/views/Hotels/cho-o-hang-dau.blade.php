@php
    $checkLogin = Auth::check();
@endphp
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