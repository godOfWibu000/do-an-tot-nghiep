<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RateAndSaveHotelController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::prefix('User')->name('user')->group(function(){
    
// });

// Trang chinh
Route::get('/', [HomeController::class, 'index'])->name('index');

// Dang nhap, dang ky
Route::get('/dang-nhap', [UserController::class, 'login'])->middleware('CheckLoginInLoginPage')->name('dang-nhap');
Route::post('/dang-nhap', [UserController::class, 'postLogin']);
Route::get('/dang-ky', [UserController::class, 'register'])->middleware('CheckLoginInLoginPage')->name('dang-ky');
Route::post('/dang-ky', [UserController::class, 'postRegister']);
Route::get('/dang-xuat', [UserController::class, 'logout'])->name('dang-xuat');
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Quan ly tai khoan ca nhan
Route::prefix('/tai-khoan')->middleware(['auth', 'CheckRole:Customer'])->name('tai-khoan.')->group(function(){
    Route::get('/', [UserController::class, 'quanLyTaiKhoan'])->name('quan-ly-tai-khoan');
    Route::get('/mat-khau-va-bao-mat', [UserController::class, 'matKhauVaBaoMat'])->name('mat-khau-va-bao-mat');
    Route::post('/sua-tai-khoan/{id}', [UserController::class, 'update'])->name('sua-tai-khoan');
    Route::post('/doi-mat-khau', [UserController::class, 'doiMatKhau'])->name('doi-mat-khau');
});

Route::get('/yeu-cau-dat-lai-mat-khau', [UserController::class, 'forgot_password'])->name('yeu-cau-dat-lai-mat-khau');
Route::post('/kiem-tra-dat-lai-mat-khau', [UserController::class, 'check_forgot_password'])->name('kiem-tra-dat-lai-mat-khau');
Route::get('/dat-lai-mat-khau/{token}', [UserController::class, 'resetPassword'])->name('dat-lai-mat-khau');
Route::post('/luu-dat-lai-mat-khau/{email}', [UserController::class, 'saveResetPassword'])->name('luu-dat-lai-mat-khau');

// Cho o
Route::prefix('/cho-o')->name('cho-o.')->group(function(){
    Route::get('/', [HotelsController::class, 'index'])->name('index');
    Route::get('/cho-o-hang-dau/{area}', [HotelsController::class, 'getTopHotelsForArea'])->name('cho-o-hang-dau');
    Route::get('/danh-muc/{id}', [HotelsController::class, 'showForCategory'])->name('danh-muc');
    Route::get('/tim-kiem-cho-o', [HotelsController::class, 'search'])->name('tim-kiem-cho-o');
    Route::get('/chi-tiet-cho-o/{id}', [HotelsController::class, 'show'])->name('chi-tiet-cho-o');
    Route::get('/khuyen-mai', [HotelsController::class, 'khuyenMai'])->name('khuyen-mai');
});

// Dat phong
Route::prefix('/dat-phong')->middleware(['auth', 'CheckRole:Customer'])->name('dat-phong.')->group(function(){
    Route::get('/dat-phong-cua-toi', [BookingController::class, 'datPhongCuaToi'])->name('dat-phong-cua-toi');
    Route::get('/chi-tiet-dat-phong/{id}', [BookingController::class, 'chiTietDatPhong'])->name('chi-tiet-dat-phong');

    Route::post('/kiem-tra-so-luong-phong/{id}', [BookingController::class, 'kTraSoPhongConLai'])->name('kiem-tra-so-luong-phong');

    Route::post('/dat-phong', [BookingController::class, 'datPhong'])->name('dat-phong');
    Route::get('/xac-nhan-dat-phong/{id}', [BookingController::class, 'xacNhanDatPhong'])->name('xac-nhan-dat-phong');
    Route::post('cap-nhat-dat-phong/{id}', [BookingController::class, 'capNhatDatPhong'])->name('cap-nhat-dat-phong');
    Route::get('/huy-dat-phong/{id}', [BookingController::class, 'huyDatPhong'])->name('huy-dat-phong');
    Route::get('/xoa-phong/{bookingId}/{roomId}', [BookingController::class, 'xoaPhong'])->name('xoa-phong');
    Route::get('/xoa-dat-phong/{id}', [BookingController::class, 'xoaDatPhong'])->name('xoa-dat-phong');
});

// Danh gia va luu cho o
Route::prefix('/danh-gia-va-da-luu')->middleware(['auth', 'CheckRole:Customer'])->name('danh-gia-va-da-luu.')->group(function(){
    Route::get('/cho-o-da-luu', [RateAndSaveHotelController::class, 'choODaLuu'])->name('cho-o-da-luu');
    Route::post('/luu-cho-o', [RateAndSaveHotelController::class, 'saveHotel'])->name('luu-cho-o');
    Route::get('/bo-luu-cho-o/{id}', [RateAndSaveHotelController::class, 'unSaveHotel'])->name('bo-luu-cho-o');
    Route::get('/xoa-luu-cho-o/{id}', [RateAndSaveHotelController::class, 'deleteSaveHotel'])->name('xoa-luu-cho-o');

    Route::post('/danh-gia/{id}', [RateAndSaveHotelController::class, 'rateHotel'])->name('danh-gia');
    Route::post('/sua-danh-gia/{id}', [RateAndSaveHotelController::class, 'editRateHotel'])->name('sua-danh-gia');
    Route::get('/xoa-danh-gia/{id}', [RateAndSaveHotelController::class, 'deleteRateHotelByUser'])->name('xoa-danh-gia');
});
Route::get('/loc-danh-gia/{id}', [RateAndSaveHotelController::class, 'filterRatesHotel'])->name('loc-danh-gia');

// Admin
Route::prefix('/admin')->name('admin.')->middleware(['auth', 'CheckRole:Admin'])->group(function(){
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::prefix('/quan-ly-danh-muc')->name('quan-ly-danh-muc.')->group(function(){
        Route::get('/', [AdminController::class, 'categoriesManager'])->name('index');
        Route::post('/them-danh-muc', [CategoriesController::class, 'store'])->name('them-danh-muc');
        Route::post('/sua-danh-muc/{id}', [CategoriesController::class, 'update'])->name('sua-danh-muc');
        Route::get('/xoa-danh-muc/{id}', [CategoriesController::class, 'destroy'])->name('xoa-danh-muc');
    });
    Route::prefix('/quan-ly-khu-vuc')->name('quan-ly-khu-vuc.')->group(function(){
        Route::get('index', [AdminController::class, 'areaManager'])->name('index');
        Route::get('/chi-tiet-khu-vuc/{area}', [AreaController::class, 'show'])->name('chi-tiet-khu-vuc');
        Route::post('/them-khu-vuc/{area}', [AreaController::class, 'store'])->name('them-khu-vuc');
        Route::post('/cap-nhat-khu-vuc/{id}', [AreaController::class, 'update'])->name('cap-nhat-khu-vuc');
        Route::get('/xoa-khu-vuc/{id}', [AreaController::class, 'destroy'])->name('xoa-khu-vuc');
    });
    Route::prefix('/quan-ly-nguoi-dung')->name('quan-ly-nguoi-dung.')->group(function(){
        Route::get('/index', [AdminController::class, 'usersManager'])->name('index');
        Route::get('/chi-tiet-nguoi-dung/{id}', [AdminController::class, 'detailUser'])->name('chi-tiet-nguoi-dung');
        Route::get('/xac-thuc-nguoi-dung/{id}', [AdminController::class, 'verifyUser'])->name('xac-thuc-nguoi-dung');
    });
});

// Partner
Route::prefix('/partner')->name('partner.')->middleware(['auth', 'CheckRole:Partner'])->group(function(){
    Route::prefix('/thong-tin-doi-tac')->name('thong-tin-doi-tac.')->group(function(){
        Route::get('/', [PartnerController::class, 'partnerManager'])->name('index');
        Route::post('/doi-anh/{oldImage}', [PartnerController::class, 'uploadImagePartner'])->name('doi-anh');
        Route::post('/cap-nhat-thong-tin-co-ban', [PartnerController::class, 'updateInforPartner'])->name('cap-nhat-thong-tin-co-ban');
        Route::post('/cap-nhat-thong-tin-chi-tiet', [PartnerController::class, 'updateInforDetail'])->name('cap-nhat-thong-tin-chi-tiet');
    });
    Route::prefix('/quan-ly-cho-o')->name('quan-ly-cho-o.')->middleware('CheckPartner')->group(function(){
        Route::get('/', [PartnerController::class, 'hotelsManager'])->name('index');
        Route::get('/chi-tiet-cho-o/{id}', [HotelsController::class, 'show_Admin'])->name('chi-tiet-cho-o');
        Route::get('/them-cho-o', [HotelsController::class, 'create'])->name('them-cho-o');
        Route::post('/them-cho-o', [HotelsController::class, 'store']);
        Route::get('/cap-nhat-cho-o/{id}', [HotelsController::class, 'edit'])->name('cap-nhat-cho-o');
        Route::post('/cap-nhat-cho-o/{id}', [HotelsController::class, 'update']);
        Route::get('/xoa-cho-o/{id}', [HotelsController::class, 'destroy'])->name('xoa-cho-o');

        Route::post('/them-anh-cho-o/{id}', [HotelsController::class, 'createImageHotel'])->name('them-anh-cho-o');
        Route::get('/xoa-anh-cho-o/{id}', [HotelsController::class, 'deleteImageHotel'])->name('xoa-anh-cho-o');
        
        Route::get('/chi-tiet-nhom-phong/{id}', [HotelsController::class, 'infomationRoomsGroup'])->name('chi-tiet-nhom-phong');
        Route::post('/them-nhom-phong/{id}', [HotelsController::class, 'storeRoomsGroup'])->name('them-nhom-phong');
        Route::post('/cap-nhat-nhom-phong/{id}', [HotelsController::class, 'updateRoomsGroup'])->name('cap-nhat-nhom-phong');
        Route::get('/xoa-nhom-phong/{id}', [HotelsController::class, 'deleteRoomsGroup'])->name('xoa-nhom-phong');

        Route::post('/them-phong/{id}', [HotelsController::class, 'themPhong'])->name('them-phong');
        Route::post('/cap-nhat-phong/{id}', [HotelsController::class, 'capNhatPhong'])->name('cap-nhat-phong');
        Route::get('/xoa-phong/{id}', [HotelsController::class, 'xoaPhong'])->name('xoa-phong');

        Route::get('/lich-dat-phong/{id}', [HotelsController::class, 'lichDatPhong'])->name('lich-dat-phong');
        Route::post('/them-lich-dat-phong/{id}', [HotelsController::class, 'themLichDatPhong'])->name('them-lich-dat-phong');
        Route::get('/xoa-lich-dat-phong/{id}/{date}', [HotelsController::class, 'xoaLichDatPhong'])->name('xoa-lich-dat-phong');
    });
    Route::prefix('/quan-ly-dat-phong')->name('quan-ly-dat-phong.')->middleware('CheckPartner')->group(function(){
        Route::get('/', [PartnerController::class, 'bookingsManager'])->name('index');
        Route::get('/chi-tiet-dat-phong/{id}', [BookingController::class, 'chiTietDatPhongAdmin'])->name('chi-tiet-dat-phong');
        Route::get('/chon-phong/{bookingID}/{roomID}/{checkIn}/{checkOut}', [BookingController::class, 'chonPhong'])->name('chon-phong');
        Route::get('/xoa-phong/{bookingID}/{roomID}/{checkIn}/{checkOut}', [BookingController::class, 'xoaPhongDat'])->name('xoa-phong');
        Route::get('/xu-ly-dat-phong/{id}', [BookingController::class, 'xuLyDatPhong'])->name('xu-ly-dat-phong');
        Route::get('/xu-ly-lai/{id}', [BookingController::class, 'xuLyLai'])->name('xu-ly-lai');
        Route::get('/het-phong/{id}', [BookingController::class, 'hetPhong'])->name('het-phong');
        Route::get('/hoan-thanh-dat-phong/{id}', [BookingController::class, 'hoanThanhDatPhong'])->name('hoan-thanh-dat-phong');

        Route::get('/export-xlsx', [PartnerController::class, 'exportBookingXLSX'])->name('export-xlsx');
        Route::get('/print-bill/{id}' ,[PartnerController::class, 'printBill'])->name('print-bill');
    });
    Route::prefix('/thong-ke')->name('thong-ke.')->group(function(){
        Route::get('/', [PartnerController::class, 'dashboard'])->name('index');
        Route::get('/chi-tiet-thong-ke-dat-phong/{id}', [PartnerController::class, 'statisticalBooking'])->name('chi-tiet-thong-ke-dat-phong');
        Route::get('/export-xlsx/{id}', [PartnerController::class, 'exportStatisticalXLSX'])->name('export-xlsx');
    });

    Route::prefix('/tai-khoan')->name('tai-khoan.')->group(function(){
        Route::get('/', [PartnerController::class, 'accountManager'])->name('index');
    });
});

Route::post('/vnpay-payment', [PaymentController::class, 'vnpayPayment'])->name('vnpay-payment');

Route::get('/tai-khoan-chua-xac-thuc', function(){
    return view('tai-khoan-chua-xac-thuc');
})->name('tai-khoan-chua-xac-thuc');

Route::get('/403', function(){
    return view('403');
})->name('403');

Route::get('/404', function(){
    return view('404');
})->name('404');

Route::get('/test-mail', [HomeController::class, 'testMail'])->name('test-mail');
