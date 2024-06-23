<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MyBooking extends Model
{
    use HasFactory;

    protected $table = 'my_bookings';
    protected $primaryKey = 'my_booking_id';
    protected $fillable = [
        'my_booking_pay',
        'my_booking_status',
        'my_booking_date_success',
        'my_booking_checkin'
    ];
    
    public function getAllBookings($tuKhoa, $trangThai, $ngayBatDau, $ngayKetThuc, $sapXep){
        $list = DB::table($this->table)
        ->join('users', 'my_bookings.id', '=', 'users.id')
        ->join('customers', 'users.id', '=', 'customers.id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->select('my_bookings.*', 'users.email', 'customers.name', 'customers.address', 'customers.phone_number', 'hotels.hotel_name')
        ->where('hotels.id', '=', Auth::user()->id);
        if(!empty($tuKhoa))
            $list = $list->where(function($query) use ($tuKhoa){
                $query->where('hotels.hotel_name', 'LIKE', '%' . $tuKhoa . '%')
                ->orWhere('customers.name', 'LIKE', '%' . $tuKhoa . '%');
            });
        if(!empty($ngayBatDau) && !empty($ngayKetThuc)){
            $list = $list->where('my_booking_checkin', '>=', $ngayBatDau)
                ->where('my_booking_checkout', '<=', $ngayKetThuc);
        }
        if(!empty($trangThai))
            $list = $list->where('my_booking_status', '=', $trangThai);
        else
            $list = $list->where('my_booking_status', '=', 'Đang xử lý');
        if(!empty($sapXep))
            $list = $list->orderBy('my_bookings.created_at', $sapXep);
        else
            $list = $list->orderBy('my_bookings.created_at', 'DESC');
        
        $list = $list->paginate(10);

        return $list;
    }
    
    public function getBookingsForUser($filter, $order){
        $list =  DB::table($this->table)
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->select('my_bookings.*', 'hotels.hotel_name', 'hotels.hotel_address', 'hotels.hotel_thumbnail')
        ->where('my_bookings.id', '=', Auth::user()->id);
        
        if(!empty($filter) && $filter != 'tat_ca')
            $list = $list->where('my_bookings.my_booking_status', '=', $filter);
        else{
            
        }
        if(!empty($order))
            $list = $list->orderBy('created_at', $order);
        else
            $list = $list->orderBy('created_at', 'DESC');   


        $list = $list->paginate(10);

        return $list;
    }


    public function getSingleBooking($id){
        return DB::table($this->table)
        ->join('users', 'my_bookings.id', '=', 'users.id')
        ->join('customers', 'users.id', '=', 'customers.id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->select('my_bookings.*', 'customers.name', 'users.email', 'customers.address', 'customers.phone_number', 'hotels.hotel_name', 'hotels.hotel_address', 'hotels.hotel_thumbnail')
        ->where('my_bookings.my_booking_id', '=', $id)
        ->first();
    }

    public function getSingleBookingForPartner($id){
        return DB::table($this->table)
        ->join('users', 'my_bookings.id', '=', 'users.id')
        ->join('customers', 'users.id', '=', 'customers.id')
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->select('my_bookings.*', 'customers.name', 'hotels.id', 'hotels.hotel_name', 'hotels.hotel_address', 'hotels.hotel_thumbnail')
        ->where('my_bookings.my_booking_id', '=', $id)
        ->first();
    }
    public function addBooking($hotelID, $timeCheckIn, $timeCheckOut, $day, $timeBooking){
        return DB::table($this->table)->insertGetId([
            'my_booking_pay' => 0,
            'id' => Auth::user()->id,
            'hotel_id' => $hotelID,
            'my_booking_checkin' => $timeCheckIn,
            'my_booking_checkout' => $timeCheckOut,
            'my_booking_day' => $day,
            'created_at' => $timeBooking
        ]);
    }
    public function changeStatusBooking($id ,$status){
        return DB::table($this->table)
        ->where('my_bookings.my_booking_id', '=', $id)
        ->update([
            'my_booking_status' => $status
        ]);
    }
    public function updatePay($id, $pay){
        return DB::table($this->table)
        ->where('my_bookings.my_booking_id', '=', $id)
        ->update([
            'my_booking_pay' => $pay
        ]);
    }
    public function updateBooking($id, $timeCheckIn, $timeCheckOut, $day, $pay){
        return DB::table($this->table)
        ->where('my_booking_id', '=', $id)
        ->update([
            'my_booking_pay' => $pay,
            'my_booking_checkin' => $timeCheckIn,
            'my_booking_checkout' => $timeCheckOut,
            'my_booking_day' => $day
        ]);
    }
    public function deleteBooking($id){
        return DB::table($this->table)->where('my_booking_id' , '=', $id)->delete();
    }

    public function deleteBookingsForCategory($id){
        return DB::table($this->table)
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingsForChildArea($id){
        return DB::table($this->table)
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.child_area_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingsForHotel($id){
        return DB::table($this->table)
        ->join('hotels', 'my_bookings.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.hotel_id' , '=', $id)
        ->delete();
    }

    public function deleteBookingsForUser($id){
        return DB::table($this->table)
        ->where('id' , '=', $id)
        ->delete();
    }
}
