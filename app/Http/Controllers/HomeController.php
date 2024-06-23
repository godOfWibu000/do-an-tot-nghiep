<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HotelsController;
use App\Models\Categories;
use App\Models\Hotels;
use App\Models\SaveHotel;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    private $cats;
    private $hotel;
    private $saveHotel;

    public function __construct()
    {
        $this->hotel = new Hotels();
        $this->saveHotel = new SaveHotel();
    }

    public function index(){
        $title = 'Trang chủ';
        $hotelsList = $this->hotel->getOutstandingHotels();
        $checkSaveHotels = [];
        if(Auth::check()){
            foreach ($hotelsList as $key => $value) {
                $checkSaveHotels[$key] = $this->saveHotel->checkSaveHotel($value->hotel_id);
            }
        }
        // $provincesList = Http::get('http://localhost/DeTaiDoAnTotNghiep/public/api/provinces')->json();
        
        return view('Main.index', compact('title', 'hotelsList', 'checkSaveHotels'));
    }

    public function testMail(){
        $title = '';
        Mail::send('mail', compact('title'), function($email){
            $email->to('hoangluong032002@gmail.com', 'Luong');
        });
    }
}
