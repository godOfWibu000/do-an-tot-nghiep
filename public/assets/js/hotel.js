// 
function openImageHotel(image) {
    document.getElementById('image-hotel-main').src = image.src;
}

//Booking
let rooms = [];
function loadRoomsHotel(id){
    $.get('http://localhost/de_tai_do_an_tot_nghiep/public/api/rooms-hotel/room-hotels-for-hotel/' + id, function(res){
        rooms = res;
        let roomsHotelInfor = "";
        let roomsList = `
            <tr>
                <th>Loại phòng</th>
                <th>Giá 1 đêm</th>
                <th>Mô tả</th>
                <th></th>
            </tr>
        `;
        for (let key in rooms) {
            roomsHotelInfor += `
                <span>
                    <i class="fas fa-angle-right color-main-1"></i> ${rooms[key].rooms_hotel_name} | 
                        <span>
                            <span class="color-main-1">${convertPrice().format(rooms[key].room_hotel_price)}</span>
                        </span>
                    <p>&nbsp;&nbsp;&nbsp;${rooms[key].room_hotel_description}</p>
                </span>
            `;
            roomsList += `
                <tr>
                    <td>${rooms[key].rooms_hotel_name}</td>
                    <td>${convertPrice().format(rooms[key].room_hotel_price)}</td>
                    <td>${rooms[key].room_hotel_description}</td>
                    <td>
                        <button class="button hover-opacity-08-05 back-color-main-1 color-white" id="button-add-room-to-book-${rooms[key].rooms_hotels_id}" onclick="addRoomsToBook(this, ${key})"><i class="fas fa-plus"></i></button>
                    </td>
                </tr>
            `;
        }
        let roomsHotelsInforElement = document.getElementById('rooms-hotels-infor');
        if(roomsHotelsInforElement != null)
            roomsHotelsInforElement.innerHTML = roomsHotelInfor;
        document.getElementById('rooms-list').innerHTML = roomsList;  
    })  
}
function tinhTongTien(){
    let tongTien = 0;
    let dataTongTien = document.getElementById('all-price');
    let dsGia = document.querySelectorAll('.price-hotel');
    let dsSoPhong = document.querySelectorAll('.so-luong-phong');
    let ngayCheckIn = new Date(document.getElementById('timeCheckIn').value);
    let ngayCheckOut = new Date(document.getElementById('timeCheckOut').value);
    let soDem = (ngayCheckOut-ngayCheckIn)/(1000*3600*24);
    document.getElementById('so-dem').textContent = soDem;
    for (let i = 0; i < dsGia.length; i++) {
        tongTien += parseInt(dsGia[i].dataset.price) * parseInt(dsSoPhong[i].textContent) * soDem;
    }
    dataTongTien.dataset.allPrice = tongTien;
    dataTongTien.textContent = convertPrice().format(tongTien);
}
function congSoLuong(idSoLuong, idSoLuongConLai){
    let soLuong = parseInt(document.getElementById(idSoLuong).textContent);
    let soLuongConLai = parseInt(document.getElementById(idSoLuongConLai).innerHTML);
    soLuong += soLuong < soLuongConLai ? 1 : 0;
    document.getElementById(idSoLuong).textContent = soLuong;
    tinhTongTien();
}
function truSoLuong(idSoLuong){
    let soLuong = parseInt(document.getElementById(idSoLuong).textContent);
    soLuong -= soLuong > 1 ? 1 : 0;
    document.getElementById(idSoLuong).textContent = soLuong;
    tinhTongTien();
}

function kTraSoPhongConLai(id){
    let timeCheckIn = document.getElementById('timeCheckIn').value;
    let timeCheckOut = document.getElementById('timeCheckOut').value;
    let _token = document.getElementById('_token').value;

    $.ajax({
        url: 'http://localhost/de_tai_do_an_tot_nghiep/public/dat-phong/kiem-tra-so-luong-phong/' + id,
        method: "POST",
        data:{_token:_token, timeCheckIn:timeCheckIn, timeCheckOut:timeCheckOut},
        success:function(data){
            if(data == '0'){
                document.getElementById('room-number-id-' + id).innerHTML = `<span class="text-warning">Hết phòng</span>`;
                document.getElementById('so-luong-phong-' + id).innerHTML = '0';
                tinhTongTien();
            }else
                document.getElementById('room-number-id-' + id).innerHTML = `Còn <span class="text-primary" id="room-number-${id}">${data}</span> phòng`;
        }
    });
}
function kTraTatCaSoPhongConLai(){
    document.querySelectorAll('.room-id').forEach(element => {
        kTraSoPhongConLai(element.dataset.roomId);
    });
}
function addRoomsToBook(button, key){
    let node = document.createDocumentFragment();
    let childNode = document.createElement('tr');
    
    let roomsNumberNode = document.createElement('h6');
    roomsNumberNode.id = `room-number-id-${rooms[key].rooms_hotels_id}`;

    childNode.id = `room-of-book-${rooms[key].rooms_hotels_id}`;
    childNode.innerHTML = `
        <td class="room-id" data-room-id="${rooms[key].rooms_hotels_id}">${rooms[key].rooms_hotel_name}</td>
        <td class="price-hotel" data-price="${rooms[key].room_hotel_price}">${convertPrice().format(rooms[key].room_hotel_price)}</td>
        <td>
            <button type="button" class="button fs-5 hover-opacity-08-05" onclick="truSoLuong('so-luong-phong-${rooms[key].rooms_hotels_id}', '.price-hotel')">-</button>
            <span class="so-luong-phong" id="so-luong-phong-${rooms[key].rooms_hotels_id}">1</span>
            <button type="button" class="button fs-5 hover-opacity-08-05" onclick="congSoLuong('so-luong-phong-${rooms[key].rooms_hotels_id}', 'room-number-${rooms[key].rooms_hotels_id}')">+</button>
        </td>
        <td><button type="button" class="button color-white hover-opacity-08-05 bg-warning" onclick="removeRoomsToBook('${rooms[key].rooms_hotels_id}')"><i class="fas fa-minus"></i></button></td>
    `;
    // roomsNumberNode.innerHTML = `
    //     Còn<span class="text-primary">4</span>phòng
    // `;
    node.appendChild(childNode);
    node.appendChild(roomsNumberNode);
    document.getElementById('books-list').appendChild(node);
    kTraTatCaSoPhongConLai();
    
    button.disabled = true;
    button.classList.remove('back-color-main-1', 'color-white');
    tinhTongTien();
}
function removeRoomsToBook(id){
    let button = document.getElementById(`button-add-room-to-book-${id}`);
    document.getElementById(`room-of-book-${id}`).remove();
    document.getElementById(`room-number-id-${id}`).remove();
    button.disabled = false;
    button.classList.add('back-color-main-1', 'color-white');
    tinhTongTien();
}

function datPhong(action, hotelID){
    let _token = document.getElementById('_token').value;
    let room = document.querySelectorAll('.room-id');
    let rooms = [];
    let roomNumber = document.querySelectorAll('.so-luong-phong');
    let roomNumbers = [];
    let dayNumber = document.getElementById('so-dem').innerHTML;
    let timeCheckIn = document.getElementById('timeCheckIn').value;
    let timeCheckOut = document.getElementById('timeCheckOut').value;
    let timeBooking = getThoiGian();
    let check = 1;
    if(_token == '' || timeCheckIn == '' || dayNumber == 0 || room.length == 0){
        alert('Vui lòng nhập đầy đủ dữ liệu!');
        return;
    }
    for (let i = 0; i < room.length; i++) {
        if(roomNumber[i].textContent <= 0)
            check = 0;
        rooms[i] = room[i].dataset.roomId;
        roomNumbers[i] = roomNumber[i].textContent;
    }

    if(check == 0)
        alert('Dữ liệu không hợp lệ!');
    else{
        $.ajax({
            url: action,
            method: "POST",
            data:{_token:_token, hotelID:hotelID, rooms:rooms, roomNumbers, dayNumber, timeCheckIn:timeCheckIn, timeCheckOut:timeCheckOut, timeBooking:timeBooking},
            success:function(data){
                if(data == 'ok')
                    window.location = '../../dat-phong/dat-phong-cua-toi';
                else
                    alert(data);
            }
        });
    }
}
function capNhatDatPhong(action){
    let _token = document.getElementById('_token').value;
    let room = document.querySelectorAll('.room-id');
    let rooms = [];
    let roomNumber = document.querySelectorAll('.so-luong-phong');
    let roomNumbers = [];
    let timeCheckIn = document.getElementById('timeCheckIn').value;
    let timeCheckOut = document.getElementById('timeCheckOut').value;

    let check = 1;
    if(_token == '' || timeCheckIn == ''){
        check = 0;
    }
    for (let i = 0; i < room.length; i++) {
        if(room[i].dataset.roomId == 0 || roomNumber[i].textContent <= 0)
            check = 0;
        rooms[i] = room[i].dataset.roomId;
        roomNumbers[i] = roomNumber[i].textContent;
    }

    if(check == 0)
        alert('Vui lòng nhập đầy đủ và chính xác dữ liệu!');
    else{
        $.ajax({
            url: action,
            method: "POST",
            data:{_token:_token, rooms:rooms, roomNumbers, timeCheckIn:timeCheckIn, timeCheckOut:timeCheckOut},
            success:function(data){
                let success = document.getElementById('message')
                // console.log(data);
                if(data == 'Fail')
                    success.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            Cập nhật không thành công!
                        </div>
                    `;
                else
                    success.innerHTML = `
                        <div class="alert alert-success" role="alert">
                            Cập nhật thành công!
                        </div>
                    `;
                window.scrollTo(0, 0);
                
            }
        });
    }
}
function xoaDatPhong(location){
    if(confirm('Bạn có chắc chắn muốn xóa đặt phòng này?'))
        window.location = location;
}
function huyDatPhong(location){
    if(confirm('Bạn có chắc chắn muốn hủy đặt phòng?'))
        window.location = location;
}


// Rate
function rateStar(numberStar){
    document.getElementById('rate-number-star-input').value = document.getElementById('rate-number-star').textContent = numberStar;
    let stars = document.querySelectorAll('.rate-star');
    for (let i = 0; i < numberStar; i++) {
        stars[i].classList.remove('far', 'far');
        stars[i].classList.add('fas');
    }
    for (let i = numberStar; i < stars.length; i++) {
        stars[i].classList.remove('fas', 'far');
        stars[i].classList.add('far');
    }
}