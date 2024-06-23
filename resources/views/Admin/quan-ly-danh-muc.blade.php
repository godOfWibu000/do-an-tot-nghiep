@extends('Layouts.admin.admin_layout')

@php
    $tuKhoa = null;
    $tuKhoaMessage = null;
    $sapXep = '?sap_xep=';
    if(!empty(request()->tu_khoa)){
        $tuKhoa = '?tu_khoa=' . request()->tu_khoa;
        $tuKhoaMessage = "Kết quả tìm kiếm cho '". request()->tu_khoa . "'";
        $sapXep = '&sap_xep=';
    }
@endphp

@section('redirect')
    <li class="active">{{ $title }}</li>
@endsection

@section('content')
    <div class="tim-kiem">
        <form class="form-tim-kiem" action="" method="GET">
            <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm...">
            <button class="btn btn-primary"><i class="fa fa-search"></i></button>&nbsp;&nbsp;&nbsp;
            <a href="{{ route('admin.quan-ly-danh-muc.index') }}"><button class="btn" type="button"><i class="fas fa-redo-alt"></i></button></a>
        </form>

        <button class="btn btn-success" data-toggle="modal" data-target="#themDanhMuc">
            <i class="fas fa-plus"></i> Thêm
        </button>
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

    <ul class="text-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    <!-- Them danh muc -->
    <div class="modal fade" id="themDanhMuc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Thêm danh mục</h3>
            </div>
            <form method="POST" action="{{ route('admin.quan-ly-danh-muc.them-danh-muc') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <label for="">Tên danh mục:</label>
                    <input type="text" name="ten_danh_muc" class="form-control" minlength="6" maxlength="255" required>
                    <label for="">Ảnh danh mục:</label>
                    <br>
                    <img src="https://cdn-icons-png.flaticon.com/512/1160/1160358.png" alt="" id="anh-danh-muc" width="150px" height="150px">
                    <input type="file" name="anh_danh_muc" accept=".jpg,.jpeg,.png" onchange="checkSizeImageUpload(this, 'message-them-anh-danh-muc');hienAnhUpload(this, 'anh-danh-muc')" required>
                    <p class="text-danger" id="message-them-anh-danh-muc"></p>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn btn-warning">Đặt lại</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <!-- Sua danh muc -->
    <div class="modal fade" id="suaDanhMuc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Cập nhật danh mục</h3>
            </div>
            <form id="sua-danh-muc" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <label for="">Tên danh mục:</label>
                    <input id="sua-ten-danh-muc" type="text" name="ten_danh_muc" class="form-control" minlength="6" maxlength="255" required>
                    <label for="">Ảnh danh mục:</label>
                    <br>
                    <img id="sua-anh-danh-muc" src="" alt="Ảnh danh mục">
                    <input type="file" name="anh_danh_muc" accept=".jpg,.jpeg,.png" onchange="checkSizeImageUpload(this, 'message-sua-anh-danh-muc');hienAnhUpload(this, 'sua-anh-danh-muc')">
                    <p class="text-danger" id="message-sua-anh-danh-muc"></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <button type="button" class="btn btn-warning" id="reset-sua-danh-muc">Đặt lại</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>      
                </div>
            </form>
        </div>
        </div>
    </div>

    <div>
        <div>
            <i class="fas fa-sort"></i>
            <select name="sap_xep" id="" onchange="dieuHuong('{{ route('admin.quan-ly-danh-muc.index') }}{{ $tuKhoa }}{{ $sapXep }}' + this.value)">
                @if (request()->sap_xep == 'DESC')
                    <option value="DESC">Z-A</option>
                    <option value="ASC">A-Z</option>
                @else
                    <option value="ASC">A-Z</option>
                    <option value="DESC">Z-A</option>
                @endif
            </select>
        </div>

        <h4>{{ $tuKhoaMessage }}</h4>

        <table class="table">
            <tr>
                <th>STT</th>
                <th>Tên danh mục</th>
                <th>Ảnh danh mục</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            @foreach ($categoriesList as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->category_name }}</td>
                    <td><img src="{{ asset('assets/img/categories') }}/{{ $value->category_image }}" alt="" width="40%"></td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#suaDanhMuc" onclick="loadSuaDanhMuc('{{ $value->category_name }}', '{{ asset('assets/img/categories') }}/{{ $value->category_image }}', '{{ route('admin.quan-ly-danh-muc.sua-danh-muc', ['id' => $value->category_id]) }}')">
                            <i class="fas fa-pen"></i>
                        </button>
                    </td>
                    <td><button class="btn btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa danh mục này? Việc xóa danh mục sẽ khiến nhiều dữ liệu liên quan đến danh mục bị ảnh hưởng!')) dieuHuong('{{ route('admin.quan-ly-danh-muc.xoa-danh-muc', ['id' => $value->category_id]) }}')"><i class="fas fa-trash"></i></button></td>
                </tr>
            @endforeach
        </table>
    </div>
    
@endsection