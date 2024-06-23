@extends('Layouts.Partner.partner_layout')

@section('redirect')
    <li><a href="{{ route('partner.quan-ly-dat-phong.index') }}">Quản lý đặt phòng</a></li>
    <li class="active">{{ $title }}</li>
@endsection

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
    <div>
        <h4>{{ $booking->hotel_name }} </h4>
        <div class="flex">
            <h5><i class="fas fa-clock"></i> {{ date_format(date_create($booking->created_at), "H:i d-m-Y") }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
            <h5>
                Đặt bởi: <a href=""><i class="fas fa-user-circle"></i> {{ $booking->name }}</h5></a>
            </h5>
        </div>
        <h5>Tổng thanh toán: {{ number_format($booking->my_booking_pay, 0, ',', '.') }} VND</h5>
        <h5>Ngày nhận phòng: {{ date_format(date_create($booking->my_booking_checkin),"d-m-Y") }}</h5>
        <h5>Ngày trả phòng: {{ date_format(date_create($booking->my_booking_checkout),"d-m-Y") }}</h5>
    </div>

    @if ($booking->my_booking_status == 'Đang xử lý')
        <h5>Trạng thái: 
            <span>
                <i class="fas fa-circle text-warning"></i>
                {{ $booking->my_booking_status }}
            </span>
            <span>
                <button class="btn btn-primary" title="Xác nhận" onclick="dieuHuong('{{ route('partner.quan-ly-dat-phong.xu-ly-dat-phong', ['id' => $booking->my_booking_id ]) }}')">
                    <i class="fas fa-check"></i>
                </button>
            </span>

            <button class="btn btn-danger" onclick="if(confirm('Xác nhận hết phòng!')) dieuHuong('{{ route('partner.quan-ly-dat-phong.het-phong', ['id' => $booking->my_booking_id]) }}')">
                Hết phòng
            </button>
        </h5>

        <hr>
        <div>
            <h4>Danh sách phòng:</h4>
            <table class="table">
                <tr>
                    <th>STT</th>
                    <th>Loại phòng</th>
                    <th>Giá phòng</th>
                    <th>Số phòng</th>
                </tr>
                @foreach ($bookingHotelList as $key => $value1)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value1->rooms_hotel_name }}</td>
                        <td>{{ number_format($value1->room_hotel_price, 0, ',', '.') }} VND</td>
                        <td>{{ $value1->my_bookings_hotel_rooms_number }}</td>
                    </tr>
                    @foreach($roomsBooking[$key] as $value2)
                        <tr>
                            <td colspan="2">
                                Phòng {{ $value2->room_number }}
                            </td>
                            <td colspan="2">
                                <button class="btn btn-danger" onclick="window.location = '{{ route('partner.quan-ly-dat-phong.xoa-phong', ['bookingID' => request()->id, 'roomID' => $value2->room_id, 'checkIn' => $booking->my_booking_checkin, 'checkOut' => $booking->my_booking_checkout  ]) }}'">Xóa</button>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>

        <hr>
        <div>
            <h4>Phòng trống từ ngày {{ date_format(date_create($booking->my_booking_checkin),"d-m-Y") }} đến {{ date_format(date_create($booking->my_booking_checkout),"d-m-Y") }}:</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Số phòng</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($bookingHotelList as $key1=>$value1)
                        <tr>
                            <td colspan="3">
                                <b>{{ $value1->rooms_hotel_name }}</b>
                            </td>
                        </tr>
                        @foreach ($roomsEmpty[$key1] as $key2=>$value2)
                            <tr>
                                <td>{{ $key2+1 }}</td>
                                <td>{{ $value2->room_number }}</td>
                                <td>
                                    <button class="btn btn-primary" onclick="window.location = '{{ route('partner.quan-ly-dat-phong.chon-phong', ['bookingID' => request()->id, 'roomID' => $value2->room_id, 'checkIn' => $booking->my_booking_checkin, 'checkOut' => $booking->my_booking_checkout ]) }}'">Chọn phòng</button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($booking->my_booking_status == 'Đã xử lý')
        <h5>Trạng thái:
            <span>
                <i class="fas fa-circle text-primary"></i>
                {{ $booking->my_booking_status }}
            </span>
            <span>
                <button class="btn btn-success" title="Hoàn thành" onclick="dieuHuong('{{ route('partner.quan-ly-dat-phong.hoan-thanh-dat-phong', ['id' => $booking->my_booking_id ]) }}')">
                    <i class="fas fa-check"></i>
                </button>
            </span>

            <button class="btn btn-primary" onclick="dieuHuong('{{ route('partner.quan-ly-dat-phong.xu-ly-lai', ['id' => $booking->my_booking_id]) }}')">
                Xử lý lại
            </button>

            <a href="{{ route('partner.quan-ly-dat-phong.print-bill', ['id' => $booking->my_booking_id]) }}">
                <button class="btn btn-success">
                    <i class="fas fa-file-alt"></i> In hóa đơn
                </button>
            </a>
        </h5>    

        <hr>
        <div>
            <h4>Danh sách phòng:</h4>
            <table class="table">
                <tr>
                    <th>STT</th>
                    <th>Loại phòng</th>
                    <th>Giá phòng</th>
                    <th>Số phòng</th>
                </tr>
                @foreach ($bookingHotelList as $key => $value1)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value1->rooms_hotel_name }}</td>
                        <td>{{ number_format($value1->room_hotel_price, 0, ',', '.') }} VND</td>
                        <td>{{ $value1->my_bookings_hotel_rooms_number }}</td>
                    </tr>
                    @foreach($roomsBooking[$key] as $value2)
                        <tr>
                            <td colspan="2">
                                Phòng {{ $value2->room_number }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
    @else
        @if($booking->my_booking_status == 'Đã hoàn thành')
            <span>
                <i class="fas fa-circle text-success"></i>
                {{ $booking->my_booking_status }}
            </span>
        @else
            <span>
                <i class="fas fa-circle text-danger"></i>
                {{ $booking->my_booking_status }}
            </span>
        @endif  

        <hr>
        <div>
            <h4>Danh sách phòng:</h4>
            <table class="table">
                <tr>
                    <th>STT</th>
                    <th>Loại phòng</th>
                    <th>Giá phòng</th>
                    <th>Số phòng</th>
                </tr>
                @foreach ($bookingHotelList as $key => $value1)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value1->rooms_hotel_name }}</td>
                        <td>{{ number_format($value1->room_hotel_price, 0, ',', '.') }} VND</td>
                        <td>{{ $value1->my_bookings_hotel_rooms_number }}</td>
                    </tr>
                    @foreach($roomsBooking[$key] as $value2)
                        <tr>
                            <td colspan="2">
                                Phòng {{ $value2->room_number }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
    @endif
@endsection