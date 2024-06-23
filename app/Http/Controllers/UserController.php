<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidationCustomRequest;
use App\Mail\ForgotPassword;
use App\Models\Customer;
use App\Models\Partner;
use App\Models\ResetToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            'name' => ['required', 'min: 6', 'max: 255'],
            'user_address' => ['nullable', 'min: 6', 'max: 500'],
            'user_phone_number' => ['nullable', 'min: 10', 'max: 10', 'regex: /(0)[0-9]{9}/']
        ], [
            'name.required' => 'Vui lòng nhập tên!',
            'name.min' => 'Tên cần có độ dài tối thiểu là 6 ký tự!',
            'name.max' => 'Tên không dài quá 255 ký tự!',
            'user_address.min' => 'Địa chỉ phải có ít nhất 6 ký tự',
            'user_address.max' => 'Địa chỉ không dài quá 500 ký tự',
            'user_phone_number.max' => 'Số điện thoại cần có 10 chữ số!'
        ]);

        // DB::statement('UPDATE users SET name = ?, user_address = ?, user_phone_number = ? WHERE id = ?', [$name, $address, $phone, $id]);
        try {
            $user = Customer::where('id', $id)->first();
            $user->update($req->all());
        } catch (\Throwable $th) {
            dd($th);
        }
        
        return redirect()->back()->with('successUpdateUser', 'Cập nhật thông tin tài khoản thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(){
        return view('dang-nhap');
    }

    public function postLogin(Request $req){
        $validate = $req->validate([
            'email' => ['required', 'email', 'min: 10'],
            'password' => ['required', 'min: 8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&"*()\-_=+{};:,<.>])(?=.*?[0-9]).{8,100}+$/']
        ], [
            'email.required' => 'Vui lòng nhập email!',
            'email.email' => 'Vui lòng nhập đúng định dạng email tiêu chuẩn!(Ex: abc@mail.com)',
            'email.min' => 'Email cần có độ dài tối thiểu là 10 ký tự!',
            'password.required' => 'Vui lòng nhập mật khẩu!',
            'password.min' => 'Mật khẩu cần có độ dài tối thiểu 8 ký tự!',
            'password.regex' => 'Mật khẩu cần chứa cả chữ cái in hoa, in thường, số và ký tự đặc biệt!'
        ]);

        if(Auth::attempt(['email' => $req->email, 'password' => $req->password])){
            if(Auth::user()->role == 'Customer')
                return redirect()->route('index');
            else if(Auth::user()->role == 'Partner')
                return redirect()->route('partner.thong-tin-doi-tac.index');
            else
                return redirect()->route('admin.index');
        }
        return redirect()->back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác! Vui lòng nhập lại!');
    }

    public function register(){
        return view('dang-ky');
    }

    public function postRegister(Request $req){
        $req->validate([
            'name' => ['required', 'min: 6', 'max: 255'],
            'address' => ['required', 'min: 6', 'max: 500'],
            'phone_number' => ['required', 'min: 10', 'max: 10', 'regex: /(0)[0-9]{9}/'],
            'email' => ['required', 'email', 'min: 10', 'max: 255', 'unique:users,email'],
            'password' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&"*()\-_=+{};:,<.>])(?=.*?[0-9]).{8,255}+$/'],
            'role' => ['required']
        ], [
            'name.required' => 'Vui lòng nhập tên!',
            'name.min' => 'Tên cần có độ dài tối thiểu là 6 ký tự!',
            'name.max' => 'Tên không dài quá 255 ký tự!',
            'address.min' => 'Địa chỉ phải có ít nhất 6 ký tự',
            'address.max' => 'Địa chỉ không dài quá 500 ký tự',
            'phone_number.max' => 'Số điện thoại cần có 10 chữ số!',
            'phone_number.regex' => 'Số điện thoại không hợp lệ!',
            'email.required' => 'Vui lòng nhập email!',
            'email.email' => 'Vui lòng nhập đúng định dạng email tiêu chuẩn!(Ex: abc@mail.com)',
            'email.min' => 'Email cần có độ dài tối thiểu là 10 ký tự!',
            'email.unique' => 'Email đã tồn tại!',
            'password.required' => 'Vui lòng nhập mật khẩu!',
            'password.min' => 'Mật khẩu cần có độ dài tối thiểu 8 ký tự!',
            'password.regex' => 'Mật khẩu cần chứa cả chữ cái in hoa, in thường, số và ký tự đặc biệt!',
            'role.required' => 'Vui lòng chọn loại tài khoản!'
        ]);

        if($req->role != 'Customer' && $req->role != 'Partner')
            return redirect()->route('403');

        $req->merge(['password'=>Hash::make($req->password)]);
        try{
            $id = User::create($req->all())->id;
            $req->merge(['id' => $id]);
            if($req->role == 'Customer')
                Customer::create($req->all());
            else
                Partner::create($req->all());
        }catch(\Throwable $th){
            dd($th);
        }
        
        return redirect()->route('dang-nhap');
    }

    public function logout(){
        Auth::logout();
        return redirect()->back();
    }

    public function quanLyTaiKhoan(){
        $title = 'Quản lý tài khoản';

        $user = Customer::where('id', Auth::user()->id)->first();

        return view('User.thong-tin-tai-khoan', compact('title', 'user'));
    }

    public function matKhauVaBaoMat(){
        $title = 'Mật khẩu và bảo mật';
        return view('User.mat-khau-va-bao-mat', compact('title'));
    }

    public function doiMatKhau(Request $req){
        $req->validate([
            'password' => ['required'],
            'new_password' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&"*()\-_=+{};:,<.>])(?=.*?[0-9]).{8,255}+$/']
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu cũ!',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới!',
            'new_password.regex' => 'Mật khẩu cần chứa ít nhất 8 ký tự, chữ cái in hoa, in thường, số và ký tự đặc biệt!'
        ]);

        // dd(Hash::make($req->password));
        if(!Hash::check($req->password, Auth::user()->password))
            return redirect()->back()->with('errorUpdatePassword', 'Vui lòng nhập chính xác mật khẩu hiện tại của bạn!');
        $req->merge(['new_password'=>Hash::make($req->new_password)]);
        $new_password = $req->new_password;
        DB::statement('UPDATE users SET password = ? WHERE id = ?', [$new_password, Auth::user()->id]);

        return redirect()->back()->with('successUpdatePassword', 'Cập nhật mật khẩu thành công!');
    }

    public function forgot_password(){
        $title = 'Gửi yêu cầu đặt lại mật khẩu';
        return view('User.yeu-cau-dat-lai-mat-khau', compact('title'));
    }
    public function check_forgot_password(Request $req){
        $req->validate([
            'email' => ['required', 'email', 'exists:users']
        ], [
            'email.required' => 'Vui lòng nhập email!',
            'email.email' => 'Vui lòng nhập đúng định dạng email tiêu chuẩn!(Ex: abc@mail.com)',
            'email.exists' => 'Email không tồn tại!'
        ]);
        if(Auth::check())
            Auth::logout();

        $oldUserToken = ResetToken::where('email', '=', $req->email)->first();
        if($oldUserToken)
            $oldUserToken->delete();
        $token = \Str::random(40);
        $tokenData = [
            'email' => $req->email,
            'token' => $token
        ];
        if(ResetToken::create($tokenData)){
            $account = User::where('email', '=', $req->email)->first();
            $id = $account->id;
            $user = null;
            if($account->role == 'Customer'){
                $user = DB::table('customers')
                ->select('customers.name')
                ->where('id', $id)
                ->first();
            }else if($account->role == 'Partner'){
                $user = DB::table('partners')
                ->select('partners.name')
                ->where('id', $id)
                ->first();
            }
            Mail::to($req->email)->send(new ForgotPassword($user, $token));
            return back()->with('success', 'Chúng tôi vừa gửi liên kết đặt lại mật khẩu tới email của bạn. Vui lòng kiểm tra trong hộp thư email!');
        }
        return back()->with('error', 'Gửi email không thành công, vui lòng kiểm tra lại thông tin!');
    }

    public function resetPassword($token){
        $title = 'Đặt lại mật khẩu';
        $userToken = DB::table('reset_tokens')->where('token', '=', $token)->first();
        $email = $userToken->email;
        return view('User.dat-lai-mat-khau', compact('title', 'email'));
    }

    public function saveResetPassword(Request $req, $email){
        $req->validate([
            'password' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&"*()\-_=+{};:,<.>])(?=.*?[0-9]).{8,255}+$/']
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu!',
            'password.min' => 'Mật khẩu cần có độ dài tối thiểu 8 ký tự!',
            'password.regex' => 'Mật khẩu cần chứa cả chữ cái in hoa, in thường, số và ký tự đặc biệt!'
        ]);

        $user = User::where('email', '=', $email)->first();
        try {
            $password = Hash::make($req->password);
            $user->update([
                'password' => $password
            ]);
            ResetToken::where('email', '=', $email)->first()->delete();

            return redirect()->route('dang-nhap')->with('success', 'Đặt lại mật khẩu thành công! Đăng nhập ngay!');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
