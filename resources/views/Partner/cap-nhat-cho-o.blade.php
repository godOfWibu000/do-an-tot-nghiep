@extends('Layouts.Partner.partner_layout')

<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>
<script>
    bkLib.onDomLoaded(function() {
            new nicEditor().panelInstance('area');
    }); 
</script>

@section('onload')
    onload="getArea('{{ $hotel->hotel_area }}');getChildAreas('{{ $hotel->hotel_area }}', '{{ $hotel->child_area_id }}')"
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

    <form onload="getArea('{{ $hotel->hotel_area }}')" action="{{ route('partner.quan-ly-cho-o.cap-nhat-cho-o', ['id' => $id]) }}" method="POST" novalidate enctype="multipart/form-data">
        @csrf
        <label for="">Tên chỗ ở<span class="text-danger">(*)</span>:</label>
        <input type="text" class="form-control" name="hotel_name" minlength="6" maxlength="255" value="{{ $hotel->hotel_name }}" required>
        <p class="text-danger">
            @error('hotel_name')
                {{ $message }}
            @enderror
        </p>
        <label for="">Địa chỉ chỗ ở<span class="text-danger">(*)</span>:</label>
        <input type="text" class="form-control" name="hotel_address" minlength="6" maxlength="500" value="{{ $hotel->hotel_address }}" required>
        <p class="text-danger">
            @error('hotel_address')
                {{ $message }}
            @enderror
        </p>

        <label for="">Hạng chỗ ở<span class="text-danger">(*)</span>:</label>
        <input type="number" class="form-control" min="1" max="7" name="hotel_star" value="{{ $hotel->hotel_star }}" required>
        <p class="text-danger">
            @error('hotel_star')
                {{ $message }}
            @enderror
        </p>

        <label for="">Danh mục<span class="text-danger">(*)</span>:</label>
        <select name="category_id" class="form-control" id="" required>
            @foreach (getCategories() as $item)
                @if ($item->category_id == $hotel->category_id)
                    <option value="{{ $item->category_id }}" selected>{{ $item->category_name }}</option>
                @else
                    <option value="{{ $item->category_id }}">{{ $item->category_name }}</option>
                @endif
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
        <input type="number" class="form-control" min="100000" max="100000000" value="{{ $hotel->hotel_old_price }}" name="hotel_old_price">
        <p class="text-danger">
            @error('hotel_old_price')
                {{ $message }}
            @enderror
        </p>
        
        <label for="">Giá mới<span class="text-danger">(*)</span>:</label>
        <input type="number" class="form-control" min="100000" max="100000000" value="{{ $hotel->hotel_new_price }}" name="hotel_new_price">
        <p class="text-danger">
            @error('hotel_new_price')
                {{ $message }}
            @enderror
        </p>

        <label for="">Mô tả<span class="text-danger">(*)</span>:</label>
        <textarea name="hotel_description" class="form-control" id="area" cols="30" rows="10" minlength="6" maxlength="1000" required>{{ $hotel->hotel_description }}</textarea>
        <p class="text-danger">
            @error('hotel_description')
                {{ $message }}
            @enderror
        </p>

        <label for="">Hình thu nhỏ<span class="text-danger">(*)</span>:</label>
        <br>
        <img src="{{ asset('assets/img/hotels/thumbnail') }}/{{ $hotel->hotel_thumbnail }}" id="hinh-thu-nho" alt="Hình thu nhỏ" class="border" width="200px" height="200px">
        <input type="file" name="image" onchange="checkSizeImageUpload(this, 'message-them-anh-cho-o');hienAnhUpload(this, 'hinh-thu-nho')" required>
        <p class="text-danger" id="message-them-anh-cho-o"></p>
        <p class="text-danger">
            @error('image')
                {{ $message }}
            @enderror
        </p>

        <br>
        <button type="reset" class="btn btn-warning">Đặt lại</button>
        <button class="btn btn-primary">Lưu lại</button>
    </form>
@endsection