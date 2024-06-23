<?php

namespace App\Http\Controllers;

use App\Models\CalendarRoom;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Hotels;
use App\Models\MyBooking;
use App\Models\MyBookingsHotel;
use App\Models\RoomsHotel;
use App\Models\ImagesHotel;
use App\Models\Rate;
use App\Models\Room;
use App\Models\SaveHotel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class HotelsController extends Controller
{
    private $cats;
    private $hotels;
    private $bookingHotels;
    private $roomHotels;
    private $rooms;
    private $calendarRoom;
    private $bookings;
    private $imagesHotel;
    private $rates;
    private $saveHotel;
    
    public function __construct()
    {
        $this->cats = new Categories();
        $this->hotels = new Hotels();
        $this->bookingHotels = new MyBookingsHotel();
        $this->roomHotels = new RoomsHotel();
        $this->rooms = new Room();
        $this->calendarRoom = new CalendarRoom();
        $this->bookings = new MyBooking();
        $this->imagesHotel = new ImagesHotel();
        $this->rates = new Rate();
        $this->saveHotel = new SaveHotel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Khám phá chỗ ở';
        $hotelsList = $this->hotels->getTopHotels('Hà Nội');
        $newHotelsList = $this->hotels->getNewHotels();
        $checkSaveHotels1 = [];
        $checkSaveHotels2 = [];
        if(Auth::check()){
            foreach ($hotelsList as $key => $value) {
                $checkSaveHotels1[$key] = $this->saveHotel->checkSaveHotel($value->hotel_id);
            }
            foreach ($newHotelsList as $key => $value) {
                $checkSaveHotels2[$key] = $this->saveHotel->checkSaveHotel($value->hotel_id);
            }
        }
        return view('Hotels.index', compact('title', 'hotelsList', 'newHotelsList', 'checkSaveHotels1', 'checkSaveHotels2'));
    }

    public function getTopHotelsForArea($area){
        $hotelsList = $this->hotels->getTopHotels($area);
        $checkSaveHotels = [];
        if(Auth::check()){
            foreach ($hotelsList as $key => $value) {
                $checkSaveHotels[$key] = $this->saveHotel->checkSaveHotel($value->hotel_id);
            }
        }
        return view('Hotels.cho-o-hang-dau', compact('hotelsList', 'checkSaveHotels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm chỗ ở';
        return view('partner.them-cho-o', compact('title'));
    }

    public function validateHotelCreate($req){
        $req->validate([
            'hotel_name' => ['required', 'min: 6', 'max: 255'],
            'hotel_star' => ['required', 'digits_between: 1,7'],
            'hotel_address' => ['required', 'min: 6', 'max: 500'],
            'hotel_old_price' => ['nullable', 'digits_between: 6, 9'],
            'hotel_new_price' => ['required', 'digits_between: 6, 9'],
            'image' => ['required', 'max: 2048', 'mimes: jpg,jpeg,png'],
            'hotel_description' => ['required', 'min: 6', 'max: 1000'],
            'hotel_area' => ['required'],
            'category_id' => ['required'],
            'child_area_id' => ['required']
        ], [
            'hotel_name.required' => 'Vui lòng nhập tên chỗ ở!',
            'hotel_name.min: 6' => 'Tên chỗ ở cần ít nhất 6 ký tự!',
            'hotel_name.max: 255' => 'Tên chỗ ở tối đa 255 ký tự!',
            'hotel_star.required' => 'Vui lòng nhập hạng chỗ ở!',
            'hotel_star.digits_between' => 'Hạng chỗ ở phải là số và giới hạn từ 1-7!',
            'hotel_address.required' => 'Vui lòng nhập địa chỉ chỗ ở!',
            'hotel_address.min: 6' => 'Địa chỉ chỗ ở cần ít nhất 6 ký tự!',
            'hotel_address.max: 500' => 'Địa chỉ chỗ ở tối đa 500 ký tự!',
            'hotel_old_price.digits_between' => 'Giá cần là số và giới hạn từ 100.000-100.000.000!',
            'hotel_new_price.required' => 'Vui lòng nhập giá chỗ ở!',
            'hotel_new_price.digits_between' => 'Giá cần là số và giới hạn từ 100.000-100.000.000!',
            'image.required' => 'Hãy tải lên ảnh chỗ ở!',
            'image.max' => 'File ảnh vượt quá kích thước! Kích thước tối đa cho phép là 2MB',
            'image.mimes' => 'File ảnh không hợp lệ! File ảnh phải là dạng file .jpeg, jpg, png',
            'hotel_description.required' => 'Vui lòng nhập mô tả chỗ ở!',
            'hotel_description.min: 6' => 'Mô tả chỗ ở cần ít nhất 6 ký tự!',
            'hotel_description.max: 255' => 'Mô tả chỗ ở tối đa 255 ký tự!',
            'hotel_area.required' => 'Vui lòng nhập khu vực chỗ ở!',
            'category_id.required' => 'Vui lòng nhập danh mục chỗ ở!',
            'child_area_id.required' => 'Vui lòng nhập địa điểm chỗ ở!',
        ]);
    }
    public function validateHotelUpdate($req){
        $req->validate([
            'hotel_name' => ['required', 'min: 6', 'max: 255'],
            'hotel_star' => ['required', 'digits_between: 1,7'],
            'hotel_address' => ['required', 'min: 6', 'max: 500'],
            'hotel_old_price' => ['nullable', 'digits_between: 6, 9'],
            'hotel_new_price' => ['required', 'digits_between: 6, 9'],
            'image' => ['nullable', 'max: 2048', 'mimes: jpg,jpeg,png'],
            'hotel_description' => ['required', 'min: 6', 'max: 1000'],
            'hotel_area' => ['required'],
            'category_id' => ['required'],
            'child_area_id' => ['required']
        ], [
            'hotel_name.required' => 'Vui lòng nhập tên chỗ ở!',
            'hotel_name.min: 6' => 'Tên chỗ ở cần ít nhất 6 ký tự!',
            'hotel_name.max: 255' => 'Tên chỗ ở tối đa 255 ký tự!',
            'hotel_star.required' => 'Vui lòng nhập hạng chỗ ở!',
            'hotel_star.digits_between' => 'Hạng chỗ ở phải là số và giới hạn từ 1-7!',
            'hotel_address.required' => 'Vui lòng nhập địa chỉ chỗ ở!',
            'hotel_address.min: 6' => 'Địa chỉ chỗ ở cần ít nhất 6 ký tự!',
            'hotel_address.max: 500' => 'Địa chỉ chỗ ở tối đa 500 ký tự!',
            'hotel_old_price.digits_between' => 'Giá cần là số và giới hạn từ 100.000-100.000.000!',
            'hotel_new_price.required' => 'Vui lòng nhập giá chỗ ở!',
            'hotel_new_price.digits_between' => 'Giá cần là số và giới hạn từ 100.000-100.000.000!',
            'image.max' => 'File ảnh vượt quá kích thước! Kích thước tối đa cho phép là 2MB',
            'image.mimes' => 'File ảnh không hợp lệ! File ảnh phải là dạng file .jpeg, jpg, png',
            'hotel_description.required' => 'Vui lòng nhập mô tả chỗ ở!',
            'hotel_description.min: 6' => 'Mô tả chỗ ở cần ít nhất 6 ký tự!',
            'hotel_description.max: 255' => 'Mô tả chỗ ở tối đa 255 ký tự!',
            'hotel_area.required' => 'Vui lòng nhập khu vực chỗ ở!',
            'category_id.required' => 'Vui lòng nhập danh mục chỗ ở!',
            'child_area_id.required' => 'Vui lòng nhập địa điểm chỗ ở!',
        ]);
    }

    public function validateRoomsGroup($req){
        $req->validate([
            'rooms_hotel_name' => ['required', 'min: 6', 'max: 255'],
            'room_hotel_price' => ['required', 'digits_between: 6, 9'],
            'room_hotel_description' => ['required', 'min: 6', 'max: 1000']
        ], [
            'rooms_hotel_name.required' => 'Vui lòng nhập tên phòng!',
            'rooms_hotel_name.min' => 'Tên phòng cần có độ dài ít nhất 6 ký tự!',
            'rooms_hotel_name.max' => 'Tên phòng có độ dài tối đa 255 ký tự',
            'room_hotel_price.required' => 'Vui lòng nhập giá phòng!',
            'room_hotel_price.digits_between' => 'Giá cần là số và giới hạn từ 100.000-100.000.000!',
            'room_hotel_description.required' => 'Vui lòng nhập mô tả!',
            'room_hotel_description.min' => 'Mô tả cần có độ dài ít nhất 6 ký tự!',
            'room_hotel_description.max' => 'Mô tả có độ dài tối đa 1000 ký tự',
        ]);
    }

    public function validateRoom($req){
        $req->validate([
            'room_number' => ['required', 'max: 255', 'unique:rooms,room_number'],
        ], [
            'room_number.required' => 'Vui lòng nhập số phòng!',
            'room_number.max' => 'Số phòng giới hạn bởi 255 ký tự!',
            'room_number.unique' => 'Số phòng đã tồn tại!',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $this->validateHotelCreate($req);

        $fileName = null;
        if(!empty($req->image)){
            $fileName = uploadImage($req->image, 'assets/img/hotels/thumbnail');
        }
        $req->merge(['hotel_thumbnail'=>$fileName]);
        $req->merge(['id' => Auth::user()->id]);
        try{
            Hotels::create($req->all());
            return redirect()->route('partner.quan-ly-cho-o.index')->with('success', 'Thêm chỗ ở thành công!');
        }catch(\Throwable $th){
            dd($th);
            // return back()->with('error', 'Thêm chỗ ở không thành công!');
        }
    }

    public function showForCategory($id){
        $category = Categories::find($id);
        if($category == null)
            return redirect()->route('404');
        $hotelsList = $this->hotels->getHotelsForCategory($id);
        $title = 'Chỗ ở danh mục ' . $category->category_name;
        $checkSaveHotels = [];
        if(Auth::check()){
            foreach ($hotelsList as $key => $value) {
                $checkSaveHotels[$key] = $this->saveHotel->checkSaveHotel($value->hotel_id);
            }
        }
        return view('Hotels.danh-muc', compact('title', 'hotelsList', 'checkSaveHotels'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hotel = $this->hotels->getSingleHotel($id);
        if(!empty($hotel)){
            $imagesHotelList = $this->imagesHotel->getImageHotel($id);
            $ratesList = $this->rates->filterRatesForHotel($id, null);
            $sameHotelsList = $this->hotels->getSameHotels($hotel->category_id, $hotel->hotel_area);

            if(Auth::check()){
                if($this->saveHotel->checkSaveHotel($id))
                    $checkSaveHotel = true;
                else
                    $checkSaveHotel = false;

                if($rate = $this->rates->checkRateHotel($id))
                    $checkRateHotel = true;
                else{
                    $rate = null;
                    $checkRateHotel = false;
                }
            }else{
                $rate = null;
                $checkSaveHotel = null;
                $checkRateHotel = null;
            }
            $title = "Thông tin chỗ ở " . $hotel->hotel_name;
        }else
            return redirect()->route('404');
        
        return view('Hotels.chi-tiet-cho-o', compact('title', 'hotel', 'sameHotelsList', 'imagesHotelList', 'ratesList', 'checkSaveHotel', 'checkRateHotel', 'rate'));
    }

    public function show_Admin($id){
        $hotel = $this->hotels->getSingleHotel($id);
        if($hotel->id != Auth::user()->id)
            return redirect('403');
        $images = $this->imagesHotel->getImageHotel($id);
        $rooms = $this->roomHotels->getRoomsForHotel($id);
        $rates = $this->rates->filterRatesForHotel($id, null);
        $title = 'Chi tiết chỗ ở ' . $hotel->hotel_name;
        return view('Partner.chi-tiet-cho-o', compact('title', 'hotel', 'images', 'rooms', 'rates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hotel = Hotels::findOrFail($id);
        $title = "Cập nhật chỗ ở '" . $hotel->hotel_name . "'";
        return view('Partner.cap-nhat-cho-o', compact('id','title', 'hotel'));
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
        $this->validateHotelUpdate($req);
        $hotel = Hotels::findOrFail($id);
        $oldFileName = null;
        $fileName = $hotel->hotel_thumbnail;
        if(!empty($req->image)){
            $oldFileName = public_path('assets/img/hotels/thumbnail') . '\\'. $fileName;
            $fileName = uploadImage($req->image, 'assets/img/hotels/thumbnail');
        }
        $req->merge(['hotel_thumbnail'=>$fileName]);
        try{
            $hotel->update($req->all());
            if(File::exists($oldFileName))
                unlink($oldFileName);
            return back()->with('success', 'Cập nhật chỗ ở thành công!');
        }catch(\Throwable $th){
            unlink(public_path('assets/img/hotels/thumbnail') . '\\'. $fileName);
            // dd($th);
            return back()->with('error', 'Cập nhật chỗ ở không thành công!');
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
        $this->bookingHotels->deleteBookingHotelsForHotel($id);
        $this->bookings->deleteBookingsForHotel($id);
        $this->imagesHotel->deleteImageHotelsForHotel($id);
        $this->rates->deleteRateHotelsForHotel($id);
        $this->saveHotel->deleteSaveHotelsForHotel($id);

        $hotel = Hotels::find($id);
        $delete = $hotel->delete();
        if($delete){
            if(File::exists((public_path('assets/img/hotels/thumbnail') . '\\'. $hotel->hotel_thumbnail)))
                unlink(public_path('assets/img/hotels/thumbnail') . '\\'. $hotel->hotel_thumbnail);
            return back()->with('success', 'Xóa chỗ ở thành công!');
        }
    }

    public function search(Request $req){
        if(!empty($req->dia_diem)){
            $filterCategory = [];
            $filterArea = [];
            $filterChildArea = [];

            $title = 'Danh sách chỗ ở tại ' . $req->dia_diem;
            $filterArea[] = ['hotels.hotel_area', '=', $req->dia_diem];

            if(!empty($req->khu_vuc)){
                foreach ($req->khu_vuc as $item) {
                    $filterChildArea[] = ['hotels.child_area_id', '=', $item, 'OR'];
                }
            }
            
            if(!empty($req->loai_hinh_cho_thue)){
                foreach ($req->loai_hinh_cho_thue as $item) {
                    $filterCategory[] = ['hotels.category_id', '=', $item, 'OR'];
                }
            }
    
            $hotelsList = $this->hotels->getFilterHotels($filterCategory, $req->gia_toi_thieu, $req->gia_toi_da, $filterArea, $filterChildArea, $req->sap_xep);

            $checkSaveHotels = [];
            if(Auth::check()){
                foreach ($hotelsList as $key => $value) {
                    $checkSaveHotels[$key] = $this->saveHotel->checkSaveHotel($value->hotel_id);
                }
            }

            return view('Hotels.tim-kiem-cho-o', compact('title','hotelsList', 'checkSaveHotels'));
        }else{
            $title = 'Không tìm thấy kết quả để hiển thị!';

            return view('Hotels.tim-kiem-cho-o', compact('title'));
        }
    }

    // Image hotel
    public function createImageHotel(Request $req, $id){
        $req->validate([
            'image' => ['required', 'max: 2048', 'mimes: jpg,jpeg,png']
        ],[
            'image.required' => 'Vui lòng tải lên ảnh!',
            'image.max' => 'File ảnh vượt quá kích thước! Kích thước tối đa cho phép là 2MB',
            'image.mimes' => 'File ảnh không hợp lệ! File ảnh phải là dạng file .jpeg, jpg, png'
        ]);

        if(!empty($req->image)){
            $fileName = uploadImage($req->image, 'assets/img/hotels/images');
        }

        $req->merge(['images_hotel_file_name' => $fileName]);
        $req->merge(['hotel_id' => $id]);

        try {
            ImagesHotel::create($req->all());
            return back()->with('success', 'Thêm ảnh chỗ ở thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function deleteImageHotel($id, ){
        $image = ImagesHotel::find($id);

        $fileName = public_path('assets/img/hotels\images') . '\\' . $image->images_hotel_file_name;
        if(File::exists($fileName))
            unlink($fileName);
        try {
            $image->delete();
        } catch (\Throwable $th) {
            dd($th);
        }

        return back()->with('success', 'Xóa ảnh chỗ ở thành công!');
    }

    // RoomGroup
    public function infomationRoomsGroup(Request $req, $id){
        $title = 'Chi tiết nhóm phòng';
        $roomsGroup = $this->roomHotels->getSingleRoomsHotel($id);
        $roomsList = $this->rooms->getRoomsForRoomsGroup($id, $req->tu_khoa, $req->trang_thai, $req->ngay_nhan_phong, $req->ngay_tra_phong);
        return view('Partner.chi-tiet-nhom-phong', compact('title', 'roomsGroup', 'roomsList'));
    }
    public function storeRoomsGroup(Request $req, $id){
        $this->validateRoomsGroup($req);
        $req->merge(['hotel_id' => $id]);
        try {
            RoomsHotel::create($req->all());
            return back()->with('success', 'Thêm phòng thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function updateRoomsGroup(Request $req, $id){
        $this->validateRoomsGroup($req);
        try {
            $room = RoomsHotel::find($id);
            $room->update($req->all());
            return back()->with('success', 'Cập nhật phòng thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function deleteRoomsGroup($id){
        try {
            $this->bookingHotels->deleteBookingHotelsForRoom($id);
            RoomsHotel::find($id)->delete();
            return back()->with('success', 'Xóa phòng thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    // Room
    public function themPhong(Request $req, $id){
        $this->validateRoom($req);
        $req->merge(['rooms_hotels_id' => $id]);
        try {
            Room::create($req->all());
            return back()->with('success', 'Thêm phòng thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function capNhatPhong(Request $req, $id){
        $this->validateRoom($req);
        $room = Room::find($id);
        try {
            $room->update($req->all());
            return back()->with('success', 'Cập nhật phòng thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function xoaPhong($id){
        try {
            Room::find($id)->delete();
            return back()->with('success', 'Xóa phòng thành công!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    //Calendar
    public function lichDatPhong(Request $req, $id){
        $calendar = $this->calendarRoom->getCalendarRoom($id, $req->ngay_thue_phong, $req->thang, $req->sap_xep);
        $room = DB::table('rooms')->select('room_number')->where('room_id', $id)->first();
        $title = 'Lịch đặt phòng ' . $room->room_number;
        return view('Partner.lich-dat-phong', compact('title', 'calendar'));
    }
    public function themLichDatPhong(Request $req, $id){
        $req->validate([
            'calendar_room_date' => ['required', 'date']
        ], [
            'calendar_room_date.required' => 'Vui lòng nhập ngày thuê phòng!',
            'calendar_room_date.date' => 'Dữ liệu không hợp lệ!'
        ]);
        $req->merge(['room_id' => $id]);
        try {
            CalendarRoom::create($req->all());
        } catch (\Throwable $th) {
            // dd($th);
            return back()->with('error', 'Bạn đã thêm ngày này rồi!');
        }
        return back();
    }
    public function xoaLichDatPhong($id, $date){
        try {
            CalendarRoom::where('room_id', $id)->where('calendar_room_date', $date)->delete();
        } catch (\Throwable $th) {
            
        }
        return back();
    }

    public function khuyenMai(){
        $title = 'Khuyến mại';
        return view('Hotels.khuyen-mai', compact('title'));
    }
}
