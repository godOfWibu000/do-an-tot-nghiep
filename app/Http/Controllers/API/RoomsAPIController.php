<?php

namespace App\Http\Controllers\API;
use App\Models\RoomsHotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomsAPIController extends Controller
{
    private $roomsHotel;

    public function __construct()
    {
        $this->roomsHotel = new RoomsHotel();
    }
    
    public function getRoomsHotelList(){
        return $this->roomsHotel->getAllRoomsHotel();
    }

    
    public function showForHotel($hotelID){
        return $this->roomsHotel->getRoomsForHotel($hotelID);
    }

    public function show($roomsHotelID){
        return $this->roomsHotel->getSingleRoomsHotel($roomsHotelID);
    }
}