<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalendarRoom extends Model
{
    use HasFactory;

    protected $table = 'calendar_rooms';
    protected $fillable = ['calendar_room_date', 'room_id'];

    public function getCalendarRoom($id, $ngayThue, $thang, $sapXep){
        $list = DB::table($this->table)
        ->join('rooms', 'calendar_rooms.room_id', '=', 'rooms.room_id')
        ->select('calendar_rooms.*', 'rooms.room_number');

        if(!empty($ngayThue))
            $list = $list->where('calendar_room_date', '=', $ngayThue);
        if(!empty($thang))
            $list = $list->where('calendar_room_date', 'LIKE', '%-' . $thang . '-%');
        else
            $list = $list->where('calendar_room_date', 'LIKE', '%-' . date('m') . '-%');
        if(!empty($sapXep))
            $list = $list->orderBy('calendar_room_date', $sapXep);
        else
            $list = $list->orderBy('calendar_room_date', 'DESC');

        $list = $list->paginate(10);

        return $list;
    }

    public function deleteCalendarRoomsForCategory($id){
        return DB::table('calendar_rooms')
        ->join('rooms', 'calendar_rooms.room_id', '=', 'rooms.room_id')
        ->join('rooms_hotels', 'rooms.rooms_hotels_id', '=', 'rooms_hotels.rooms_hotels_id')
        ->join('hotels', 'rooms_hotels.hotel_id', '=', 'hotels.hotel_id')
        ->where('hotels.category_id', $id)
        ->delete();
    }
}
