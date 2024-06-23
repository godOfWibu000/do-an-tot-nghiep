<?php

    use App\Models\Categories;
    use App\Models\ChildArea;

    function getCategories(){
        $categoriesList = new Categories();

        return $categoriesList->getCats();
    }

    function getChildAreas($area){
        $childAreasList = new ChildArea();

        return $childAreasList->getChildAreas($area, null, null);
    }

    function checkPagination($currentPage, $i){
        return $currentPage == $i ? 'active' : false;
    }

    function getFilterBookingsStatus(){
        return ['Chờ xác nhận', 'Đang xử lý', 'Đã xử lý', 'Đã hoàn thành', 'Hết phòng', 'Đã hủy'];
    }

    function getBookingsStatusForManager(){
        return ['Đã xử lý', 'Đã hoàn thành', 'Hết phòng', 'Đã hủy'];
    }

    function checkBookingStatus($status){
        if($status == 'Chờ xác nhận')
            echo '<button class="button back-color-main-2 color-white border-radius-10 m-1">Chờ xác nhận</button>';
        else if($status == 'Đang xử lý')
            echo '<button class="button bg-warning color-white border-radius-10 m-1">Đang xử lý</button>';
        else if($status == 'Đã xử lý')
            echo '<button class="button back-color-main-1 color-white border-radius-10 m-1">Đã xử lý</button>';
        else if($status == 'Đã hoàn thành')
            echo '<button class="button bg-success color-white border-radius-10 m-1">Đã hoàn thành</button>';
        else
            echo '<button class="button bg-danger color-white border-radius-10 m-1">Đã hủy</button>';
    }

    function pagination($list){

    }

    function getTopHotelsForArea(){
        return ['Sapa', 'Hà Giang', 'Điện Biên',
        'Hạ Long', 'Hải Phòng', 'Nghệ An', 'Huế', 'Đà Nẵng',
        'Nha Trang', 'Đà Lạt', 'Vũng Tàu', 'Hồ Chí Minh', 'Cần Thơ', 'Phú Quốc'];
    }

    function uploadImage($req, $path){
        $file = $req;
        $ext = $req->extension();
        $fileName = md5(rand(0,1000)).'.'.$ext;
        $file->move(public_path($path), $fileName);

        return $fileName;
    }