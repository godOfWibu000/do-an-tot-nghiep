<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotels;

class HotelsAPIController extends Controller
{
    private $hotels;
    
    public function __construct()
    {
        $this->hotels = new Hotels();
    }

    public function index(){
        $hotelsList = $this->hotels->getAllHotels();
        return response([
            'data' => $hotelsList
        ]);
    }

    public function show($id)
    {
        $hotel = $this->hotels->getSingleHotel($id);
        return $hotel;
    }
}
