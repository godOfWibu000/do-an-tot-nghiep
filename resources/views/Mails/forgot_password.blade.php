<div style="text-align: center;border: 2px solid #34495e; padding: 20px 2%;border-radius: 10px;">
    <h2>
        Đặt lại mật khẩu cho

        <span style="color: #3498db;">
            {{ $user->name }}
        </span>
    </h2>
    <hr>
    
    <p>Xin chào <b style="color: #3498db;">{{ $user->name }}</b>! Chúng tôi vừa nhận được yêu cầu đặt lại mật khẩu từ bạn.
    Vui lòng không chia sẻ email này với bất kỳ ai!</p>
    
    <a href="{{ route('dat-lai-mat-khau', ['token' => $token]) }}" target="_blank">
        <button style="border: none; padding: 10px 2%; color: white; background-color: #27ae60;cursor: pointer;box-shadow: 5px 5px #2ecc71;">
            Nhấn vào đây để đặt lại mật khẩu
        </button>
    </a>
</div>