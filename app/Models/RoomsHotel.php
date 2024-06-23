<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoomsHotel extends Model
{
    use HasFactory;

    protected $table = 'rooms_hotels';
    protected $primaryKey = 'rooms_hotels_id';
    protected $fillable = [
        'rooms_hotel_name',
        'room_hotel_price',
        'room_hotel_description',
        'hotel_id'
    ];
    
    public function getAllRoomsHotel(){
        return DB::table($this->table)->get();
    }

    public function getRoomsForHotel($hotelID){
        return DB::table($this->table)
        ->where('hotel_id', '=', $hotelID)
        ->get();
    }
    public function getSingleRoomsHotel($roomsHotelID){
        return DB::table($this->table)
        ->where('rooms_hotels_id', '=', $roomsHotelID)
        ->first();
    }

    public function deleteRoomHotelsForCategory($id){
        return DB::table($this->table)
        ->join('hotels', 'rooms_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id' , '=', $id)
        ->delete();
    }

    public function deleteRoomHotelsForChildArea($id){
        return DB::table($this->table)
        ->join('hotels', 'rooms_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.child_area_id' , '=', $id)
        ->delete();
    }
}
