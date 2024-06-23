<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Hotels;
use App\Models\MyBooking;
use App\Models\MyBookingsHotel;
use App\Models\Rate;
use App\Models\SaveHotel;
use App\Models\StatisticalBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\PDF;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PartnerController extends Controller
{
    private $hotels;
    private $myBookings;
    private $myBookingHotels;
    private $rates;
    private $saveHotels;
    private $statisticals;

    public function __construct()
    {
        $this->hotels = new Hotels();
        $this->myBookings = new MyBooking();
        $this->myBookingHotels = new MyBookingsHotel();
        $this->rates = new Rate();
        $this->saveHotels = new SaveHotel();
        $this->statisticals = new StatisticalBookings();
    }

    public function accountManager(){
        $title = 'Quản lý tài khoản';
        return view('Partner.tai-khoan', compact('title'));
    }

    public function partnerManager(){
        $title = 'Quản lý thông tin đối tác';
        $partner = Partner::find(Auth::user()->id);
        return view('Partner.thong-tin-doi-tac', compact('title', 'partner'));
    }
    public function uploadImagePartner(Request $req, $oldImage){
        $req->validate([
            'image' => ['required', 'max: 2048', 'mimes: jpg,jpeg,png']
        ],[
            'image.required' => 'Vui lòng tải lên ảnh!',
            'image.max' => 'File ảnh vượt quá kích thước! Kích thước tối đa cho phép là 2MB',
            'image.mimes' => 'File ảnh không hợp lệ! File ảnh phải là dạng file .jpeg, jpg, png'
        ]);
        if(!empty($req->image)){
            $fileName = uploadImage($req->image, 'assets/img/partner');
        }

        $req->merge(['logo' => $fileName]);

        try {
            $partner = Partner::find(Auth::user()->id);
            $partner->update($req->all());
        } catch (\Throwable $th) {
            dd($th);
        }
        if($oldImage != 0){
            $oldFileName = public_path('assets/img/partner') . '\\' . $oldImage;
            if(File::exists($oldFileName))
                unlink($oldFileName);
        }

        return back()->with('success', 'Cập nhật ảnh thành công!');
    }
    public function updateInforPartner(Request $req){
        $req->validate([
            'name' => ['required', 'min: 6', 'max: 255'],
            'address' => ['required', 'min: 6', 'max: 500'],
            'phone_number' => ['required', 'min: 10', 'max: 10', 'regex: /(0)[0-9]{9}/']
        ], [
            'name.required' => 'Vui lòng nhập tên!',
            'name.min' => 'Tên cần có độ dài tối thiểu là 6 ký tự!',
            'name.max' => 'Tên không dài quá 255 ký tự!',
            'address.min' => 'Địa chỉ phải có ít nhất 6 ký tự!',
            'address.max' => 'Địa chỉ không dài quá 500 ký tự!',
            'phone_number.max' => 'Số điện thoại cần có 10 chữ số!',
            'phone_number.regex' => 'Số điện thoại không hợp lệ!'
        ]);

        $partner = Partner::find(Auth::user()->id);
        try {
            $partner->update($req->all());
        } catch (\Throwable $th) {
            dd($th);
        }
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
    public function updateInforDetail(Request $req){
        $req->validate([
            'company_name' => ['required', 'min:6', 'max: 255'],
            'tax_number' => ['required', 'max:10', 'regex: /[0-9]{10}/'],
            'company_address' => ['required', 'min: 6', 'max: 500'],
            'company_phone_number' => ['required', 'max: 10', 'regex: /(0)[0-9]{9}/'],
            'company_email' => ['required', 'email', 'min: 10', 'max: 255'],
            'company_website' => ['nullable', 'min:6', 'max:255']
        ],
        [
            'company_name.required' => 'Vui lòng nhập tên doanh nghiệp!',
            'company_name.min' => 'Tên cần ít nhất 6 ký tự!',
            'company_name.max' => 'Tên không vượt quá 255 ký tự!',
            'tax_number.required' => 'Vui lòng nhập mã số thuế!',
            'tax_number.max' => 'Mã số thuế không vượt quá 10 ký tự!',
            'tax_number.regex' => 'Mã số thuế không hợp lệ!',
            'company_address.required' => 'Vui lòng nhập địa chỉ!',
            'company_address.min' => 'Địa chỉ cần ít nhất 6 ký tự!',
            'company_address.max' => 'Địa chỉ vượt quá 500 ký tự!',
            'company_phone_number.required' => 'Vui lòng nhập số điện thoại!',
            'company_phone_number.max' => 'Số điện thoại không vượt quá 10 ký tự!',
            'company_phone_number.regex' => 'Số điện thoại không hợp lệ!',
            'company_email.required' => 'Vui lòng nhập email!',
            'company_email.email' => 'Email không hợp lệ!',
            'company_email.min' => 'Email cần ít nhất 6 ký tự!',
            'company_email.max' => 'Email không vượt quá 255 ký tự!',
            'company_website.min' => 'Website cần ít nhất 6 ký tự!',
            'company_website.max' => 'Website không vượt quá 255 ký tự!',
        ]);

        $partner = Partner::find(Auth::user()->id);
        try {
            $partner->update($req->all());
        } catch (\Throwable $th) {
            dd($th);
        }
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function hotelsManager(Request $req){
        $title = 'Quản lý chỗ ở';
        $hotelsList = $this->hotels->getFilterHotelsAdmin($req->tu_khoa, $req->loc_theo_danh_muc, $req->loc_theo_khu_vuc, $req->sap_xep_theo_ten, $req->sap_xep_theo_gia);
        return view('Partner.quan-ly-cho-o', compact('title', 'hotelsList'));
    }

    public function bookingsManager(Request $req){
        $title = 'Quản lý đặt phòng';
        if($req->cho_o)
            $title .= '- ' . Hotels::find($req->cho_o)->hotel_name;
        $bookings = $this->myBookings->getAllBookings($req->tu_khoa, $req->trang_thai, $req->ngay_nhan_phong, $req->ngay_tra_phong, $req->sap_xep);
        return view('Partner.quan-ly-dat-phong', compact('title', 'bookings'));
    }
    public function exportBookingXLSX(Request $req){
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getDefaultColumnDimension()->setWidth(150, 'pt');
        $activeWorksheet->setCellValue('A1', 'STT')
        ->setCellValue('B1', 'Tên khách hàng')
        ->setCellValue('C1', 'Địa chỉ')
        ->setCellValue('D1', 'Số điện thoại')
        ->setCellValue('E1', 'Email')
        ->setCellValue('F1', 'Tên khách sạn')
        ->setCellValue('G1', 'Ngày checkin')
        ->setCellValue('H1', 'Ngày checkout')
        ->setCellValue('I1', 'Tổng thanh toán');
        $bookings = $this->myBookings->getAllBookings($req->tu_khoa, $req->trang_thai, $req->ngay_nhan_phong, $req->ngay_tra_phong, $req->sap_xep);
        foreach ($bookings as $key => $value) {
            $activeWorksheet->setCellValue('A' . $key+2, $key+1);
            $activeWorksheet->setCellValue('B' . $key+2, $value->name);
            $activeWorksheet->setCellValue('C' . $key+2, $value->address);
            $activeWorksheet->setCellValue('D' . $key+2, $value->phone_number);
            $activeWorksheet->setCellValue('E' . $key+2, $value->email);
            $activeWorksheet->setCellValue('F' . $key+2, $value->hotel_name);
            $activeWorksheet->setCellValue('G' . $key+2, date_format(date_create($value->my_booking_checkin), "d-m-Y"));
            $activeWorksheet->setCellValue('H' . $key+2, date_format(date_create($value->my_booking_checkout), "d-m-Y"));
            $activeWorksheet->setCellValue('I' . $key+2, number_format($value->my_booking_pay, 0, ',', '.') . ' VND');
        }

        $writer = new Xlsx($spreadsheet);
        // $writer->save('helloworld.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="booking.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
    public function printBill($id){
        $pdf = app('dompdf.wrapper');
        
        $booking = $this->myBookings->getSingleBooking($id);
        $bookingDetails = $this->myBookingHotels->getHotelsForBooking($id);

        $data = [
            'title' => 'Hóa đơn thanh toán',
            'booking' => $booking,
            'bookingDetail' => $bookingDetails
        ];
        $pdf->loadView('print.bill-pdf', $data);
        return $pdf->download('bill.pdf');
    }

    public function dashboard(Request $req){
        $title = 'Thống kê đặt phòng';
        $hotelsList = $this->hotels->getFilterHotelsAdmin(Auth::user()->id, $req->tu_khoa, $req->loc_theo_danh_muc, $req->loc_theo_khu_vuc, $req->sap_xep_theo_ten, $req->sap_xep_theo_gia);
        return view('Partner.thong-ke', compact('title', 'hotelsList'));
    }
    public function statisticalBooking(Request $req, $id){
        $hotel = Hotels::find($id);
        $statistical = $this->statisticals->getFilterStatisticalBookings($id, $req->thong_ke_tu, $req->thong_ke_den, $req->thang, $req->sap_xep);
        if($hotel == null)
            return redirect()->route('404');
        $title = 'Thống kê đặt phòng ' . $hotel->hotel_name;

        return view('Partner.thong-ke-dat-phong', compact('title', 'statistical'));
    }
    public function exportStatisticalXLSX(Request $req, $id){
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getDefaultColumnDimension()->setWidth(150, 'pt');
        $activeWorksheet->setCellValue('A1', 'STT')
        ->setCellValue('B1', 'Ngày thống kê')
        ->setCellValue('C1', 'Doanh thu')
        ->setCellValue('D1', 'Số lượng đặt phòng');
        $statistical = $this->statisticals->getFilterStatisticalBookings($id, $req->thong_ke_tu, $req->thong_ke_den, $req->thang, $req->sap_xep);
        foreach ($statistical as $key => $value) {
            $activeWorksheet->setCellValue('A' . $key+2, $key+1);
            $activeWorksheet->setCellValue('B' . $key+2, date_format(date_create($value->statistical_bookings_date), "d-m-Y"));
            $activeWorksheet->setCellValue('C' . $key+2, number_format($value->revenue, 0, ',', '.') . ' VND');
            $activeWorksheet->setCellValue('D' . $key+2, $value->total_number_bookings);
        }
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="statistical_booking.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
