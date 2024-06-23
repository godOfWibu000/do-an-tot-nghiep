function validateDangNhap(){
    let validate = validateMatKhau('password', 'message-password');
    return validate;
}
function validateDangKy(){
    let validate1 = validateMatKhau('password', 'message-password');
    let validate2 = validateNhapLaiMatKhau('password', 'repassword', 'message-repassword');
    if(validate1 == false || validate2 == false)
        return false;
    return true;
}