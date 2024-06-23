<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Hotels extends Model
{
    use HasFactory;

    protected $table = 'hotels';
    protected $primaryKey = 'hotel_id';
    protected $fillable = [
        'hotel_name',
        'hotel_star',
        'hotel_address',
        'hotel_old_price',
        'hotel_new_price',
        'hotel_thumbnail', 
        'hotel_description',
        'hotel_area',
        'category_id',
        'child_area_id',
        'id'
    ];

    public function getAllHotels(){
        $list = DB::table('hotels')->get();

        return $list;
    }

    public function getFilterHotelsAdmin($tuKhoa, $danhMuc, $khuVuc, $sapXepTheoTen, $sapXepTheoGia){
        $list = DB::table('hotels')
        ->join('categories', 'hotels.category_id', '=', 'categories.category_id')
        ->join('child_areas', 'hotels.child_area_id', '=', 'child_areas.child_area_id')
        ->select('hotels.*', 'categories.category_name', 'child_areas.child_area_name')
        ->where('hotels.id', '=', Auth::user()->id);

        if(!empty($tuKhoa))
            $list = $list->where(function ($query) use ($tuKhoa){
                $query->where('hotels.hotel_name', 'LIKE', '%'. $tuKhoa . '%')
                ->orWhere('hotels.hotel_address', 'LIKE', '%'. $tuKhoa . '%');
            });
        if(!empty($danhMuc) && $danhMuc != 'all')
            $list = $list->where('hotels.category_id', '=', $danhMuc);
        if(!empty($khuVuc))
            $list = $list->where('hotels.hotel_area', '=', $khuVuc);
        if(!empty($sapXepTheoTen))
            $list =$list->orderBy('hotels.hotel_name', $sapXepTheoTen);
        if(!empty($sapXepTheoGia))
            $list = $list->orderBy('hotels.hotel_new_price', $sapXepTheoGia);

        $list = $list->paginate(10);

        return $list;
    }

    public function getHotelsForCategory($id){
        $list = DB::table('hotels')
        ->join('categories', 'hotels.category_id', '=', 'categories.category_id')
        ->select('hotels.*', 'categories.category_name')
        ->where('hotels.category_id', '=', $id)
        ->orderBy('hotel_rate_point', 'DESC')
        ->paginate(10);

        return $list;
    }

    public function getOutstandingHotels(){
        $list = DB::table('hotels')
        ->orderBy('hotel_rate_point', 'DESC')
        ->limit(10)
        ->get();

        return $list;
    }

    public function getTopHotels($area){
        $list = DB::table('hotels')
        ->where('hotel_area', '=', $area)
        ->orderBy('hotel_star', 'DESC')
        ->limit(10)
        ->get();

        return $list;
    }

    public function getNewHotels(){
        $list = DB::table('hotels')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        return $list;
    } 

    public function getRateForSingleHotel($id){
        $hotel = DB::table('hotels')
        ->select('hotels.hotel_rate_point', 'hotels.hotel_number_rate')
        ->where('hotel_id', '=', $id)
        ->first();

        return $hotel;
    }

    public function updateRateForSingleHotel($id, $ratePoint, $rateNumber){
        return DB::table('hotels')
        ->where('hotel_id', '=', $id)
        ->update([
            'hotel_rate_point' => $ratePoint,
            'hotel_number_rate' => $rateNumber
        ]);
    }

    public function getSameHotels($catID, $area){
        $list = DB::table('hotels')
        ->join('categories', 'hotels.category_id', '=', 'categories.category_id')
        ->where([
            ['hotels.category_id', '=', $catID],
            ['hotel_area', '=', $area]
        ])
        ->limit(10)
        ->get();

        return $list;
    }

    public function getSingleHotel($id){
        $hotel = DB::table($this->table)
        ->join('categories', 'hotels.category_id', '=', 'categories.category_id')
        ->join('child_areas', 'hotels.child_area_id', '=', 'child_areas.child_area_id')
        ->select('hotels.*', 'categories.category_name', 'child_areas.child_area_name')
        ->where('hotel_id', '=', $id)
        ->first();
        return $hotel;
    }

    public function getFilterHotels($filterCategory = [], $minPrice, $maxPrice, $filterArea = [], $filterChildArea = [], $orderBy){
        $list = DB::table($this->table)
        ->join('categories', 'hotels.category_id', '=', 'categories.category_id')
        ->join('child_areas', 'hotels.child_area_id', '=', 'child_areas.child_area_id')
        ->select('hotels.*', 'categories.category_name', 'child_areas.child_area_name');

        if(!empty($filterArea))
            $list = $list->where($filterArea);
        if(!empty($minPrice) && !empty($maxPrice))
            $list = $list->whereBetween('hotels.hotel_new_price', [$minPrice, $maxPrice]);   
        if(!empty($filterCategory)){
            $list = $list->where($filterCategory);
        }
        if(!empty($filterChildArea))
            $list = $list->where($filterChildArea);
        if(!empty($orderBy)){
            if($orderBy == 'gia_cao_hon')
                $list = $list->orderBy('hotels.hotel_new_price', 'DESC');
            else if($orderBy == 'gia_thap_hon')
                $list = $list->orderBy('hotels.hotel_new_price', 'ASC');
            else
                $list = $list->orderBy('hotels.hotel_rate_point', 'DESC');
        }else
            $list = $list->orderBy('hotels.hotel_rate_point', 'DESC');

        // dd($list->toSql());
        $list = $list->paginate(10);

        return $list;
    }

    public function deleteHotelsForCategory($id){
        return DB::table($this->table)
        ->where('category_id' , '=', $id)
        ->delete();
    }

    public function deleteHotelsForChildArea($id){
        return DB::table($this->table)
        ->where('child_area_id' , '=', $id)
        ->delete();
    }
}
