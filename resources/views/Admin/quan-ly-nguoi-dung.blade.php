@extends('Layouts.admin.admin_layout')

@php
    $tuKhoa = $search = $tuKhoaMessage = $sapXep = $trangThai = null ;
    if(!empty(request()->tu_khoa)){
        $search = request()->tu_khoa;
        $tuKhoa = '&tu_khoa=' . request()->tu_khoa;
        $tuKhoaMessage = "Kết quả tìm kiếm cho '". request()->tu_khoa . "'";
    }
    if(!empty(request()->sap_xep))
        $sapXep = '&sap_xep=' . request()->sap_xep;
    if(!empty(request()->trang_thai))
        $trangThai = '&trang_thai=' . request()->trang_thai;
@endphp

@section('redirect')
    <li class="active">{{ $title }}</li>
@endsection

@section('content')
    <div class="tim-kiem">
        <form class="form-tim-kiem" action="" method="GET">
            <input type="text" class="form-control" name="tu_khoa" value="{{ $search }}" placeholder="Tìm kiếm...">
            <button class="btn btn-primary"><i class="fa fa-search"></i></button>&nbsp;&nbsp;&nbsp;
            <a href="{{ route('admin.quan-ly-nguoi-dung.index') }}"><button class="btn" type="button"><i class="fas fa-redo-alt"></i></button></a>
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
        <div class="flex-between">
            <div>
                <i class="fas fa-sort"></i>
                <select name="sap_xep" id="" onchange="dieuHuong('{{ route('admin.quan-ly-nguoi-dung.index') }}?page=1{{ $tuKhoa }}' + '&sap_xep=' + this.value + '{{ $trangThai }}')">
                    @if (request()->sap_xep == 'DESC')
                        <option value="DESC">Z-A</option>
                        <option value="ASC">A-Z</option>
                    @else
                        <option value="ASC">A-Z</option>
                        <option value="DESC">Z-A</option>
                    @endif
                </select>
            </div>

            <div>
                <i class="fas fa-filter"></i>
                <select name="" id="" onchange="dieuHuong('{{ route('admin.quan-ly-nguoi-dung.index') }}?page=1{{ $tuKhoa }}{{ $sapXep }}' + '&trang_thai=' + this.value)">
                    @if (request()->trang_thai == '1')
                        <option value="1">Đã xác thực</option>
                        <option value="0">Chưa xác thực</option>
                    @else
                        <option value="0">Chưa xác thực</option>
                        <option value="1">Đã xác thực</option>
                    @endif
                </select>
            </div>
        </div>

        <h4>{{ $tuKhoaMessage }}</h4>

        <table class="table">
            <tr>
                <th>STT</th>
                <th>Tên người dùng</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            @foreach ($usersList as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->address }}</td>
                    <td>{{ $value->phone_number	 }}</td>
                        @if ($value->partner_status == 0)
                            <td>
                                <span class="text-warning">
                                    <i class="fas fa-times-circle"></i>
                                    Chưa xác thực
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.quan-ly-nguoi-dung.chi-tiet-nguoi-dung', ['id' => $value->id ]) }}">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-success" title="Xác thực" onclick="if(confirm('Xác thực người dùng?')) dieuHuong('{{ route('admin.quan-ly-nguoi-dung.xac-thuc-nguoi-dung', ['id' => $value->id ]) }}')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </td>
                        @else
                            <td>
                                <span class="text-success">
                                    <i class="fas fa-check-circle"></i> 
                                    Đã xác thực
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.quan-ly-nguoi-dung.chi-tiet-nguoi-dung', ['id' => $value->id ]) }}">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </a>
                            </td>
                            <td></td>
                        @endif
                    <td><button class="btn btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa người dùng này? Việc xóa dữ liệu sẽ ảnh hưởng đến nhiều dữ liệu khác!')) dieuHuong('')"><i class="fas fa-trash"></i></button></td>
                </tr>
            @endforeach
        </table>
        
        {{-- Pagination --}}
        @php
            $pageNumber = ceil($usersList->total()/1);
            $currentPage = $usersList->currentPage();
        @endphp

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($pageNumber <= 3)
                    @for ($i = 1; $i <= $pageNumber; $i++)
                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                            <a class="page-link" href="{{ route('admin.quan-ly-nguoi-dung.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $sapXep }}">{{ $i }}</a>
                        </li>
                    @endfor
                @else
                    @if ($currentPage != 1)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('admin.quan-ly-nguoi-dung.index') }}?page=1{{ $tuKhoa }}{{ $sapXep }}">Trang đầu</a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('admin.quan-ly-nguoi-dung.index') }}?page={{ $currentPage-- }}{{ $tuKhoa }}{{ $sapXep }}">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                    @endif

                    @if ($currentPage >=3)
                        @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                            @if ($i <= $pageNumber)
                                <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                    <a class="page-link" href="{{ route('admin.quan-ly-nguoi-dung.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $sapXep }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                    @else
                        @for ($i = 1; $i <= 3; $i++)
                            <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                <a class="page-link" href="{{ route('admin.quan-ly-nguoi-dung.index') }}?page={{ $i }}{{ $tuKhoa }}{{ $sapXep }}">{{ $i }}</a>
                            </li>
                        @endfor
                    @endif
                    
                    @if ($currentPage != $pageNumber)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('admin.quan-ly-nguoi-dung.index') }}?page={{ $currentPage++ }}{{ $tuKhoa }}{{ $sapXep }}">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('admin.quan-ly-nguoi-dung.index') }}?page={{ $pageNumber }}{{ $tuKhoa }}{{ $sapXep }}">Trang cuối</a>
                        </li>
                    @endif
                @endif
            </ul>
        </nav>
    </div>
@endsection