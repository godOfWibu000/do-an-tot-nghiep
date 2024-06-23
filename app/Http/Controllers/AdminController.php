<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\User;
use App\Models\Hotels;
use App\Models\MyBooking;
use App\Models\MyBookingsHotel;
use App\Models\Partner;
use App\Models\Rate;
use App\Models\SaveHotel;
use App\Models\StatisticalBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private $categories;
    private $users;
    private $partner;
    private $hotels;
    private $myBookings;
    private $myBookingHotels;
    private $rates;
    private $saveHotels;
    private $statisticals;

    public function __construct()
    {
        $this->categories = new Categories();
        $this->users = new User();
        $this->partner = new Partner();
        $this->hotels = new Hotels();
        $this->myBookings = new MyBooking();
        $this->myBookingHotels = new MyBookingsHotel();
        $this->rates = new Rate();
        $this->saveHotels = new SaveHotel();
        $this->statisticals = new StatisticalBookings();
    }

    public function index(){
        $title = 'Trang quản trị';

        return view('Admin.index', compact('title'));
    }

    public function categoriesManager(Request $req){
        $title = 'Quản lý danh mục';
        $tuKhoa = null;
        $sapXep = null;
        if(!empty($req->tu_khoa))
            $tuKhoa = $req->tu_khoa;
        if(!empty($req->sap_xep))
            $sapXep = $req->sap_xep;
        $categoriesList = $this->categories->filterCats($tuKhoa, $sapXep);
        return view('Admin.quan-ly-danh-muc', compact('title', 'categoriesList'));
    }

    public function areaManager(Request $req){
        $title = 'Quản lý khu vực';
        return view('Admin.quan-ly-khu-vuc', compact('title'));
    }

    public function usersManager(Request $req){ 
        $title = 'Quản lý người dùng';
        
        $usersList = $this->partner->getAllPartners($req->tu_khoa, $req->sap_xep, $req->trang_thai);
        return view('Admin.quan-ly-nguoi-dung', compact('title', 'usersList'));
    }
    public function detailUser($id){
        $partner = $this->partner->getSinglePartner($id);
        if($partner == null)
            return redirect()->route('404');
        $title = 'Chi tiết người dùng ' . $partner->name;
        return view('Admin.chi-tiet-nguoi-dung', compact('title', 'partner'));
    }
    public function verifyUser(Request $req, $id){
        $partner = Partner::find($id);
        $req->merge(['partner_status' => 1]);
        $partner->update($req->all());

        return back()->with('success', 'xác thực người dùng thành công!');
    }
}
