@extends('Layouts.Partner.partner_layout')

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

    <form action="{{ route('partner.quan-ly-cho-o.them-cho-o') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="">Tên chỗ ở<span class="text-danger">(*)</span>:</label>
        <input type="text" class="form-control" name="hotel_name" minlength="6" maxlength="255" required>
        <p class="text-danger">
            @error('hotel_name')
                {{ $message }}
            @enderror
        </p>
        <label for="">Địa chỉ chỗ ở<span class="text-danger">(*)</span>:</label>
        <input type="text" class="form-control" name="hotel_address" minlength="6" maxlength="500" required>
        <p class="text-danger">
            @error('hotel_address')
                {{ $message }}
            @enderror
        </p>

        <label for="">Hạng chỗ ở<span class="text-danger">(*)</span>:</label>
        <input type="number" class="form-control" min="1" max="7" name="hotel_star" required>
        <p class="text-danger">
            @error('hotel_star')
                {{ $message }}
            @enderror
        </p>

        <label for="">Danh mục<span class="text-danger">(*)</span>:</label>
        <select name="category_id" class="form-control" id="" required>
            @foreach (getCategories() as $item)
                <option value="{{ $item->category_id }}">{{ $item->category_name }}</option>
            @endforeach
        </select>
        <p class="text-danger">
            @error('category_id')
                {{ $message }}
            @enderror
        </p>

        <label for="">Khu vực<span class="text-danger">(*)</span>:</label>
        <select name="hotel_area" class="form-control" id="hotel-area" onchange="getChildAreas(this.value)" required>

        </select>
        <p class="text-danger">
            @error('hotel_area')
                {{ $message }}
            @enderror
        </p>

        <label for="">Địa điểm<span class="text-danger">(*)</span>:</label>
        <select name="child_area_id" class="form-control" id="hotel-child-area" required>

        </select>
        <p class="text-danger">
            @error('child_area_id')
                {{ $message }}
            @enderror
        </p>

        <label for="">Giá cũ:</label>
        <input type="number" class="form-control" min="100000" max="100000000" name="hotel_old_price">
        <p class="text-danger">
            @error('hotel_old_price')
                {{ $message }}
            @enderror
        </p>
        
        <label for="">Giá mới<span class="text-danger">(*)</span>:</label>
        <input type="number" class="form-control" min="100000" max="100000000" name="hotel_new_price">
        <p class="text-danger">
            @error('hotel_new_price')
                {{ $message }}
            @enderror
        </p>

        <label for="">Mô tả<span class="text-danger">(*)</span>:</label>
        <textarea name="hotel_description" class="form-control" id="area" cols="30" rows="10" minlength="6" maxlength="1000" required> </textarea>
        <p class="text-danger">
            @error('hotel_description')
                {{ $message }}
            @enderror
        </p>

        <label for="">Hình thu nhỏ<span class="text-danger">(*)</span>:</label>
        <br>
        <img src="https://cdn-icons-png.flaticon.com/512/1160/1160358.png" id="hinh-thu-nho" alt="Hình thu nhỏ" class="border" width="200px" height="200px">
        <input type="file" name="image" accept=".jpg, .png" onchange="checkSizeImageUpload(this, 'message-them-anh-cho-o');hienAnhUpload(this, 'hinh-thu-nho')" required>
        <p class="text-danger" id="message-them-anh-cho-o"></p>
        <p class="text-danger">
            @error('image')
                {{ $message }}
            @enderror
        </p>

        <br>
        <button class="btn btn-warning">Đặt lại</button>
        <button class="btn btn-primary">Lưu lại</button>
    </form>
@endsection

@section('js')
    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>
    <script>
        bkLib.onDomLoaded(function() {
             new nicEditor().panelInstance('area');
        });
        window.onload = function (){ getArea('');getChildAreas('Hà Nội') };
    </script>
@endsection