@extends('Layouts.admin.admin_layout')

@php
    $tuKhoa = null;
    $sapXep = '?sap_xep=';
    $tuKhoaMessage = null;
    if(!empty(request()->tu_khoa)){
        $tuKhoa = '?tu_khoa=' . request()->tu_khoa;
        $sapXep = '&sap_xep=';
        $tuKhoaMessage = "Kết quả tìm kiếm cho '". request()->tu_khoa . "'";
    }
@endphp

@section('redirect')
    <li><a href="{{ route('admin.quan-ly-khu-vuc.index') }}">Quản lý khu vực</a></li>
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

    <div class="flex-between">
        <div class="tim-kiem">
            <form class="form-tim-kiem" action="" method="GET">
                <input type="text" class="form-control" name="tu_khoa" placeholder="Tìm kiếm...">
                <button class="btn btn-primary"><i class="fa fa-search"></i></button>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('admin.quan-ly-khu-vuc.chi-tiet-khu-vuc', ['area' => request()->area ]) }}"><button class="btn" type="button"><i class="fas fa-redo-alt"></i></button></a>
            </form>
        </div>
        <button class="btn btn-success" data-toggle="modal" data-target="#themKhuVuc">
            <i class="fas fa-plus"></i> Thêm
        </button>
    </div>
    <hr>

    <div>
        <div>
            <i class="fas fa-sort"></i>
            <select name="sap_xep" id="" onchange="dieuHuong('{{ route('admin.quan-ly-khu-vuc.chi-tiet-khu-vuc', ['area' => request()->area]) }}{{ $tuKhoa }}{{ $sapXep }}' + this.value)">
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
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Khu vực</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($childAreasList as $key =>$value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->child_area_name }}</td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#suaKhuVuc" onclick="loadSuaKhuVuc('{{ $value->child_area_name }}', '{{ route('admin.quan-ly-khu-vuc.cap-nhat-khu-vuc', ['id' => $value->child_area_id]) }}')">
                                <i class="fas fa-pen"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa khu vực này?')) dieuHuong('{{ route('admin.quan-ly-khu-vuc.xoa-khu-vuc', ['id' => $value->child_area_id ]) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Them khu vuc -->
        <div class="modal fade" id="themKhuVuc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Thêm khu vực</h3>
                </div>
                <form method="POST" action="{{ route('admin.quan-ly-khu-vuc.them-khu-vuc', ['area' => request()->area]) }}">
                    @csrf
                    <div class="modal-body">
                        <label for="">Tên khu vực</label>
                        <input type="text" class="form-control" name="child_area_name">
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

        <!-- Sua khu vuc -->
        <div class="modal fade" id="suaKhuVuc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Cập nhật khu vực</h3>
                </div>
                <form method="POST" action="" id="form-sua-khu-vuc">
                    @csrf
                    <div class="modal-body">
                        <label for="">Tên khu vực</label>
                        <input type="text" class="form-control" name="child_area_name" id="sua-ten-khu-vuc">
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <button type="button" class="btn btn-warning" id="reset-sua-khu-vuc">Đặt lại</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function loadSuaKhuVuc(tenKhuVuc, action){
            document.getElementById('sua-ten-khu-vuc').value = tenKhuVuc;
            document.getElementById('form-sua-khu-vuc').action = action;
            document.getElementById('reset-sua-khu-vuc').onclick = function() {loadSuaKhuVuc(tenKhuVuc, action)};
        }
    </script>
@endsection