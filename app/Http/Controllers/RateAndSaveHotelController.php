<?php

namespace App\Http\Controllers;

use App\Models\Hotels;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Models\SaveHotel;

class RateAndSaveHotelController extends Controller
{
    private $hotels;
    private $saveHotel;
    private $rateHotel;

    public function __construct()
    {
        $this->hotels = new Hotels();
        $this->saveHotel = new SaveHotel();
        $this->rateHotel = new Rate();
    }
    public function choODaLuu(Request $req){
        $title = 'Chỗ ở đã lưu';

        $savesHotelList = $this->saveHotel->getSavesHotelForUser($req->sap_xep);

        return view('Booking.cho-o-da-luu', compact('title', 'savesHotelList'));
    }

    public function updateAddRatePointAndRateNumber($id, $point){
        $rate = $this->hotels->getRateForSingleHotel($id);
        $newPoint = ($rate->hotel_rate_point * $rate->hotel_number_rate) + $point;
        $newRateNumber = $rate->hotel_number_rate + 1;
        $newPoint = round($newPoint / $newRateNumber, 1);

        return $this->hotels->updateRateForSingleHotel($id, $newPoint, $newRateNumber);
    }
    public function updateRatePoint($id, $oldPoint, $point){
        $rate = $this->hotels->getRateForSingleHotel($id);
        $newPoint = ($rate->hotel_rate_point * $rate->hotel_number_rate) - $oldPoint + $point;
        $newRateNumber = $rate->hotel_number_rate;
        $newPoint = round($newPoint / $newRateNumber, 1);
        return $this->hotels->updateRateForSingleHotel($id, $newPoint, $newRateNumber);
    }
    public function updateDeleteRatePointAndRateNumber($id, $point){
        $rate = $this->hotels->getRateForSingleHotel($id);
        $newPoint = ($rate->hotel_rate_point * $rate->hotel_number_rate) - $point;
        $newRateNumber = $rate->hotel_number_rate - 1;
        $newPoint = round($newPoint / $newRateNumber, 1);

        return $this->hotels->updateRateForSingleHotel($id, $newPoint, $newRateNumber);
    }

    public function saveHotel(Request $req){
        $addSaveHotel = $this->saveHotel->addSaveHotel($req->id, $req->thoiGian);
        echo $addSaveHotel ? 'Success' : 'Fail';
    }

    public function unSaveHotel($id){
        $deleteSave = $this->saveHotel->deleteSaveHotel($id);
        echo $deleteSave ? 'Success' : 'Fail';
    }

    public function deleteSaveHotel($id){
        $deleteSave = $this->saveHotel->deleteSaveHotel($id);
        $status = $deleteSave ? 'Success' : 'Fail';
        if($status == 'Success')
            return redirect()->back()->with('successDelete', 'Xóa chỗ ở đã lưu thành công!');
        else
            return redirect()->back()->with('failDelete', 'Xóa chỗ ở đã lưu không thành công!');
    }

    public function filterRatesHotel(Request $req, $id){
        $ratesList = $this->rateHotel->filterRatesForHotel($id, $req->sapXep);

        return view('Hotels.danh-gia', compact('ratesList', 'id'));
    }

    public function rateHotel(Request $req, $id){
        $req->validate([
            'rate_point' => ['numeric', 'min:1', 'max:10'],
            'comment' => ['max: 500']
        ], [
            
        ]);
        $rate = $this->rateHotel->addRateByUser($id, $req->rate_point,$req->comment, $req->rate_time);
        if($rate)
            $this->updateAddRatePointAndRateNumber($id, $req->rate_point);
        
        return redirect()->back();
    }

    public function editRateHotel(Request $req, $id){
        $req->validate([
            'rate_point' => ['numeric', 'min:1', 'max:10'],
            'comment' => ['max: 500']
        ], [
            
        ]);
        $point = $this->rateHotel->getRatePointForUser($id)->rate_point;
        $this->rateHotel->editRateHotel($id, $req->rate_point, $req->comment, $req->rate_time);
        $this->updateRatePoint($id, $point, $req->rate_point);
        return redirect()->back();
    }

    public function deleteRateHotelByUser($id){
        $point = $this->rateHotel->getRatePointForUser($id)->rate_point;
        $this->rateHotel->deleteRateHotelByUser($id);
        $this->updateDeleteRatePointAndRateNumber($id, $point);

        return redirect()->back();
    }
}
