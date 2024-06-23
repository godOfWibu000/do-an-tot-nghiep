@extends('Layouts.Booking.booking_layout')

@php
    if (!empty(request()->page))
        $page = '?page=' . request()->page;
    else 
        $page = '?page=1';
@endphp

@section('content')
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
            <a class="text-decoration-none" href="{{ route('dat-phong.dat-phong-cua-toi') }}">
                <span>
                    Đặt phòng của tôi
                </span>
            </a>
            &nbsp;
        </span>
    </div>
    <hr>

    <h3>Danh sách đặt phòng</h3>
    <div class="flex flex-between">
        <div class="p-2 width-40-percent">
            <h5>Lọc theo trạng thái:</h5>
            <span class="position-absolute p-2">
                <i class="fas fa-filter"></i>
            </span>
            <select class="form-select text-center" name="" id="loc-trang-thai" onchange="dieuHuong('{{ route('dat-phong.dat-phong-cua-toi') }}{{ $page }}&trang_thai=' + this.value +  '&sap_xep=' + document.getElementById('sap-xep').value)">
                @if (empty(request()->trang_thai) || request()->trang_thai == 'tat_ca')
                    <option value="tat_ca" selected>Tất cả</option>
                @else
                    <option value="tat_ca">Tất cả</option>
                @endif
                @foreach (getFilterBookingsStatus() as $item)
                    @if (request()->trang_thai == $item)
                        <option value="{{ $item }}" selected>{{ $item }}</option>
                    @else
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="p-2 width-40-percent">
            <h5>Sắp xếp</h5>
            <span class="material-symbols-outlined position-absolute p-2">
                sort
            </span>
            
            <select class="form-select text-center" name="" id="sap-xep" onchange="dieuHuong('{{ route('dat-phong.dat-phong-cua-toi') }}{{ $page }}&trang_thai=' + document.getElementById('loc-trang-thai').value +  '&sap_xep=' + this.value)">
                @if (request()->sap_xep == 'ASC')
                    <option value="ASC">Cũ hơn</option>
                    <option value="DESC">Mới hơn</option>
                @else
                    <option value="DESC">Mới hơn</option>
                    <option value="ASC">Cũ hơn</option>
                @endif
            </select>
        </div>
    </div>
    <hr>

    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
    @endif
    
    @if (count($bookingsList) != 0)
        <div class="my-bookings-list">
            @foreach ($bookingsList as $key => $value)
                <div class="my-booking box-shadow-10-black border-radius-10 p-2">
                    {{ checkBookingStatus($value->my_booking_status) }}
                    <p><i class="fas fa-clock"></i> <span>{{ date_format(date_create($value->created_at), "H:i d-m-Y") }}</span></p>
                    <a class="text-decoration-none color-black" href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $value->hotel_id]) }}">
                        <div class="flex cursor">
                            <div class="width-10-percent hover-opacity-08-05">
                                <img class="border-radius-10" src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $value->hotel_thumbnail }}" alt="" width="100%">
                            </div>
                            <div class="p-1">
                                <h4>{{ $value->hotel_name }}</h4>
                                <span>
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $value->hotel_address }}
                                </span>
                            </div>
                        </div>
                    </a>
                    <hr>
                    <div class="rooms-list">
                        <h5>Danh sách đặt phòng</h5>
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
                                @foreach ($bookingHotelList[$key] as $item)
                                    <tr>
                                        <td>{{ $item->rooms_hotel_name }}</td>
                                        <td>{{ number_format($item->room_hotel_price, 0, ',', '.') }} đ</td>
                                        <td>
                                            <span>{{ $item->my_bookings_hotel_rooms_number }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p class="flex">
                            <b>Giá cho <span>{{ $value->my_booking_day }}</span> đêm:&nbsp;</b>
                            <span>
                                {{ number_format($value->my_booking_pay, 0, ',', '.') }} ₫
                            </span>
                            <input type="hidden" id="all-price-input" name="tong_tien" value="0">
                        </p>
                        <p>Ngày nhận phòng: {{ date_format(date_create($value->my_booking_checkin),"d/m/Y") }}</p>
                        <p>Ngày trả phòng: {{ date_format(date_create($value->my_booking_checkout),"d/m/Y") }}</p>
                        <div class="flex flex-between">
                            <div>
                                <a href="{{ route('dat-phong.chi-tiet-dat-phong', ['id' => $value->my_booking_id]) }}">
                                    <button type="button" class="button back-color-main-1 color-white hover-opacity-08-05">
                                        <i class="fas fa-info-circle"></i> Xem chi tiết
                                    </button>
                                </a>
                                @if ($value->my_booking_status == 'Chờ xác nhận')
                                    <button type="button" class="button bg-success color-white hover-opacity-08-05" onclick="dieuHuong('{{ route('dat-phong.xac-nhan-dat-phong', ['id' => $value->my_booking_id]) }}')">
                                        <i class="fas fa-check-square"></i> Xác nhận đặt phòng
                                    </button>
                                @endif
                            </div>
                            <div>
                                @if ($value->my_booking_status == 'Đã xử lý')
                                    <button type="button" class="button bg-danger color-white hover-opacity-08-05" onclick="huyDatPhong('{{ route('dat-phong.huy-dat-phong', ['id' => $value->my_booking_id] ) }}')">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                @elseif($value->my_booking_status == 'Đang xử lý' || $value->my_booking_status == 'Chờ xác nhận')
                                    @if ($value->my_booking_status == 'Đang xử lý')
                                        <form method="POST" action="{{ route('vnpay-payment') }}">
                                            @csrf
                                            <button  class="btn btn-success" disabled>
                                                <i class="fas fa-money-check"></i> Thanh toán
                                            </button>
                                        </form>
                                    @endif
                                    <br>
                                    <button type="button" class="button bg-danger color-white hover-opacity-08-05" onclick="xoaDatPhong('{{ route('dat-phong.xoa-dat-phong', ['id' => $value->my_booking_id] ) }}')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>
    @else
        <div class="alert alert-success" role="alert">
            <h4>Không có đặt phòng nào gần đây! Hãy tiếp tục <a class="text-decoration-none" href="{{ route('index') }}">đặt chỗ</a>!</h4>
        </div>
    @endif

    {{ $bookingsList->links() }}

@endsection

@section('js')
    <script src="{{ asset('assets/js/hotel.js') }}"></script>
@endsection