<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    protected $fillable = [
        'room_number',
        'room_checkin',
        'room_checkout',
        'rooms_hotels_id',
        'room_status'
    ];

    public function getRoomsEmpty($roomHotelId, $ngayConPhong){
        $list = DB::table($this->table)
        ->join('rooms_hotels', 'rooms_hotels.rooms_hotels_id', '=', 'rooms.rooms_hotels_id')
        ->select('rooms.*', 'rooms_hotels.rooms_hotel_name')
        ->where('rooms.rooms_hotels_id', '=', $roomHotelId);

        if(!empty($ngayConPhong))
            $list = $list->where(function($query) use($ngayConPhong){
                $query->where('room_checkin', '>', $ngayConPhong)
                ->orWhere('room_checkout', '<', $ngayConPhong);
            });

        $list = $list->get();

        return $list;
    }

    public function getRoomsForRoomsGroup($id, $tuKhoa, $trangThai, $ngayNhanPhong, $ngayTraPhong){
        $list = DB::table($this->table)
        ->select('rooms.room_id', 'rooms.room_number', 'rooms.room_status');

        if(!empty($ngayNhanPhong) && !empty($ngayTraPhong)){
            // $list = DB::select("SELECT * FROM `rooms` LEFT JOIN `calendar_rooms` ON rooms.room_id = calendar_rooms.room_id AND calendar_rooms.calendar_room_date >= ? AND calendar_rooms.calendar_room_date <= ? WHERE calendar_rooms.room_id IS NULL AND rooms_hotels.rooms_hotels_id=?", [$ngayNhanPhong, $ngayTraPhong, $id]);
            $list = $list->leftJoin('calendar_rooms', function($join) use($ngayNhanPhong, $ngayTraPhong) {
                $join->on('rooms.room_id', '=', 'calendar_rooms.room_id')
                     ->where('calendar_rooms.calendar_room_date', '>=', $ngayNhanPhong)
                     ->where('calendar_rooms.calendar_room_date', '<=', $ngayTraPhong);
            })
            ->whereNull('calendar_rooms.room_id');
        }

        $list = $list->where('rooms_hotels_id', '=', $id);

        if(!empty($tuKhoa))
            $list = $list->where('room_number', 'LIKE', '%' . $tuKhoa . '%');
        if(!empty($trangThai) && $trangThai == 'Không hoạt động')
            $list = $list->where('room_status', '=', 0);
        else
            $list = $list->where('room_status', '=', true);

        $list = $list->paginate(1);
        // dd($list->toSql());

        return $list;
    }

    public function deleteRoomsForCategory($id){
        return DB::table('rooms')
        ->join('rooms_hotels', 'rooms.rooms_hotels_id', '=', 'rooms_hotels.rooms_hotels_id')
        ->join('hotels', 'rooms_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id', $id)
        ->delete();
    }
}
