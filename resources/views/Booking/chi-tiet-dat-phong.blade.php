@extends('Layouts.Booking.booking_layout')

@section('css')
    <link href="{{ asset('assets/css/datepicker/datepicker.css') }}" rel="stylesheet" />
@endsection

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
            <i class="fas fa-angle-right"></i>
            &nbsp;
        </span>

        <span>
            <a class="text-decoration-none" href="">
                <span>
                    {{ $title }}
                </span>
            </a>
        </span>
    </div>
    <hr>

    <div id="message">
        
    </div>

    <div id="book" class="book box-shadow-10-black border-radius-10 p-1">
        {{ checkBookingStatus($booking->my_booking_status) }}
        <p><i class="fas fa-clock"></i> <span>{{ date_format(date_create($booking->created_at), "H:i d-m-Y") }}</span></p>
        <a class="text-decoration-none color-black" href="{{ route('cho-o.chi-tiet-cho-o', ['id' => $booking->hotel_id]) }}">
            <div class="flex cursor">
                <div class="width-10-percent hover-opacity-08-05">
                    <img class="border-radius-10" src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $booking->hotel_thumbnail }}" alt="" width="100%">
                </div>
                <div class="p-1">
                    <h4>{{ $booking->hotel_name }}</h4>
                    <span>
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $booking->hotel_address }}
                    </span>
                </div>
            </div>
        </a>
        <hr>
        @if ($booking->my_booking_status == 'Chờ xác nhận' || $booking->my_booking_status == 'Đang xử lý')
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
                        @foreach ($bookingHotelList as $item)
                            <tr>
                                <td class="room-id" data-room-id="{{ $item->rooms_hotels_id }}">{{ $item->rooms_hotel_name }}</td>
                                <td class="price-hotel" data-price="{{ $item->room_hotel_price }}">{{ number_format($item->room_hotel_price, 0, ',', '.') }} ₫</td>
                                <td>
                                    <button type="button" class="button fs-5 hover-opacity-08-05" onclick="truSoLuong('so-luong-phong-{{ $item->rooms_hotels_id }}')">-</button>
                                    <span class="so-luong-phong" id="so-luong-phong-{{ $item->rooms_hotels_id }}">{{ $item->my_bookings_hotel_rooms_number }}</span>
                                    <button type="button" class="button fs-5 hover-opacity-08-05" onclick="congSoLuong('so-luong-phong-{{ $item->rooms_hotels_id }}', 'room-number-{{ $item->rooms_hotels_id }}')">+</button>
                                </td>
                                <td><button type="button" class="button color-white hover-opacity-08-05 bg-warning" onclick="dieuHuong('{{ route('dat-phong.xoa-phong', ['bookingId' => $booking->my_booking_id, 'roomId' => $item->rooms_hotels_id]) }}')"><i class="fas fa-minus"></i></button></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <h6 id="room-number-id-{{ $item->rooms_hotels_id }}"></h6>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="flex">
                    <b>Giá cho <span id="so-dem">{{ $booking->my_booking_day }}</span> đêm:&nbsp;</b>
                    <span id="all-price" data-all-price="">
                        {{ number_format($booking->my_booking_pay, 0, ',', '.') }} ₫
                    </span>
                </p>
                <label for="">Ngày nhận phòng:</label>
                <input type="date" id="timeCheckIn" class="form-control" value="{{ $booking->my_booking_checkin }}" readonly/>
                <label for="">Ngày trả phòng:</label>
                <input type="date" id="timeCheckOut" class="form-control" value="{{ $booking->my_booking_checkout }}" readonly/><br><br>
                
                <button type="button" class="button back-color-main-1 color-white hover-opacity-08-05" onclick="capNhatDatPhong('{{ route('dat-phong.cap-nhat-dat-phong', ['id' => $booking->my_booking_id]) }}')">
                    Lưu lại
                </button>
                @if ($booking->my_booking_status == 'Chờ xác nhận')
                    <button type="button" class="button bg-success color-white hover-opacity-08-05" onclick="dieuHuong('{{ route('dat-phong.xac-nhan-dat-phong', ['id' => $booking->my_booking_id]) }}')">
                        <i class="fas fa-check-square"></i> Xác nhận đặt phòng
                    </button>
                @endif
                <button type="button" class="button bg-danger color-white hover-opacity-08-05" onclick="xoaDatPhong('{{ route('dat-phong.xoa-dat-phong', ['id' => $booking->my_booking_id] ) }}')">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </form>
        @else
            <h5>Danh sách đặt phòng</h5>
            <form>
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                <table class="table">
                    <thead>
                        <tr>
                            <th>Loại phòng</th>
                            <th>Giá 1 đêm</th>
                            <th>Số lượng phòng</th>
                        </tr>
                    </thead>
                    <tbody id="books-list">
                        @foreach ($bookingHotelList as $item)
                            <tr>
                                <td class="room-id" data-room-id="{{ $item->rooms_hotels_id }}">{{ $item->rooms_hotel_name }}</td>
                                <td class="price-hotel" data-price="{{ $item->room_hotel_price }}">{{ number_format($item->room_hotel_price, 0, ',', '.') }} ₫</td>
                                <td>
                                    <span class="so-luong-phong" id="so-luong-phong-{{ $item->rooms_hotels_id }}">1</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="flex">
                    <b>Tổng:&nbsp;</b>
                    <span id="all-price" data-all-price="">
                        {{ number_format($booking->my_booking_pay, 0, ',', '.') }} ₫
                    </span>
                </p>
                <label for="">Ngày nhận phòng: </label><span> {{ $booking->my_booking_checkin }}</span>
                <br>

                @if ($booking->my_booking_status == 'Đã xử lý')
                    <button type="button" class="button bg-danger color-white hover-opacity-08-05" onclick="huyDatPhong('{{ route('dat-phong.huy-dat-phong', ['id' => $booking->my_booking_id] ) }}')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                @endif
            </form>
        @endif
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/hotel.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/bootstrap-datepicker.js') }}"></script>
    <script>
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

        kTraTatCaSoPhongConLai();
    </script>
@endsection