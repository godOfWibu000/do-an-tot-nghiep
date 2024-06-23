@extends('Layouts.Booking.booking_layout')

@section('content')
    <h3>Chỗ ở đã lưu</h3>
    <div class="p-2">
        <span class="material-symbols-outlined position-absolute p-2">
            sort
        </span>
        @if (!empty(request()->page))
            <select class="form-select text-center" name="" id="" onchange="window.location = location.href.toString().split('&sap_xep=')[0] + '&sap_xep=' + this.value">
        @else
        <select class="form-select text-center" name="" id="" onchange="window.location = location.href.toString().split('?sap_xep=')[0] + '?sap_xep=' + this.value">
        @endif
            @if (request()->sap_xep == 'ASC')
                <option value="ASC">Cũ hơn</option>
                <option value="DESC">Mới hơn</option>
            @else
                <option value="DESC">Mới hơn</option>
                <option value="ASC">Cũ hơn</option>
            @endif
        </select>
    </div>
    <hr>

    @if ($message = Session::get('successDelete'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('failDelete'))
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
    @endif

    @if (count($savesHotelList) != 0)
        <div class="">
            @foreach ($savesHotelList as $item)
                <div class="box-shadow-10-black border-radius-10 p-2">
                    <div class="flex">
                        <div class="width-10-percent">
                            <a href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $item->hotel_id ]) }}">
                                <img class="border-radius-10 cursor hover-opacity-08-05" src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $item->hotel_thumbnail }}" alt="" width="100%">
                            </a>
                        </div>
    
                        <div class="p-1">
                            <div class="flex flex-wrap">
                                <a class="text-decoration-none color-black" href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $item->hotel_id ]) }}">
                                    <h5 class="title"><b>{{ $item->hotel_name }}</b></h5>
                                </a>&nbsp;&nbsp;&nbsp;
                                <span class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    {{ $item->hotel_star }}
                                </span>&nbsp;&nbsp;&nbsp;
                                <span class="text-primary">{{ $item->hotel_rate_point }}/10</span><span>(12)</span>
                            </div>
                            <span class="flex">
                                <i class="fas fa-map-marker-alt"></i>&nbsp;
                                <p>{{ $item->hotel_address }}</p>
                            </span>
    
                            <div class="price">
                                <h6><del>{{ number_format($item->hotel_old_price, 0, ',', '.') }} VND</del></h6>
                                <h5 class="text-primary">Từ {{ number_format($item->hotel_new_price, 0, ',', '.') }} VND</h5>
                            </div>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $item->hotel_id]) }}">
                            <button class="button back-color-main-1 color-white hover-opacity-08-05">Xem chi tiết&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-right"></i></button>
                        </a>
                        <button class="button bg-danger color-white hover-opacity-08-05" onclick="if(confirm('Bạn có chắc chắn muốn xóa chỗ ở này khỏi danh sách đã lưu?')) dieuHuong('{{ route('danh-gia-va-da-luu.xoa-luu-cho-o', ['id' => $item->hotel_id]) }}')">Xóa&nbsp;&nbsp;&nbsp;<i class="fas fa-times"></i></button>
                    </div>
                </div>
                <hr>
                {{ $savesHotelList->links() }}
            @endforeach
        </div>
    @else
        <div class="alert alert-success" role="alert">
            <h4>Không có chỗ ở đã lưu nào gần đây! Hãy tiếp tục <a class="text-decoration-none" href="{{ route('index') }}">đặt chỗ</a>!</h4>
        </div>
    @endif

@endsection

@section('js')
    <script src="{{ asset('assets/js/hotel.js') }}"></script>
@endsection