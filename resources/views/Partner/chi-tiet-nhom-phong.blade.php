@extends('Layouts.Partner.partner_layout')

@php
    $tuKhoa = $trangThai = $ngayNhanPhong = $ngayTraPhong = null;
    if(!empty(request()->tu_khoa))
        $tuKhoa = '&tu_khoa=' . request()->tu_khoa;
    if(!empty(request()->trang_thai))
        $trangThai = '&trang_thai=' . request()->trang_thai;
    if(!empty(request()->ngay_nhan_phong))
        $ngayNhanPhong = '&ngay_nhan_phong=' . request()->ngay_nhan_phong;
    if(!empty(request()->ngay_tra_phong))
        $ngayTraPhong = '&ngay_tra_phong=' . request()->ngay_tra_phong;
@endphp

@section('redirect')
    <li><a href="{{ route('partner.quan-ly-cho-o.index') }}">Quản lý chỗ ở</a></li>
    <li class="active">{{ $title }}</li>
@endsection

@section('content')
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
    
    {{-- Thong tin nhom phong --}}
    <h3>{{ $roomsGroup->rooms_hotel_name }}</h3>
    <h4><b>Giá phòng:</b> {{ number_format($roomsGroup->room_hotel_price, 0, ',', '.') }} VND</h4>
    <h4><b>Mô tả:</b> {{ $roomsGroup->room_hotel_description }}</h4>

    {{-- Danh sach phong --}}
    <hr>
    <ul class="text-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <div class="flex-between">
        <h4>Danh sách phòng:</h4>
        <button class="btn btn-success" data-toggle="modal" data-target="#themPhong">
            <i class="fas fa-plus"></i> Thêm
        </button>
    </div>
    <div class="tim-kiem">
        <form class="form-tim-kiem" action="" method="GET">
            <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm..." value="{{ !empty(request()->tu_khoa) ? request()->tu_khoa : false }}">
            <button class="btn btn-primary"><i class="fa fa-search"></i></button>&nbsp;&nbsp;&nbsp;
            <a href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}"><button class="btn" type="button"><i class="fas fa-redo-alt"></i></button></a>
        </form>
    </div>
    <br>
    <div class="flex-between">
        <div>
            <select name="" id="" onchange="dieuHuong('{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page=1{{ $tuKhoa }}&trang_thai=' + this.value + '{{ $ngayNhanPhong }}{{ $ngayTraPhong }}')">
                @if (request()->trang_thai == 'Không hoạt động')
                    <option value="Không hoạt động">Không hoạt động</option>
                    <option value="Hoạt động">Hoạt động</option>
                @else
                    <option value="Hoạt động">Hoạt động</option>
                    <option value="Không hoạt động">Không hoạt động</option>
                @endif
            </select>
        </div>
        <div>
            <h4>Ngày còn phòng:</h4>
            <div class="flex">
                <div>
                    <span>Từ</span><input class="form-control" type="date" id="ngay-bat-dau" onchange="dieuHuong('{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page=1{{ $tuKhoa }}{{ $trangThai }}' + '&ngay_nhan_phong=' + this.value + '&ngay_tra_phong=' + document.getElementById('ngay-ket-thuc').value)">
                </div>
                &nbsp;
                <div>
                    <span>Đến</span><input class="form-control" type="date" id="ngay-ket-thuc" onchange="dieuHuong('{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page=1{{ $tuKhoa }}{{ $trangThai }}' + '&ngay_nhan_phong=' + document.getElementById('ngay-bat-dau').value +'&ngay_tra_phong=' + this.value)">
                </div> 
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Số phòng</th>
                <th>Trạng thái</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roomsList as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->room_number }}</td>
                    <td>
                        @if ($value->room_status == 1)
                            <i class="fas fa-circle text-success"></i> Đang hoạt động
                        @else
                            <i class="fas fa-circle text-danger"></i> Không hoạt động
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => $value->room_id]) }}" target="_blank">
                            <button class="btn btn-success">
                                <i class="fas fa-calendar-alt"></i>
                            </button>
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#suaPhong" onclick="loadSuaPhong('{{ $value->room_number }}', '{{ route('partner.quan-ly-cho-o.cap-nhat-phong', ['id' => $value->room_id]) }}')">
                            <i class="fas fa-pen"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger" onclick="if(confirm('Bạn có muốn xóa phòng này!')) dieuHuong('{{ route('partner.quan-ly-cho-o.xoa-phong', ['id' => $value->room_id]) }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Them phong --}}
    <div class="modal fade" id="themPhong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Thêm phòng</h3>
                </div>
                <form method="POST" action="{{ route('partner.quan-ly-cho-o.them-phong', ['id' => request()->id]) }}">
                    @csrf
                    <div class="modal-body">
                        <label for="">Số phòng:</label>
                        <input type="text" class="form-control" name="room_number" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu lại</button>
                        <button type="reset" class="btn btn-warning" id="reset-sua-danh-muc">Đặt lại</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>      
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sua phong --}}
    <div class="modal fade" id="suaPhong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Cập nhật phòng</h3>
                </div>
                <form id="sua-phong" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <label for="">Số phòng:</label>
                        <input id="so-phong" type="text" class="form-control" name="room_number" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu lại</button>
                        <button type="button" class="btn btn-warning" id="reset-sua-danh-muc" onclick="loadSuaPhong(soPhongCanSua, actionCanSua)">Đặt lại</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>      
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Pagination --}}
    @php
        $pageNumber = ceil($roomsList->total()/1);
        $currentPage = $roomsList->currentPage();
    @endphp

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            @if ($pageNumber <= 3)
                @for ($i = 1; $i <= $pageNumber; $i++)
                    <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                        <a class="page-link" href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page={{ $i }}{{ $tuKhoa }}{{ $trangThai }}{{ $ngayNhanPhong }}{{ $ngayTraPhong }}">{{ $i }}</a>
                    </li>
                @endfor
            @else
                @if ($currentPage != 1)
                    <li class="page-item cursor">
                        <a class="page-link" href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page=1{{ $tuKhoa }}{{ $trangThai }}{{ $ngayNhanPhong }}{{ $ngayTraPhong }}">Trang đầu</a>
                    </li>
                    <li class="page-item cursor">
                        <a class="page-link" href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page={{ $currentPage - 1 }}{{ $tuKhoa }}{{ $trangThai }}{{ $ngayNhanPhong }}{{ $ngayTraPhong }}">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    </li>
                @endif

                @if ($currentPage >=3)
                    @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                        @if ($i <= $pageNumber)
                            <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                <a class="page-link" href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page={{ $i }}{{ $tuKhoa }}{{ $trangThai }}{{ $ngayNhanPhong }}{{ $ngayTraPhong }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                @else
                    @for ($i = 1; $i <= 3; $i++)
                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page={{ $i }}{{ $tuKhoa }}{{ $trangThai }}{{ $ngayNhanPhong }}{{ $ngayTraPhong }}">{{ $i }}</a>
                        </li>
                    @endfor
                @endif
                
                @if ($currentPage != $pageNumber)
                    <li class="page-item cursor">
                        <a class="page-link" href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page={{ $currentPage + 1 }}{{ $tuKhoa }}{{ $trangThai }}{{ $ngayNhanPhong }}{{ $ngayTraPhong }}">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item cursor">
                        <a class="page-link" href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => request()->id]) }}?page={{ $pageNumber }}{{ $tuKhoa }}{{ $trangThai }}{{ $ngayNhanPhong }}{{ $ngayTraPhong }}">Trang cuối</a>
                    </li>
                @endif
            @endif
        </ul>
    </nav>
@endsection

@section('js')
    <script>
        @if(empty(request()->ngay_nhan_phong))
            // function getNgayBatDau(){
                document.getElementById('ngay-bat-dau').value = getNgayThang();
            // }
        @else
            document.getElementById('ngay-bat-dau').value = '{{ request()->ngay_nhan_phong}}';
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