<?php

namespace App\Models;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StatisticalBookings extends Model
{
    use HasFactory;

    protected $table = 'statistical_bookings';
    protected $primaryKey = 'statistical_booking_id';
    protected $fillable = [
        'hotel_id',
        'total_number_bookings',
        'revenue',
        'statistical_bookings_date'
    ];

    public function getFilterStatisticalBookings($id, $thongKeTu, $thongKeDen, $thang, $sapXep){
        $list = DB::table($this->table)
        ->where('hotel_id', '=', $id);

        if(!empty($thongKeTu) && !empty($thongKeDen))
            $list = $list->whereBetween('statistical_bookings_date', [$thongKeTu, $thongKeDen]);
        else if(!empty($thang))
            $list = $list->where('statistical_bookings_date', 'LIKE', '%-' . $thang .'-%');
        else
            $list = $list->Limit(7);

        if(!empty($sapXep))
            $list = $list->orderBy('statistical_bookings_date', $sapXep);
        else
            $list = $list->orderBy('statistical_bookings_date', 'DESC');

        $list = $list->get();

        return $list;
    }
}
