@extends('Layouts.Partner.partner_layout')

@php
    $tuKhoa = null;
    $khuVuc = null;
    $tuKhoaMessage = null;
    $locTheoDanhMuc = null;
    $locTheoKhuVuc = null;
    $sapXepTheoTen = null;
    $sapXepTheoGia = null;
    if(!empty(request()->tu_khoa)){
        $tuKhoa = '&tu_khoa=' . request()->tu_khoa;
        $tuKhoaMessage = "Kết quả tìm kiếm cho '". request()->tu_khoa . "'";
    }
    if(!empty(request()->loc_theo_danh_muc))
        $locTheoDanhMuc = '&loc_theo_danh_muc=' . request()->loc_theo_danh_muc;
    if(!empty(request()->loc_theo_khu_vuc)){
        $khuVuc = request()->loc_theo_khu_vuc;
        $locTheoKhuVuc = '&loc_theo_khu_vuc=' . request()->loc_theo_khu_vuc;
    }
    if(!empty(request()->sap_xep_theo_ten))
        $sapXepTheoTen = '&sap_xep_theo_ten=' . request()->sap_xep_theo_ten;
    if(!empty(request()->sap_xep_theo_gia))
        $sapXepTheoGia = '&sap_xep_theo_gia=' . request()->sap_xep_theo_gia;
@endphp

@section('redirect')
    <li><a href="{{ route('partner.quan-ly-cho-o.index') }}">Quản lý chỗ ở</a></li>
    <li class="active">Danh sách chỗ ở</li>
@endsection

@section('content')
    <div class="tim-kiem">
        <form class="form-tim-kiem" action="" method="GET">
            <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm..." value="{{ !empty(request()->tu_khoa) ? request()->tu_khoa : false }}">
            <button class="btn btn-primary"><i class="fa fa-search"></i></button>&nbsp;&nbsp;&nbsp;
            <a href="{{ route('partner.quan-ly-cho-o.index') }}"><button class="btn" type="button"><i class="fas fa-redo-alt"></i></button></a>
        </form>

        <a href="{{ route('partner.quan-ly-cho-o.them-cho-o') }}">
            <button class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm
            </button>
        </a>
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
                    <h4>Lọc theo danh mục:</h4>
                    <select name="" id="" onchange="dieuHuong('{{ route('partner.quan-ly-cho-o.index') }}?page=1{{ $tuKhoa }}&loc_theo_danh_muc=' + this.value + '{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}')">
                        <option value="all">Tất cả</option>
                        @foreach (getCategories() as $value)
                            @if ($value->category_id == request()->loc_theo_danh_muc)
                                <option value="{{ $value->category_id }}" selected>{{ $value->category_name }}</option>
                            @else
                                <option value="{{ $value->category_id }}">{{ $value->category_name }}</option>
                            @endif
                        @endforeach                                                       
                    </select>
                </div>
                <div>
                    <h4>Lọc theo khu vực:</h4>
                    <input type="text" list="hotel-area" class="form-control" value="{{ $khuVuc }}" onchange="dieuHuong(`{{ route('partner.quan-ly-cho-o.index') }}?page=1{{ $tuKhoa }}{{ $locTheoDanhMuc }}&loc_theo_khu_vuc=${this.value}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}`)">
                    <datalist id="hotel-area">
                    </datalist>
                </div>
            </div>
            <hr>
            
            <div class="flex-between">
                <div>
                    <h4>Sắp xếp theo tên:</h4>
                    <i class="fas fa-sort"></i>
                    <select name="sap_xep" id="" onchange="dieuHuong(`{{ route('partner.quan-ly-cho-o.index') }}?page=1{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}&sap_xep_theo_ten=${this.value}`)">
                        @if (request()->sap_xep_theo_ten == 'DESC')
                            <option value="DESC">Z-A</option>
                            <option value="ASC">A-Z</option>
                        @else
                            <option value="ASC">A-Z</option>
                            <option value="DESC">Z-A</option>
                        @endif
                    </select>
                </div>

                <div>
                    <h4>Sắp xếp theo giá:</h4>
                    <i class="fas fa-sort"></i>
                    <select name="sap_xep" id="" onchange="dieuHuong(`{{ route('partner.quan-ly-cho-o.index') }}?page=1{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}&sap_xep_theo_gia=${this.value}`)">
                        @if (request()->sap_xep_theo_gia == 'DESC')
                            <option value="DESC">Cao hơn</option>
                            <option value="ASC">Thấp hơn</option>
                        @else
                            <option value="ASC">Thấp hơn</option>
                            <option value="DESC">Cao hơn</option>
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
                <th>Khu vực</th>
                <th>Danh mục</th>
                <th>Địa chỉ chỗ ở</th>
                <th>Giá chỗ ở</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            @foreach ($hotelsList as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->hotel_name }}</td>
                    <td>{{ $value->child_area_name }} - {{ $value->hotel_area }}</td>
                    <td>{{ $value->category_name }}</td>
                    <td>{{ $value->hotel_address }}</td>
                    <td>{{ number_format($value->hotel_new_price, 0, ',', '.') }} VND</td>
                    <td>
                        <a href="{{ route('partner.quan-ly-cho-o.chi-tiet-cho-o', ['id' => $value->hotel_id ]) }}">
                            <button class="btn btn-info bg-info">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('partner.quan-ly-cho-o.cap-nhat-cho-o', ['id' => $value->hotel_id ]) }}">
                            <button class="btn btn-primary">
                                <i class="fas fa-pen"></i>
                            </button>
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa chỗ ở này?')) dieuHuong('{{ route('partner.quan-ly-cho-o.xoa-cho-o', ['id' => $value->hotel_id ]) }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                    <td>
                        <a href="{{ route('partner.quan-ly-dat-phong.index') }}?cho_o={{ $value->hotel_id }}">
                            <button class="btn btn-success">Xem đặt phòng</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Pagination --}}
        @php
            $pageNumber = ceil($hotelsList->total()/10);
            $currentPage = $hotelsList->currentPage();
        @endphp

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($pageNumber <= 3)
                    @for ($i = 1; $i <= $pageNumber; $i++)
                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}">{{ $i }}</a>
                        </li>
                    @endfor
                @else
                    @if ($currentPage != 1)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.index') }}?page=1{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}">Trang đầu</a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.index') }}?page={{ $currentPage-1 }}{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                    @endif

                    @if ($currentPage >=3)
                        @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                            @if ($i <= $pageNumber)
                                <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                    <a class="page-link" href="{{ route('partner.quan-ly-cho-o.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                    @else
                        @for ($i = 1; $i <= 3; $i++)
                            <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                <a class="page-link" href="{{ route('partner.quan-ly-cho-o.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}">{{ $i }}</a>
                            </li>
                        @endfor
                    @endif
                    
                    @if ($currentPage != $pageNumber)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.index') }}?page={{ $currentPage+1 }}{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.index') }}?page={{ $pageNumber }}{{ $tuKhoa }}{{ $locTheoDanhMuc }}{{ $locTheoKhuVuc }}{{ $sapXepTheoTen }}{{ $sapXepTheoGia }}">Trang cuối</a>
                        </li>
                    @endif
                @endif
            </ul>
        </nav>
    </div>
@endsection

@section('js')
    <script>
        window.onload = function() { getArea() }
    </script>
@endsection