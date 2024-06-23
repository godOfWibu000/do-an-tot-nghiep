<?php

namespace App\Http\Controllers;

use App\Models\BookingRoom;
use App\Models\CalendarRoom;
use Illuminate\Http\Request;
use App\Models\MyBooking;
use App\Models\MyBookingsHotel;
use App\Models\Room;
use App\Models\SaveHotel;
use App\Models\StatisticalBookings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Class_;

class BookingController extends Controller
{
    private $booking;
    private $bookingsHotel;
    private $bookingRoom;
    private $rooms;
    private $savesHotel;

    public function __construct()
    {
        $this->booking = new MyBooking();
        $this->bookingsHotel = new MyBookingsHotel();
        $this->rooms = new Room();
        $this->bookingRoom = new BookingRoom();
        $this->savesHotel = new SaveHotel();
    }

    public function getSoPhongConLai($id, $checkIn, $checkOut){
        $soLuongPhong= DB::select("SELECT rooms.room_id, rooms.room_number FROM `rooms` INNER JOIN rooms_hotels ON rooms.rooms_hotels_id=rooms_hotels.rooms_hotels_id LEFT JOIN `calendar_rooms` ON rooms.room_id = calendar_rooms.room_id AND calendar_rooms.calendar_room_date >= ? AND calendar_rooms.calendar_room_date <= ? WHERE calendar_rooms.room_id IS NULL AND rooms_hotels.rooms_hotels_id=?", [$checkIn, $checkOut, $id]);
        return $soLuongPhong;
    }

    public function kTraSoPhongConLai(Request $req, $id){
        $soLuongPhong = DB::select("SELECT * FROM `rooms` LEFT JOIN `calendar_rooms` ON rooms.room_id = calendar_rooms.room_id AND calendar_rooms.calendar_room_date >= ? AND calendar_rooms.calendar_room_date <= ? WHERE calendar_rooms.room_id IS NULL AND rooms.rooms_hotels_id = ?", [$req->timeCheckIn, $req->timeCheckOut, $id]);
        return count($soLuongPhong);
    }

    public function datPhongCuaToi(Request $req){
        $bookingsList = $this->booking->getBookingsForUser($req->trang_thai, $req->sap_xep);
        $bookingHotelList = [];
        foreach ($bookingsList as $key => $value) {
            $bookingHotelList[$key] = $this->bookingsHotel->getHotelsForBooking($bookingsList[$key]->my_booking_id);
        }
        $title = 'Đặt phòng của tôi';
        return view('Booking.dat-phong-cua-toi', compact('title', 'bookingsList', 'bookingHotelList'));
    }

    public function capNhatTongTien($id, $dayNumber){
        $rooms = $this->bookingsHotel->getHotelsForBooking($id);
        $tongTien = 0;
        foreach ($rooms as $item) {
            $tongTien += $item->room_hotel_price * $item->my_bookings_hotel_rooms_number * $dayNumber;
        }
        return $tongTien;
    }

    public function datPhong(Request $req){
        foreach ($req->roomNumbers as $key => $value) { 
            $req->validate([
                'roomNumbers[' . $key . ']' => ['numeric', 'min:1']
            ],
            [
                
            ]);
        }
        
        
        $day = date_diff(date_create($req->timeCheckIn), date_create($req->timeCheckOut))->format("%a");
        if($day <= 0){
            echo 'Ngày nhận phòng và trả phòng không hợp lệ!';
            return;
        }

        foreach ($req->rooms as $key => $value) {
            $soPhongConLai = $this->kTraSoPhongConLai($req, $value);
            if($req->roomNumbers[$key] > $soPhongConLai){
                echo 'Số lượng phòng còn lại không đủ!';
                return;
            }
        }
        $id = $this->booking->addBooking($req->hotelID, $req->timeCheckIn, $req->timeCheckOut, $day, $req->timeBooking);
        foreach($req->rooms as $key => $value){
            $this->bookingsHotel->addBookingsHotel($id, $value, $req->roomNumbers[$key]);
        }
        $pay = $this->capNhatTongTien($id, $day);
        $this->booking->updatePay($id, $pay);
        
        echo 'ok';
    }

    public function capNhatDatPhong(Request $req, $id){
        foreach ($req->roomNumbers as $key => $value) { 
            $req->validate([
                'roomNumbers[' . $key . ']' => ['numeric', 'min:1']
            ],
            [
                
            ]);
        }

        $day = date_diff(date_create($req->timeCheckIn), date_create($req->timeCheckOut))->format("%a");
        if($day <= 0){
            echo 'Ngày nhận phòng và trả phòng không hợp lệ!';
            return;
        }

        foreach($req->rooms as $key => $value){
            $soPhongConLai = $this->kTraSoPhongConLai($req, $value);
            if($req->roomNumbers[$key] > $soPhongConLai){
                echo 'Số lượng phòng còn lại không đủ!';
                return;
            }
        }
        foreach ($req->rooms as $key => $value) {
            try {
                $updateBookingRoom = $this->bookingsHotel->updateBookingsHotel($id, $value, $req->roomNumbers[$key]);
            } catch (\Throwable $th) {
                echo 'Fail';
                return;
            }
        }
        $pay = $this->capNhatTongTien($id, $day);
        $this->booking->updatePay($id, $pay);
        try {
            $update = $this->booking->updateBooking($id, $req->timeCheckIn, $req->timeCheckOut, $day, $pay);
        } catch (\Throwable $th) {
            echo 'Fail';
            return;
        }

        echo 'Success';
    }

    public function chuyenTrangThaiDatPhong($id, $status){
        $this->booking->changeStatusBooking($id, $status);
    }

    public function kTraQuyen($id){
        if($id != Auth::user()->id)
            return true;
    }

    public function kTraQuyenChinhSuaDatPhong($bookingID, $roomID){
        $room = DB::table('rooms')
        ->join('rooms_hotels', 'rooms.rooms_hotels_id', '=', 'rooms_hotels.rooms_hotels_id')
        ->join('hotels', 'rooms_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->select('hotels.id')
        ->where('room_id', '=', $roomID)
        ->first();
        $hotel = DB::table('my_bookings')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->select('hotels.id')
        ->where('my_booking_id', '=', $bookingID)
        ->first();
        if($room == null || $hotel == null)
            return true;
        if($this->kTraQuyen($hotel->id) || $this->kTraQuyen($room->id))
            return true;
    }

    public function xacNhanDatPhong($id){
        $booking = $this->booking->getSingleBooking($id);
        if(!empty($booking)){
            if($this->kTraQuyen($booking->id))
                return redirect()->route('403');
            if($booking->my_booking_status == 'Chờ xác nhận'){
                if($this->booking->changeStatusBooking($id, 'Đang xử lý'))
                    return redirect()->route('dat-phong.chi-tiet-dat-phong', ['id' => $id]);
            }
        }else
            return redirect()->route('404');
        return back();
    }
    public function chiTietDatPhongAdmin($id){
        $booking = $this->booking->getSingleBookingForPartner($id);
        if($this->kTraQuyen($booking->id))
            return redirect('403');
        if(!empty($booking)){
            $bookingHotelList = $this->bookingsHotel->getHotelsForBooking($booking->my_booking_id);
            $roomsEmpty = [];
            $roomsBooking = [];
            for($i = 0; $i < count($bookingHotelList); $i++){
                $roomsBooking[$i] = $this->bookingRoom->getBookingRoomsForRoomHotel($id, $bookingHotelList[$i]->rooms_hotels_id);
            }
            for ($i = 0; $i < count($bookingHotelList); $i++) { 
                $roomsEmpty[$i] = $this->getSoPhongConLai($bookingHotelList[$i]->rooms_hotels_id, $booking->my_booking_checkin, $booking->my_booking_checkout);
            }
            $title = 'Chi tiết đặt phòng';
        }else
            return redirect()->route('404');

        return view('partner.chi-tiet-dat-phong', compact('title', 'booking', 'bookingHotelList', 'roomsBooking', 'roomsEmpty'));
    }

    public function chonPhong(Request $req, $bookingID, $roomID, $checkIn, $checkOut){
        if($this->kTraQuyenChinhSuaDatPhong($bookingID, $roomID))
            return redirect('403');
        // $kTra = BookingRoom::where('my_booking_id', $bookingID)->where('room_id', $roomID)->first();
        // if($kTra != null)
        //     return back()->with('error', 'Bạn đã chọn phòng này rồi!');
        for ( $i = strtotime($checkIn); $i < strtotime($checkOut); $i = $i + 86400 ) {
            $thisDate = date( 'Y-m-d', $i );
            $req->merge(['calendar_room_date' => $thisDate]);
            $req->merge(['room_id' => $roomID]);
            CalendarRoom::create($req->all());
        }

        $req->merge(['my_booking_id' => $bookingID]);
        $req->merge(['room_id' => $roomID]);
        try {
            BookingRoom::create($req->all());
        } catch (\Throwable $th) {
            dd('NOT');
        }
        return back();
    }

    public function xoaPhongDat($bookingID, $roomID, $checkIn, $checkOut){
        if($this->kTraQuyenChinhSuaDatPhong($bookingID, $roomID))
            return redirect('403');

        for ( $i = strtotime($checkIn); $i < strtotime($checkOut); $i = $i + 86400 ) {
            $thisDate = date( 'Y-m-d', $i );
            CalendarRoom::where('calendar_room_date', '=', $thisDate)->where('room_id', '=', $roomID)->delete();
        }
        try {
            BookingRoom::where('my_booking_id', $bookingID)->where('room_id', $roomID)->delete();
        } catch (\Throwable $th) {
            dd('NOT');
        }
        return back();
    }

    public function xuLyDatPhong($id){
        $this->chuyenTrangThaiDatPhong($id, 'Đã xử lý');
        return back()->with('success', 'Đặt phòng đã được xử lý!');
    }
    public function xuLyLai($id){
        $this->chuyenTrangThaiDatPhong($id, 'Đang xử lý');
        return back();
    }
    public function hetPhong($id){
        $this->chuyenTrangThaiDatPhong($id, 'Hết phòng');
        $booking = DB::table('my_bookings')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->select('hotels.id')
        ->where('my_booking_id', $id)
        ->first();
        if($this->kTraQuyen($booking->id))
            return redirect('403');

        $bookingRooms = DB::table('booking_rooms')
        ->join('my_bookings', 'booking_rooms.my_booking_id', '=', 'my_bookings.my_booking_id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->select('booking_rooms.room_id', 'my_bookings.my_booking_checkin', 'my_bookings.my_booking_checkout', 'hotels.id')
        ->get();
        
        foreach ($bookingRooms as $key => $value) {
            for ($i=strtotime($value->my_booking_checkin); $i < strtotime($value->my_booking_checkout); $i+=86400) { 
                $thisDate = date( 'Y-m-d', $i );
                $a = CalendarRoom::where('calendar_room_date', $thisDate)->where('room_id', $value->room_id)->delete();
            }
        }

        BookingRoom::where('my_booking_id', $id)->delete();

        return back();
    }
    public function hoanThanhDatPhong($id){
        $booking = MyBooking::find($id);
        $dateSuccess = date("Y-m-d");
        $booking->update([
            'my_booking_status' => 'Đã hoàn thành',
            'my_booking_date_success' => $dateSuccess
        ]);
        $statisticalBooking = StatisticalBookings::where('hotel_id', '=', $booking->hotel_id)
        ->where('statistical_bookings_date', '=', $dateSuccess)
        ->first();
        if($statisticalBooking == null){
            $statisticalData = [
                'hotel_id' => $booking->hotel_id,
                'total_number_bookings' => 1,
                'revenue' => $booking->my_booking_pay,
                'statistical_bookings_date' => $dateSuccess
            ];
            StatisticalBookings::create($statisticalData);
        }else{
            $statisticalData = [
                'total_number_bookings' => $statisticalBooking->total_number_bookings + 1,
                'revenue' => $statisticalBooking->revenue + $booking->my_booking_pay
            ];
            $statisticalBooking->update($statisticalData);
        }
        
        return back()->with('success', 'Đặt phòng đã được hoàn thành!');
    }

    public function huyDatPhong($id){
        $booking = $this->booking->getSingleBooking($id);
        if(!empty($booking)){
            if($this->kTraQuyen($booking->id))
                return redirect()->route('403');
            if($booking->my_booking_status == 'Đã xử lý'){
                if($this->booking->changeStatusBooking($id, 'Đã hủy'))
                    return back();
            }
        }else
            return redirect()->route('404');
        return back();
    }
    public function xoaDatPhong($id){
        $booking = $this->booking->getSingleBooking($id);
        if($this->kTraQuyen($booking->id))
            return redirect()->route('403');
        if($booking->my_booking_status == 'Chờ xác nhận' || $booking->my_booking_status == 'Đang xử lý'){
            $this->bookingsHotel->deleteBookingsHotelForBooking($id);
            $this->booking->deleteBooking($id);

            return redirect()->route('dat-phong.dat-phong-cua-toi')->with('success', 'Xóa đặt phòng thành công!');
        }
        return back();
    }
    public function xoaPhong($bookingId, $roomId){
        $booking = $this->booking->getSingleBooking($bookingId);
        if($this->kTraQuyen($booking->id))
            return redirect()->route('403');
        if($booking->my_booking_status == 'Chờ xác nhận' || $booking->my_booking_status == 'Đang xử lý'){
            $delete = $this->bookingsHotel->deleteSingleBookingHotelForBooking($bookingId, $roomId);
            if($delete){
                $pay = $this->capNhatTongTien($bookingId, 1);
                if($pay == 0)
                    return $this->xoaDatPhong($bookingId);
                else
                    $this->booking->updatePay($bookingId, $pay);
            }
        }
        return back();
    }

    public function chiTietDatPhong($id){
        $booking = $this->booking->getSingleBooking($id);
        if(!empty($booking)){
            if($this->kTraQuyen($booking->id))
                return redirect()->route('403');
            $bookingHotelList = $this->bookingsHotel->getHotelsForBooking($booking->my_booking_id);
            $title = 'Chi tiết đặt phòng ' . $booking->hotel_name . ' - Checkin ' . $booking->my_booking_checkin;
        }else
            return redirect()->route('404');

        return view('Booking.chi-tiet-dat-phong', compact('title', 'booking', 'bookingHotelList'));
    }
}
