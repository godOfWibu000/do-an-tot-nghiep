<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BookingRoom extends Model
{
    use HasFactory;

    protected $table = 'booking_rooms';
    protected $fillable = ['my_booking_id', 'room_id']; 

    public function getBookingRoomsForRoomHotel($bookingID, $roomHotelID){
        return DB::table($this->table)
        ->join('rooms', 'booking_rooms.room_id', '=' , 'rooms.room_id')
        ->join('rooms_hotels', 'rooms.rooms_hotels_id', '=', 'rooms_hotels.rooms_hotels_id')
        ->select('booking_rooms.*', 'rooms.room_number')
        ->where('booking_rooms.my_booking_id', '=', $bookingID)
        ->where('rooms_hotels.rooms_hotels_id', '=', $roomHotelID)
        ->get();
    }

    public function deleteBookingRoomsForCategory($id){
        return DB::table('booking_rooms')
        ->join('my_bookings', 'booking_rooms.my_booking_id', '=', 'my_bookings.my_booking_id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id', $id)
        ->delete();
    }
}
