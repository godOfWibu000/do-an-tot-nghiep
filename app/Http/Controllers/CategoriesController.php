<?php

namespace App\Http\Controllers;

use App\Models\BookingRoom;
use App\Models\CalendarRoom;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Hotels;
use App\Models\ImagesHotel;
use App\Models\MyBooking;
use App\Models\MyBookingsHotel;
use App\Models\Rate;
use App\Models\Room;
use App\Models\RoomsHotel;
use App\Models\SaveHotel;
use Illuminate\Support\Facades\File;

class CategoriesController extends Controller
{
    private $cats;
    private $bookingHotels;
    private $room;
    private $roomHotels;
    private $bookings;
    private $bookingRoom;
    private $calendarRoom;
    private $saveHotels;
    private $rateHotels;
    private $imageHotels;
    private $hotels;

    public function __construct()
    {
        $this->cats = new Categories();
        $this->bookingHotels = new MyBookingsHotel();
        $this->room = new Room();
        $this->roomHotels = new RoomsHotel();
        $this->bookings = new MyBooking();
        $this->bookingRoom = new BookingRoom();
        $this->calendarRoom = new CalendarRoom();
        $this->saveHotels = new SaveHotel();
        $this->rateHotels = new Rate();
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
        $catsList = $this->cats->getCats();
        return $catsList;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $req->validate([
            'ten_danh_muc' => ['required', 'min: 6', 'max: 255'],
            'anh_danh_muc' => ['required', 'max: 2048', 'mimes: jpg,jpeg,png']
        ], 
        [
            'ten_danh_muc.required' => 'Tên danh mục không được bỏ trống!',
            'ten_danh_muc.min' => 'Tên danh mục phải cần ít nhất 6 ký tự!',
            'ten_danh_muc.max' => 'Tên danh mục tối đa 255 ký tự!',
            'anh_danh_muc.required' => 'Hãy tải lên ảnh danh mục!',
            'anh_danh_muc.max' => 'File ảnh vượt quá kích thước! Kích thước tối đa cho phép là 2MB',
            'anh_danh_muc.mimes' => 'File ảnh không hợp lệ! File ảnh phải là dạng file .jpeg, jpg, png'
        ]);
        if(count($this->cats->checkCategories($req->ten_danh_muc)) >= 1)
            return back()->with('error', 'Tên danh mục đã tồn tại!');

        $fileName = null;
        if(!empty($req->anh_danh_muc)){
            $fileName = uploadImage($req->anh_danh_muc, 'assets/img/categories');
        }

        $add = $this->cats->addCategory($req->ten_danh_muc, $fileName);
        if($add)
            return back()->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $req->validate([
            'ten_danh_muc' => ['required', 'min: 6', 'max: 255'],
            'anh_danh_muc' => ['nullable', 'max: 2048', 'mimes: jpg,jpeg,png']
        ], 
        [
            'ten_danh_muc.required' => 'Tên danh mục không được bỏ trống!',
            'ten_danh_muc.min' => 'Tên danh mục phải cần ít nhất 6 ký tự!',
            'ten_danh_muc.max' => 'Tên danh mục tối đa 255 ký tự!',
            'anh_danh_muc.required' => 'Hãy tải lên ảnh danh mục!',
            'anh_danh_muc.max' => 'File ảnh vượt quá kích thước! Kích thước tối đa cho phép là 2MB',
            'anh_danh_muc.mimes' => 'File ảnh không hợp lệ! File ảnh phải là dạng file .jpeg, jpg, png'
        ]);

        $category = $this->cats->getSingleCat($id);
        $file = $category->category_image;
        if(count($this->cats->checkCategories($req->ten_danh_muc)) >= 1 && $req->ten_danh_muc != $category->category_name)
            return back()->with('error', 'Tên danh mục đã tồn tại!');
        $fileName = null;
        $oldFileName = null;
        if(!empty($req->anh_danh_muc)){
            $oldFileName = public_path('assets\img\categories') . '\\'. $file;
            $fileName = uploadImage($req->anh_danh_muc, 'assets/img/categories');
        }else{
            $fileName = $file;
        }
        $update = $this->cats->updateCategory($id, $req->ten_danh_muc, $fileName);
        if($update){
            if(File::exists($oldFileName))
                unlink($oldFileName);
        }
        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imagePath = public_path('assets\img\categories') . '\\'. $this->cats->getImageCategory($id)->category_image;

        $this->bookingHotels->deleteBookingHotelsForCategory($id);
        $this->bookingRoom->deleteBookingRoomsForCategory($id);
        $this->calendarRoom->deleteCalendarRoomsForCategory($id);
        $this->room->deleteRoomsForCategory($id);
        $this->bookings->deleteBookingsForCategory($id);
        $this->roomHotels->deleteRoomHotelsForCategory($id);
        $this->saveHotels->deleteSaveHotelsForCategory($id);
        $this->rateHotels->deleteRateHotelsForCategory($id);
        $this->imageHotels->deleteImageHotelsForCategory($id);
        $this->hotels->deleteHotelsForCategory($id);
        $delete = $this->cats->deleteCategory($id);
        if($delete){
            if(File::exists($imagePath))
                unlink($imagePath);
            return back()->with('success', 'Xóa danh mục thành công!');
        }
    }
}
