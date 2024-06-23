@extends('Layouts.Partner.partner_layout')

@php
    $choO = null;
    $tuKhoa = null;
    $trangThai = null;
    $tuKhoaMessage = null;
    $ngayBatDau = null;
    $ngayKetThuc = null;
    $sapXep = null;
    if(!empty(request()->cho_o))
        $choO = '&cho_o=' . request()->cho_o;
    if(!empty(request()->tu_khoa)){
        $tuKhoa = '&tu_khoa=' . request()->tu_khoa;
        $tuKhoaMessage = "Kết quả tìm kiếm cho '". request()->tu_khoa . "'";
    }
    if(!empty(request()->trang_thai))
        $trangThai = '&trang_thai=' . request()->trang_thai;
    if(!empty(request()->ngay_nhan_phong_tu))
        $ngayBatDau = '&ngay_nhan_phong_tu=' . request()->ngay_nhan_phong_tu;
    if(!empty(request()->ngay_nhan_phong_den))
        $ngayKetThuc = '&ngay_nhan_phong_den=' . request()->ngay_nhan_phong_den;
    if(!empty(request()->sap_xep))
        $sapXep = '&sap_xep=' . request()->sap_xep;
@endphp

@section('onload')
    
@endsection

@section('redirect')
    <li><a href="{{ route('partner.quan-ly-dat-phong.index') }}">Quản lý đặt phòng</a></li>
    <li class="active">Danh sách đặt phòng</li>
@endsection

@section('content')
    <div class="tim-kiem">
        <form class="form-tim-kiem" action="" method="GET">
            <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm..." value="{{ !empty(request()->tu_khoa) ? request()->tu_khoa : false }}">
            <button class="btn btn-primary"><i class="fa fa-search"></i></button>&nbsp;&nbsp;&nbsp;
            <a href="{{ route('partner.quan-ly-dat-phong.index') }}"><button class="btn" type="button"><i class="fas fa-redo-alt"></i></button></a>
        </form>
    </div>
    <hr>

    @if ($message = Session::get('success'))
        <div class="alert alert-success text-center" id="alert-login" role="alert">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger text-center" id="alert-login" role="alert">
            {{ $message }}
        </div>
    @endif

    <div>
        <div>
            <div class="flex-between">
                <div>
                    <h4>Lọc theo trạng thái:</h4>
                    <select name="" id="" onchange="dieuHuong('{{ route('partner.quan-ly-dat-phong.index') }}?page=1' + '{{ $tuKhoa }}{{ $choO }}' + '&trang_thai=' + this.value + '{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}')">
                        <option value="Đang xử lý" selected>Chờ xử lý</option>
                        @foreach (getBookingsStatusForManager() as $value)
                            @if ($value == request()->trang_thai)
                                <option value="{{ $value }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <h4>Lọc theo ngày thuê phòng:</h4>
                    <div class="flex">
                        <div>
                            <span>Từ</span>
                            <input type="date" name="ngay_nhan_phong" class="form-control" value="" id="ngay-bat-dau" onchange="dieuHuong('{{ route('partner.quan-ly-dat-phong.index') }}?page=1{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}&ngay_nhan_phong=' + this.value + '&ngay_tra_phong=' + document.getElementById('ngay-ket-thuc').value + '{{ $sapXep }}')">
                        </div>
                        &nbsp;
                        <div>
                            <span>Đến</span>
                            <input type="date" name="ngay_tra_phong" class="form-control" value="" id="ngay-ket-thuc" onchange="dieuHuong('{{ route('partner.quan-ly-dat-phong.index') }}?page=1{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}&ngay_nhan_phong=' + document.getElementById('ngay-bat-dau').value  + '&ngay_tra_phong=' + this.value + '{{ $sapXep }}')">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            
            <div>
                <div>
                    <h4>Sắp xếp:</h4>
                    <i class="fas fa-sort"></i>
                    <select name="sap_xep" id="" onchange="dieuHuong('{{ route('partner.quan-ly-dat-phong.index') }}?page=1{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}&sap_xep=' + this.value)">
                        @if (request()->sap_xep == 'ASC')
                            <option value="ASC">Cũ hơn</option>
                            <option value="DESC">Mới hơn</option>
                        @else
                            <option value="DESC">Mới hơn</option>
                            <option value="ASC">Cũ hơn</option>
                        @endif
                    </select>
                </div>
            </div>
            <hr>
        </div>

        <h4>{{ $tuKhoaMessage }}</h4>

        <table class="table">
            <tr>
                <th>STT</th>
                <th>Tên chỗ ở</th>
                <th>Người đặt</th>
                <th>Thời gian đặt</th>
                <th>Tổng tiền</th>
                <th>Ngày nhận phòng</th>
                <th>Ngày trả phòng</th>
                <th>Trạng thái</th>
                <th></th>
                <th></th>
            </tr>

            @foreach ($bookings as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->hotel_name }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ date_format(date_create($value->created_at), "H:i d-m-Y") }}</td>
                    <td>{{ number_format($value->my_booking_pay, 0, ',', '.') }} VND</td>
                    <td>{{ date_format(date_create($value->my_booking_checkin), "d-m-Y") }}</td>
                    <td>{{ date_format(date_create($value->my_booking_checkout), "d-m-Y") }}</td>
                    @if ($value->my_booking_status == 'Đang xử lý')
                        <td>
                            <i class="fas fa-circle text-warning"></i>
                            {{ $value->my_booking_status }}
                        </td>
                        <td>
                            <button class="btn btn-primary" title="Xác nhận" onclick="dieuHuong('{{ route('partner.quan-ly-dat-phong.xu-ly-dat-phong', ['id' => $value->my_booking_id ]) }}')">
                                <i class="fas fa-check"></i>
                            </button>
                        </td>
                    @elseif($value->my_booking_status == 'Đã xử lý')
                        <td>
                            <i class="fas fa-circle text-primary"></i>
                            {{ $value->my_booking_status }}
                        </td>
                        <td>
                            <button class="btn btn-success" title="Hoàn thành" onclick="dieuHuong('{{ route('partner.quan-ly-dat-phong.hoan-thanh-dat-phong', ['id' => $value->my_booking_id ]) }}')">
                                <i class="fas fa-check"></i>
                            </button>
                        </td>
                    @elseif($value->my_booking_status == 'Đã hoàn thành')
                        <td>
                            <i class="fas fa-circle text-success"></i>
                            {{ $value->my_booking_status }}
                        </td>
                    @else
                        <td>
                            <i class="fas fa-circle text-danger"></i>
                            {{ $value->my_booking_status }}
                        </td>
                    @endif
                    <td>
                        <a href="{{ route('partner.quan-ly-dat-phong.chi-tiet-dat-phong', ['id' => $value->my_booking_id]) }}">
                            <button class="btn btn-info bg-info">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Pagination --}}
        @php
            $pageNumber = ceil($bookings->total()/1);
            $currentPage = $bookings->currentPage();
        @endphp

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($pageNumber <= 3)
                    @for ($i = 1; $i <= $pageNumber; $i++)
                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                            <a class="page-link" href="{{ route('partner.quan-ly-dat-phong.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}">{{ $i }}</a>
                        </li>
                    @endfor
                @else
                    @if ($currentPage != 1)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-dat-phong.index') }}?page=1{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}">Trang đầu</a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-dat-phong.index') }}?page={{ $currentPage-1 }}{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                    @endif

                    @if ($currentPage >=3)
                        @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                            @if ($i <= $pageNumber)
                                <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                    <a class="page-link" href="{{ route('partner.quan-ly-dat-phong.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                    @else
                        @for ($i = 1; $i <= 3; $i++)
                            <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                <a class="page-link" href="{{ route('partner.quan-ly-dat-phong.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}">{{ $i }}</a>
                            </li>
                        @endfor
                    @endif
                    
                    @if ($currentPage != $pageNumber)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-dat-phong.index') }}?page={{ $currentPage+1 }}{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-dat-phong.index') }}?page={{ $pageNumber }}{{ $tuKhoa }}{{ $choO }}{{ $trangThai }}{{ $ngayBatDau }}{{ $ngayKetThuc }}{{ $sapXep }}">Trang cuối</a>
                        </li>
                    @endif
                @endif
            </ul>
        </nav>

        <hr>
        <a href="{{ route('partner.quan-ly-dat-phong.export-xlsx') }}?tu_khoa={{ request()->tu_khoa }}&trang_thai={{ request()->trang_thai }}&ngay_nhan_phong={{ request()->ngay_nhan_phong }}&ngay_tra_phong={{ request()->ngay_tra_phong }}&sap_xep={{ request()->sap_xep }}">
            <button class="btn btn-success">
                <i class="fas fa-file-excel"></i> In ra file Excel
            </button>
        </a>
    </div>
@endsection

@section('js')
    <script>
        @if(empty(request()->ngay_nhan_phong))
            // function getNgayBatDau(){
                document.getElementById('ngay-bat-dau').value = getNgayThang();
            // }
        @else
            document.getElementById('ngay-bat-dau').value = '{{ request()->ngay_nhan_phong }}';
        @endif
        @if(empty(request()->ngay_tra_phong))
            // function getNgayBatDau(){
                document.getElementById('ngay-ket-thuc').value = getNgayThang();
            // }
        @else
            document.getElementById('ngay-ket-thuc').value = '{{ request()->ngay_tra_phong }}';
        @endif
    </script>
@endsection