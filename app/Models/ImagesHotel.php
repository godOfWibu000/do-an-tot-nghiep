<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImagesHotel extends Model
{
    use HasFactory;

    protected $table = 'images_hotels';
    protected $primaryKey = 'images_hotel_id';
    protected $fillable = [
        'images_hotel_file_name',
        'hotel_id'
    ];

    public function getImageHotel($id){
        $hotel = DB::table($this->table)
        ->where('hotel_id', '=', $id)
        ->get();
        return $hotel;
    }

    public function deleteImageHotelsForCategory($id){
        return DB::table($this->table)
        ->join('hotels', 'images_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id' , '=', $id)
        ->delete();
    }

    public function deleteImageHotelsForChildArea($id){
        return DB::table($this->table)
        ->join('hotels', 'images_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.child_area_id' , '=', $id)
        ->delete();
    }

    public function deleteImageHotelsForHotel($id){
        return DB::table($this->table)
        ->join('hotels', 'images_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.hotel_id' , '=', $id)
        ->delete();
    }
}
