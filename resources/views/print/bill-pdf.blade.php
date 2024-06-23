<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        .center{
            text-align: center;
        }
        .flex{
            display: flex;
        }
        .between{
            justify-content: space-between;
        }
        table{
            border-collapse: collapse;
            border: 1px solid grey;
            width: 100%;
        }
        th, td{
            border: 1px solid grey;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 class="center">{{ $title }}</h2>
    <h5 class="center">Mã hóa đơn: {{ strtotime(date('Y-m-d')) }}</h5>
    <hr>
    <br><br>
    <div>
        <h3>Thông tin khách hàng:</h3>
        <h4>Họ tên khách hàng: {{ $booking->name }}</h4>
        <h4>Địa chỉ: {{ $booking->address }}</h4>
        <h4>Số điện thoại: {{ $booking->phone_number }}</h4>
        <h4>Email: {{ $booking->email }}</h4>
        <hr>

        <h3>Thông tin đặt phòng:</h3>
        <h4>Ngày checkin: {{ $booking->my_booking_checkin }}</h4>
        <h4>Ngày checkout: {{ $booking->my_booking_checkout }}</h4>

        <h4>Danh sách phòng: </h4>
        <table>
            <tr>
                <th>STT</th>
                <th>Tên phòng</th>
                <th>Giá phòng</th>
                <th>Số lượng phòng</th>
            </tr>
            {{-- @foreach ($bookingDetail as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->rooms_hotel_name }}</td>
                    <td>{{ number_format($value->room_hotel_price, 0, ',', '.') }} VND</td>
                    <td>{{ $value->my_bookings_hotel_rooms_number }}</td>
                </tr>
            @endforeach --}}
        </table>
        <h4>Tổng thanh toán: {{ number_format($booking->my_booking_pay, 0, ',', '.') }} VND</h4>
    </div>
</body>
</html>