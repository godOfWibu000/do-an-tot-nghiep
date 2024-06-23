<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaveHotel extends Model
{
    use HasFactory;
    protected $table = 'save_hotels';

    public function getAllSavesHotel(){
        return DB::table($this->table)->get();
    }

    public function getSavesHotelForUser($filter){
        $list = DB::table($this->table)
        ->join('hotels', 'save_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->select('save_hotels.*', 'hotels.*');
        if(!empty($filter))
            $list = $list->orderBy('save_hotels.created_at', $filter);
        else
            $list = $list->orderBy('save_hotels.created_at', 'DESC');
        $list = $list->paginate(10);

        return $list;
    }

    public function checkSaveHotel($id){
        return DB::table($this->table)
        ->where('id', '=', Auth::user()->id)
        ->where('hotel_id', '=', $id)
        ->first();
    }

    public function addSaveHotel($hotelID, $time){
        return DB::table($this->table)->insert([
            'id' => Auth::user()->id,
            'hotel_id' => $hotelID,
            'created_at' => $time
        ]);
    }

    public function deleteSaveHotel($hotelID){
        return DB::table($this->table)
        ->where('id', '=', Auth::user()->id)
        ->where('hotel_id', '=', $hotelID)
        ->delete();
    }

    public function deleteSaveHotelsForCategory($id){
        return DB::table($this->table)
        ->join('hotels', 'save_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id' , '=', $id)
        ->delete();
    }

    public function deleteSaveHotelsForChildArea($id){
        return DB::table($this->table)
        ->join('hotels', 'save_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.child_area_id' , '=', $id)
        ->delete();
    }

    public function deleteSaveHotelsForHotel($id){
        return DB::table($this->table)
        ->join('hotels', 'save_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.hotel_id' , '=', $id)
        ->delete();
    } 

    public function deleteSaveHotelsForUser($id){
        return DB::table($this->table)
        ->where('id' , '=', $id)
        ->delete();
    }
}
