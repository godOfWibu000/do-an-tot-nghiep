@extends('Layouts.admin.admin_layout')

@section('redirect')
    <li><a href="{{ route('admin.quan-ly-nguoi-dung.index') }}">Quản lý người dùng</a></li>
    <li class="active">{{ $title }}</li>
@endsection

@section('content')
    <div>
        @if ($partner->partner_status == 0)
            <div class="flex">
                <h4 class="text-warning">
                    <i class="fas fa-times-circle"></i>
                    Chưa xác thực
                </h4>
                &nbsp;
                <button class="btn btn-success" onclick="if(confirm('Xác thực người dùng?')) dieuHuong('{{ route('admin.quan-ly-nguoi-dung.xac-thuc-nguoi-dung', ['id' => request()->id]) }}')">
                    <i class="fas fa-check"></i> Xác thực
                </button>
            </div>
        @else
            <h4 class="text-success">
                <i class="fas fa-check-circle"></i> 
                Đã xác thực
            </h4>
        @endif

        <div>
            <h3>Thông tin cơ bản</h3>
            <hr>

            <div class="flex">
                <div>
                    <img src="{{ asset('assets/img/partner') }}/{{ !empty($partner->logo) ? $partner->logo : 'partner_avatar.png' }}" alt="" width="100px">
                </div>
                &nbsp;&nbsp;&nbsp;
                <div>
                    <h3>{{ $partner->name }}</h3>
                    <br>
                    <h4>
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $partner->address }}
                    </h4>
                    <h4>
                        <i class="fas fa-envelope"></i>
                        {{ $partner->email }}
                    </h4>
                    <h4>
                        <i class="fas fa-phone"></i>
                        {{ $partner->phone_number }}
                    </h4>
                </div>
            </div>
        </div>
        <hr>

        <div>
            <h3>Thông tin doanh nghiệp</h3>
            <hr>
            <h4>Tên công ty: {{ $partner->company_name }}</h4>
            <h4>Mã số thuế: {{ $partner->tax_number }}</h4>
            <h4>Địa chỉ: {{ $partner->company_address }}</h4>
            <h4>Số điện thoại: {{ $partner->company_phone_number }}</h4>
            <h4>Email: {{ $partner->company_email }}</h4>
            <h4>Website: {{ $partner->company_website }}</h4>
        </div>
    </div>
@endsection