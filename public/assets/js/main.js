function dieuHuong(href){
    window.location = href;
}

// Header
window.addEventListener('scroll', function(){
    var nav = document.querySelector(".nav");
    nav.classList.toggle("sticky", window.scrollY > 0);
})

function toggleMenu() {
    const menu = document.getElementById('menu');
    const iconToggle = document.getElementById('icon-toggle');

    const isMenuOpen = menu.style.transform === "translateX(0%)";
    menu.style.transform = isMenuOpen ? "translateX(100%)" : "translateX(0%)";
    iconToggle.innerHTML = isMenuOpen ? 'menu' : 'close';
}

// slick
$('.filtering').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    dots: true
});
var filtered = false;
$('.js-filter').on('click', function(){
if (filtered === false) {
    $('.filtering').slick('slickFilter',':even');
    $(this).text('Unfilter Slides');
    filtered = true;
} else {
    $('.filtering').slick('slickUnfilter');
    $(this).text('Filter Slides');
    filtered = false;
}
});

$('.responsive').slick({
    dots: true,
    infinite: true,
    speed: 300,
    arrows: true,
    slidesToShow: 4,
    slidesToScroll: 4,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });

function setTranslateAnimationLeft(idElement){
    let element = document.getElementById(idElement);
    if(element.style.transform === "translateX(0%)")
        element.style.transform = 'translateX(-120%)';
    else
        element.style.transform = 'translateX(0%)';
}

function setTranslateAnimationRight(idElement){
    let element = document.getElementById(idElement);
    if(element.style.transform === "translateX(0%)")
        element.style.transform = 'translateX(120%)';
    else
        element.style.transform = 'translateX(0%)';
}

function moCuaSo(idCuaSo, idNenCuaSo){
    document.getElementById(idCuaSo).style.display = document.getElementById(idNenCuaSo).style.display = 'block';
}
function dongCuaSo(idCuaSo, idNenCuaSo){
    document.getElementById(idCuaSo).style.display = document.getElementById(idNenCuaSo).style.display = 'none';
}
function moCuaSoTuyChon(idCuaSo){
    let cuaSo = document.getElementById(idCuaSo);
    if(cuaSo.style.height == '230px'){
        cuaSo.style.height = '0';
    }else{
        cuaSo.style.height = '230px';
    }
}
function anHienThanhPhan(idThanhPhanHien, classTatCaThanhPhan, idMenuHien, classTatCaMenu){
    let thanhPhanHien = document.getElementById(idThanhPhanHien);
    let tatCaThanhPhan = document.getElementsByClassName(classTatCaThanhPhan);
    let menuHien = document.getElementById(idMenuHien);
    let tatCaMenu = document.getElementsByClassName(classTatCaMenu);

    thanhPhanHien.classList.remove('hidden');
    menuHien.classList.add('color-main-1', 'border-bottom-1');
    for(let i=0; i<tatCaThanhPhan.length; i++){
        if(tatCaThanhPhan[i].id != idThanhPhanHien){
            tatCaThanhPhan[i].classList.remove('hidden');
            tatCaThanhPhan[i].classList.add('hidden');
        }
        if(tatCaMenu[i].id != idMenuHien){
            tatCaMenu[i].classList.remove('color-main-1', 'border-bottom-1');
        }
    }
}
function validateMatKhau(idInput, idMessage){
    let input = document.getElementById(idInput);
    let message = document.getElementById(idMessage);
    if(!input.value.trim().match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,}$/)){
        message.innerHTML = 'Mật khẩu cần chứa cả chữ cái in hoa, in thường, số và ký tự đặc biệt!';
        return false;
    }else{
        message.innerHTML = '';
        return true;
    }
}
function validateNhapLaiMatKhau(idInput1, idInput2, idMessage) {
    const input1 = document.getElementById(idInput1).value;
    const input2 = document.getElementById(idInput2).value;
    const message = document.getElementById(idMessage);
    
    const arePasswordsMatching = input1 === input2;
    message.innerHTML = arePasswordsMatching ? '' : 'Vui lòng nhập lại mật khẩu trùng khớp với trường bên trên!';
    
    return arePasswordsMatching;
}
function anHienMatKhau(idMatKhau, icon){
    let password = document.getElementById(idMatKhau);
    const isPasswordVisible = password.type === 'text';
    password.type = isPasswordVisible ? 'password' : 'text';
    
    const removeClass = isPasswordVisible ? ['fas', 'fa-eye-slash', 'icon2', 'cursor'] : ['fas', 'fa-eye', 'icon2', 'cursor'];
    const addClass = isPasswordVisible ? ['fas', 'fa-eye', 'icon2', 'cursor'] : ['fas', 'fa-eye-slash', 'icon2', 'cursor'];

    icon.classList.remove(...removeClass);
    icon.classList.add(...addClass);
}
function dangXuat(href){
    if(confirm('Bạn có chắc chắn muốn đăng xuất?'))
        window.location = href;
}

function convertPrice(){
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    });
}

function getThoiGian(){
    let d = new Date;
    let thoiGian = d.getFullYear();
    let thang = d.getMonth()+1;
    if(thang<10)
        thang = '0' + thang;
    let ngay = d.getDate();
    if(ngay<10)
        ngay = '0' + ngay;
    let gio = d.getHours();
    if(gio<10)
        gio = '0' + gio;
    let phut = d.getMinutes();
    if(phut<10)
        phut = '0' + phut;
    let giay = d.getSeconds();
    if(giay<10)
        giay = '0' + giay;
    thoiGian += ('-' + thang + '-' + ngay + ' ' + gio + ':' + phut + ':' + giay);

    return thoiGian;
}

function getNgayThang(){
    let d = new Date;
    let thoiGian = d.getFullYear();
    let thang = d.getMonth()+1;
    if(thang<10)
        thang = '0' + thang;
    let ngay = d.getDate();
    if(ngay<10)
        ngay = '0' + ngay;
    thoiGian += ('-' + thang + '-' + ngay);

    return thoiGian;
}
function getNgayTiepTheo(){
    let d = new Date;
    let thoiGian = d.getFullYear();
    let thang = d.getMonth()+1;
    if(thang<10)
        thang = '0' + thang;
    let ngay = d.getDate() + 1;
    if(ngay<10)
        ngay = '0' + ngay;
    thoiGian += ('-' + thang + '-' + ngay);

    return thoiGian;
}

function luuChoO(id, action1, action2, _token, icon){
    if(icon.id == 'save-' + id){
        let thoiGian = getThoiGian();
        $.ajax({
            url: action1,
            method: "POST",
            data:{id:id, _token:_token, thoiGian:thoiGian},
            success:function(data){
                if(data === 'Success')
                {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.id = 'unsave-' + id;
                    icon.title = 'Bỏ lưu';
                }
            }
        });
    }
    else if(icon.id == 'unsave-' + id){
        $.ajax({
            url: action2,
            method: "GET",
            data:{},
            success:function(data){
                if(data === 'Success')
                {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.id = 'save-' + id;
                    icon.title = 'Lưu lại';
                }
            }
        });
    }
}

function getChoOHangDau(action, button){
    $.ajax({
        url: action,
        method: "GET",
        data:{},
        success:function(data){
            document.getElementById('ds-cho-nghi-noi-bat').innerHTML = data;
            button.classList.remove('color-main-1');
            button.classList.add('back-color-main-1', 'color-white');
            let areaButton = document.querySelectorAll('.area-button');
            areaButton.forEach(element => {
                if(element != button){
                    element.classList.remove('back-color-main-1', 'color-white', 'color-main-1');
                    element.classList.add('color-main-1');
                }
            });
        }
    });
}

function locDanhGia(page, action){
    let sapXep = document.getElementById('sap-xep-danh-gia').value;

    $.ajax({
        url: action,
        method: "GET",
        data:{ sapXep:sapXep, page:page },
        success:function(data){
            document.getElementById('all-rates-list').innerHTML = data;
        }
    });
}
