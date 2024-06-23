<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MyBookingsHotel extends Model
{
    use HasFactory;

    protected $table = 'my_bookings_hotels';

    public function getAllBookingsHotel(){
        return DB::table($this->table)->get();
    }

    public function getHotelsForBooking($bookingId){
        return DB::table($this->table)
        ->join('rooms_hotels', 'my_bookings_hotels.rooms_hotels_id', '=', 'rooms_hotels.rooms_hotels_id')
        ->select('my_bookings_hotels.*', 'rooms_hotels.rooms_hotel_name', 'rooms_hotels.room_hotel_price')
        ->where('my_booking_id', '=', $bookingId)
        ->get();
    }

    public function addBookingsHotel($bookingID, $roomID, $roomsNumber){
        DB::table($this->table)->insert([
            'my_booking_id' => $bookingID,
            'rooms_hotels_id' => $roomID,
            'my_bookings_hotel_rooms_number' => $roomsNumber
        ]);
    }

    public function updateBookingsHotel($bookingId, $roomId, $roomNumber){
        return DB::table($this->table)
        ->where('my_booking_id' , '=', $bookingId)
        ->where('rooms_hotels_id', '=', $roomId)
        ->update([
            'my_bookings_hotel_rooms_number' => $roomNumber,
        ]);
    }

    public function deleteBookingsHotelForBooking($id){
        return DB::table($this->table)
        ->where('my_booking_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingHotelsForCategory($id){
        return DB::table($this->table)
        ->join('my_bookings', 'my_bookings_hotels.my_booking_id', '=', 'my_bookings.my_booking_id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingHotelsForChildArea($id){
        return DB::table($this->table)
        ->join('my_bookings', 'my_bookings_hotels.my_booking_id', '=', 'my_bookings.my_booking_id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.child_area_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingHotelsForHotel($id){
        return DB::table($this->table)
        ->join('my_bookings', 'my_bookings_hotels.my_booking_id', '=', 'my_bookings.my_booking_id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.hotel_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingHotelsForRoom($id){
        return DB::table($this->table)
        ->where('rooms_hotels_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingHotelsForUser($id){
        return DB::table($this->table)
        ->join('my_bookings', 'my_bookings_hotels.my_booking_id', '=', 'my_bookings.my_booking_id')
        ->where('my_bookings.id' , '=', $id)
        ->delete();
    }

    public function deleteSingleBookingHotelForBooking($bookingId, $roomId){
        return DB::table($this->table)
        ->where('my_booking_id' , '=', $bookingId)
        ->where('rooms_hotels_id', '=', $roomId)
        ->delete();
    }
}
