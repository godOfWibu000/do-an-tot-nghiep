<?php

namespace App\Http\Controllers;

use App\Models\ChildArea;
use App\Models\Hotels;
use App\Models\ImagesHotel;
use App\Models\MyBooking;
use App\Models\MyBookingsHotel;
use App\Models\Rate;
use App\Models\RoomsHotel;
use App\Models\SaveHotel;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    private $childAreas;
    private $bookingHotels;
    private $bookings;
    private $rooms;
    private $saveHotels;
    private $rates;
    private $imageHotels;
    private $hotels;

    public function __construct()
    {
        $this->childAreas = new ChildArea();
        $this->bookingHotels = new MyBookingsHotel();
        $this->bookings = new MyBooking();
        $this->rooms = new RoomsHotel();
        $this->saveHotels = new SaveHotel();
        $this->rates = new Rate();
        $this->imageHotels = new ImagesHotel();
        $this->hotels = new Hotels();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, $area)
    {
        if(count($this->childAreas->checkChildArea($req->child_area_name)) >= 1)
            return back()->with('error', 'Khu vực đã tồn tại!');

        $req->merge(['parent_area' => $area]);
        try {
            ChildArea::create($req->all());
        } catch (\Throwable $th) {
            dd($th);
        }
        return back()->with('success', 'Thêm khu vực thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($area, Request $req)
    {
        $title = 'Chi tiết khu vực ' . $area;
        $childAreasList = $this->childAreas->getChildAreas($area, $req->tu_khoa, $req->sap_xep);
        return view('admin.chi-tiet-khu-vuc', compact('title', 'childAreasList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $childArea = ChildArea::find($id);
        if(count($this->childAreas->checkChildArea($req->child_area_name)) >= 1 && $childArea->child_area_name != $req->child_area_name)
            return back()->with('error', 'Khu vực đã tồn tại!');
        try {
            $childArea->update($req->all());
            return back()->with('success', 'Cập nhật khu vực thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $childArea = ChildArea::find($id);
        $this->bookingHotels->deleteBookingHotelsForChildArea($id);
        $this->bookings->deleteBookingsForChildArea($id);
        $this->rooms->deleteRoomHotelsForChildArea($id);
        $this->saveHotels->deleteSaveHotelsForChildArea($id);
        $this->rates->deleteRateHotelsForChildArea($id);
        $this->imageHotels->deleteImageHotelsForChildArea($id);
        $this->hotels->deleteHotelsForChildArea($id);
        $delete = $childArea->delete();
        if($delete)
            return back()->with('success', 'Xóa khu vực thành công!');
    }
}
