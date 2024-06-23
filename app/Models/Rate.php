<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Rate extends Model
{
    use HasFactory;

    protected $table = 'rates';

    public function getAllRates(){
        return DB::table($this->table)
        ->join('users', 'rates.id', '=', 'users.id')
        ->join('customers', 'users.id', '=', 'customers.id')
        ->select('rates.*', 'customers.name')
        ->get();
    }

    public function filterRatesForHotel($id, $filter){
        $list = DB::table($this->table)
        ->join('users', 'rates.id', '=', 'users.id')
        ->join('customers', 'users.id', '=', 'customers.id')
        ->select('rates.*', 'customers.name')
        ->where('hotel_id', '=', $id);
        if(!empty($filter)){
            if($filter == 'moi_hon')
                $list = $list->orderBy('rates.created_at', 'DESC');
            else if($filter == 'cu_hon')
                $list = $list->orderBy('rates.created_at', 'ASC');
            else
                $list = $list->orderBy('rate_point', 'DESC');
        }else
            $list = $list->orderBy('rate_point', 'DESC');

        $list = $list->paginate(1);
        return $list;
    }

    public function checkRateHotel($id){
        return DB::table($this->table)
        ->where('id', '=', Auth::user()->id)
        ->where('hotel_id', '=', $id)
        ->first();
    }

    public function addRateByUser($hotelID, $ratePoint, $rateComment, $rateTimes){
        return DB::table($this->table)->insert([
            'id' => Auth::user()->id,
            'hotel_id' => $hotelID,
            'rate_point' => $ratePoint,
            'rate_comment' => $rateComment,
            'created_at' => $rateTimes
        ]);
    }

    public function editRateHotel($id, $ratePoint, $rateComment, $createdAt){
        return DB::table($this->table)
        ->where('id', '=', Auth::user()->id)
        ->where('hotel_id', '=', $id)
        ->update([
            'rate_point' => $ratePoint,
            'rate_comment' => $rateComment,
            'created_at' => $createdAt
        ]);
    }

    public function getRatePointForUser($id){
        return DB::table($this->table)
        ->select('rates.rate_point')
        ->where('id', '=', Auth::user()->id)
        ->where('hotel_id', '=', $id)
        ->first();
    }

    public function deleteRateHotelByUser($id){
        return DB::table($this->table)
        ->where('id', '=', Auth::user()->id)
        ->where('hotel_id', '=', $id)
        ->delete();
    }

    public function deleteRateHotelsForCategory($id){
        return DB::table($this->table)
        ->join('hotels', 'rates.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id' , '=', $id)
        ->delete();
    }

    public function deleteRateHotelsForChildArea($id){
        return DB::table($this->table)
        ->join('hotels', 'rates.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.child_area_id' , '=', $id)
        ->delete();
    }

    public function deleteRateHotelsForHotel($id){
        return DB::table($this->table)
        ->join('hotels', 'rates.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.hotel_id' , '=', $id)
        ->delete();
    }

    public function deleteRateHotelsForUser($id){
        return DB::table($this->table)
        ->where('id' , '=', $id)
        ->delete();
    }
}
