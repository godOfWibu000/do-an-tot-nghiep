@extends('Layouts.Partner.partner_layout')

@section('css')
    <style>
        .image-library{
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            border: 1px solid #bdc3c7;
            padding: 10px 2%;
            overflow: auto;
            height: 150px;
        }
        .image-library .image{
            width: 20%;
            position: relative;
        }
        .image-library .image .xoa-anh{
            position: absolute;
            top: 30%;
            right: 30%;
            opacity: 0;
            transition: 0.5s;
        }
        .image-library .image:hover .xoa-anh{
             opacity: 1;
        }

        .rates-list{
            margin: auto;
            margin-bottom: 20px;
        }
        .rates-list .rate{
            border: 1px solid grey;
            border-radius: 10px;
            padding: 10px
        }
        .rates-list .rate .user{
            display: flex;
        }
        .rates-list .rate .user .avatar i{
            background-color: #0984e3;
            color: white;
            padding: 10px;
            border-radius: 50%;
        }
        .color-main-2{
            color: #EE5A24;
        }
    </style>
@endsection

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
    {{-- Thong tin chi tiet cho o --}}
    <div>
        <div>
            <div style="display: flex;justify-content: space-between;">
                <h3><b>{{ $hotel->hotel_name }}</b></h3>
                <div>
                    <a href="{{ route('partner.quan-ly-cho-o.cap-nhat-cho-o', ['id' => $hotel->hotel_id ]) }}">
                        <button class="btn btn-primary">
                            <i class="fas fa-pen"></i>
                        </button>
                    </a>
                    <a href="">
                        <button class="btn btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa chỗ ở này?')) dieuHuong('{{ route('partner.quan-ly-cho-o.xoa-cho-o', ['id' => $hotel->hotel_id]) }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </a>
                </div>
            </div>
            <p><b>Loại hình cho thuê:</b> {{ $hotel->category_name }}</p>
            <p><b>Khu vực:</b> {{ $hotel->child_area_name }} - {{ $hotel->hotel_area }}</p>
            <div>
                <span><b>Hạng:</b> {{ $hotel->hotel_star }}<i class="fas fa-star text-warning"></i></span>&nbsp;&nbsp;&nbsp;
                <span>
                    <b>Đánh giá:</b> <span class="text-primary">{{ $hotel->hotel_rate_point }}</span>/10
                    <span>-{{ $hotel->hotel_number_rate }} đánh giá</span>
                </span>
            </div>
            <div>
                <b>Địa chỉ:</b> <span>{{ $hotel->hotel_address }}</span>
            </div>
            <div>
                Mô tả: {{ $hotel->hotel_description }}
            </div>
            <h4>Giá thuê tối thiểu: <span class="text-primary">{{ number_format($hotel->hotel_new_price, 0, ',', '.') }} VND</span></h4>
        </div>
        <img src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $hotel->hotel_thumbnail }}" alt="" width="200px">
        <h4>Ảnh:</h4>
        <div>
            <div class="image-library">
                @foreach ($images as $item)
                    <div class="image">
                        <img src="{{ asset('assets/img/hotels/images') }}/{{ $item->images_hotel_file_name }}" alt="" width="100%">
                        <button class="btn btn-danger xoa-anh" onclick="if(confirm('Bạn có chắc chắn muốn xóa ảnh này?')) dieuHuong('{{ route('partner.quan-ly-cho-o.xoa-anh-cho-o', ['id' => $item->images_hotel_id]) }}')">Xóa ảnh</button>
                    </div>
                @endforeach
            </div><br>
            <form method="POST" action="{{ route('partner.quan-ly-cho-o.them-anh-cho-o', ['id' => $hotel->hotel_id]) }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="image" accept=".jpg, .jpeg, .png" required onchange="checkSizeImageUpload(this, 'message-them-anh');hienAnhUpload(this, 'them-anh')">
                @error('image')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <p class="text-danger" id="message-them-anh"></p>
                <img src="" alt="" id="them-anh" width="200px"><br><br>
                <button class="btn btn-primary">Thêm ảnh</button>
            </form>
        </div>
    </div>
    <hr>

    {{-- Danh sach phong --}}
    <div>
        <ul class="text-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <div style="display: flex;justify-content: space-between;">
            <h4>Danh sách nhóm phòng:</h4>
            <button class="btn btn-success" data-toggle="modal" data-target="#themPhong">
                <i class="fas fa-plus"></i> Thêm
            </button>
        </div>
        <table class="table">
            <tr>
                <th>Tên nhóm phòng</th>
                <th>Giá nhóm phòng</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            @foreach ($rooms as $item)
                <tr>
                    <td>{{ $item->rooms_hotel_name }}</td>
                    <td>{{ number_format($item->room_hotel_price, 0, ',', '.') }} VND</td>
                    <td>
                        <a href="{{ route('partner.quan-ly-cho-o.chi-tiet-nhom-phong', ['id' => $item->rooms_hotels_id]) }}">
                            <button class="btn btn-primary">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#suaPhong" onclick="loadSuaNhomPhong('{{ $item->rooms_hotel_name }}', '{{ $item->room_hotel_price }}', '{{ $item->room_hotel_description }}', '{{ route('partner.quan-ly-cho-o.cap-nhat-nhom-phong', ['id' => $item->rooms_hotels_id ]) }}')">
                            <i class="fas fa-pen"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa phòng này?')) dieuHuong('{{ route('partner.quan-ly-cho-o.xoa-nhom-phong', ['id' => $item->rooms_hotels_id]) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </table>

        <!-- Them phong -->
        <div class="modal fade" id="themPhong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">Thêm phòng</h3>
                    </div>
                    <form method="POST" action="{{ route('partner.quan-ly-cho-o.them-nhom-phong', ['id' => $hotel->hotel_id]) }}">
                        @csrf
                        <div class="modal-body">
                            <label for="">Tên phòng:</label>
                            <input type="text" class="form-control" name="rooms_hotel_name" required minlength="6" maxlength="255">
                            <label for="">Giá phòng:</label>
                            <input type="number" class="form-control" required ="100000" max="100000000" name="room_hotel_price">
                            <label for="">Mô tả:</label>
                            <textarea class="form-control" name="room_hotel_description" id="" cols="30" rows="10" required minlength="6" maxlength="1000"></textarea>
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

        <!-- Sua phong -->
        <div class="modal fade" id="suaPhong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Cập nhật phòng</h3>
                </div>
                <form id="sua-phong" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <label for="">Tên phòng:</label>
                        <input type="text" class="form-control" id="ten-phong" name="rooms_hotel_name" required minlength="6" maxlength="255">
                        <label for="">Giá phòng:</label>
                        <input type="number" class="form-control" id="gia-phong" required ="100000" max="100000000" name="room_hotel_price">
                        <label for="">Mô tả:</label>
                        <textarea class="form-control" id="mo-ta" name="room_hotel_description" id="" cols="30" rows="10" required minlength="6" maxlength="1000"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu lại</button>
                        <button type="button" class="btn btn-warning" id="reset-sua-danh-muc" onclick=" loadSuaNhomPhong(suaTenPhong, suaGiaPhong, suaMoTaPhong, actionSuaPhong)">Đặt lại</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>      
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <hr>

    {{-- Danh gia --}}
    <div>
        <div style="display: flex;justify-content: space-between;">
            <h4>Đánh giá của khách hàng:</h4>
            <select class="form-select text-center width-fit-content" name="" id="sap-xep-danh-gia" onchange="locDanhGia(1, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">
                <option value="danh_gia_tot_nhat">Đánh giá tốt nhất</option>
                <option value="moi_hon">Mới hơn</option>
                <option value="cu_hon">Cũ hơn</option>
            </select>
        </div>

        <div id="all-rates-list">
            {{-- List --}}
                <div class="rates-list">
                    @foreach ($rates as $item)
                        <div class="p-2">
                            <div class="p-1 rate">
                                <div class="user p-2">
                                    <div class="avatar back-color-main-1">
                                        <i class="fas fa-user color-white fs-4 p-2"></i>
                                    </div>
                                    <h5>{{ $item->name }}</h5>
                                </div>
                                <h6 class="color-main-1">Điểm đánh giá: {{ $item->rate_point }}/10</h6>
                                <h6>{{ $item->rate_comment }}</h6>
                                <h6 class="color-main-2"><i class="fas fa-clock"></i>&nbsp;Vào {{ date_format(date_create($item->created_at), "H:m d-m-Y") }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
    
            {{-- Pagination --}}
                @php
                    $pageNumber = ceil($rates->total()/10);
                    $currentPage = $rates->currentPage();
                @endphp
                
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @if ($pageNumber <= 3)
                            @for ($i = 1; $i <= $pageNumber; $i++)
                                <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                    <a class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">{{ $i }}</a>
                                </li>
                            @endfor
                        @else
                            @if ($currentPage != 1)
                                <li class="page-item cursor">
                                    <a class="page-link" onclick="locDanhGia(1, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">Trang đầu</a>
                                </li>
                                <li class="page-item cursor">
                                    <a class="page-link" onclick="locDanhGia({{ $currentPage - 1 }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">
                                        <i class="fas fa-angle-left"></i>
                                    </a>
                                </li>
                            @endif

                            @if ($currentPage >=3)
                                @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                                    @if ($i <= $pageNumber)
                                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                            <a class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')",>{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i <= 3; $i++)
                                    <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                        <a class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">{{ $i }}</a>
                                    </li>
                                @endfor
                            @endif
                            
                            @if ($currentPage != $pageNumber)
                                <li class="page-item cursor">
                                    <a class="page-link" onclick="locDanhGia({{ $currentPage + 1 }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </li>
                                <li class="page-item cursor">
                                    <a class="page-link" onclick="locDanhGia({{ $pageNumber }}, '{{ route('loc-danh-gia', ['id' =>  $hotel->hotel_id ]) }}')">Trang cuối</a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </nav>
        </div>
    </div>
@endsection