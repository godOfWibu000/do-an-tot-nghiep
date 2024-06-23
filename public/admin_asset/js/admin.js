function checkSizeImageUpload(file, message){
    if(file.files[0].size > 2097152) {
        document.getElementById(message).textContent = "File ảnh vượt quá kích thước! Kích thước tối đa cho phép là 2MB";
        file.value = "";
    }else{
        document.getElementById(message).textContent = "";
    }
}
function loadSuaDanhMuc(tenDanhMuc, anhDanhMuc, action){
    document.getElementById('sua-danh-muc').action = action;
    document.getElementById('sua-ten-danh-muc').value = tenDanhMuc;
    document.getElementById('sua-anh-danh-muc').src = anhDanhMuc;
    document.getElementById('reset-sua-danh-muc').onclick = function() {loadSuaDanhMuc(tenDanhMuc, anhDanhMuc, action)};
}

let suaTenPhong;
let suaGiaPhong;
let suaMoTaPhong;
let actionSuaPhong;
function loadSuaNhomPhong(tenPhong, giaPhong, moTa, action){
    actionSuaPhong = document.getElementById('sua-phong').action = action;
    suaTenPhong = document.getElementById('ten-phong').value = tenPhong;
    suaGiaPhong = document.getElementById('gia-phong').value = giaPhong;
    suaMoTaPhong = document.getElementById('mo-ta').value = moTa;
}

let soPhongCanSua;
let actionCanSua;
function loadSuaPhong(soPhong, action){
    actionCanSua = document.getElementById('sua-phong').action = action;
    soPhongCanSua = document.getElementById('so-phong').value = soPhong;
}

// Tai len file anh
function layTenFIle(id){
    let tenFile = document.getElementById(id);
    const { files } = event.target;
    console.log("files", files);
    tenFile.textContent = files[0].name;
}

function hienAnhUpload(fileInput, id){
    if(fileInput.files && fileInput.files[0]){
        let reader = new FileReader();

        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(fileInput.files[0]);
    }
}

// Get area
function getArea(area){
    $.get('http://localhost/de_tai_do_an_tot_nghiep/public/api/provinces', function(res){
        let provinces = [];
        let data = JSON.parse(res);
        provinces = data;

        let element = '';
        provinces.map(value => {
            if(value.name == area || value.name == 'Hà Nội'){
                element += `
                    <option value="${value.name}" selected>${value.name}</option>
                `;
            }else
                element += `
                    <option value="${value.name}">${value.name}</option>
                `;
        });
        document.getElementById('hotel-area').innerHTML = element;
    })
}

function getChildAreas(area, childAreaId){
    let childAreas = [];
    $.get('http://localhost/de_tai_do_an_tot_nghiep/public/api/child-area/' + area, function(res){
        let childAreas = res;
        let element = '';
        childAreas.map(value => {
            if(value.child_area_id != childAreaId)
                element += `
                    <option value="${value.child_area_id}">${value.child_area_name}</option>
                `;
            else
                element += `
                    <option value="${value.child_area_id}" selected>${value.child_area_name}</option>
                `;
        });
        document.getElementById('hotel-child-area').innerHTML = element;
    })
}
