<div class="search p-3 border-radius-10 box-shadow-10-black">
    <h3 class="text-center">Tìm kiếm chỗ ở du lịch</h3>
    <hr>
    <form action="{{ route('cho-o.tim-kiem-cho-o') }}" method="GET">
        <div class="flex flex-wrap">
            <div class="tim-khu-vuc width-100-percent">
                <h5>Địa điểm:</h5>
                <div class="position-relative">
                    <i class="fas fa-map-marker-alt fs-30 position-absolute"></i>
                    <input type="text" name="dia_diem" class="input width-100-percent" id="input-search-provinces" placeholder="Tìm địa điểm bạn muốn đến..." list="provinces-list" data-setvalue='0' oninput="searchProvinces(this)" value="{{ !empty(request()->dia_diem) ? request()->dia_diem : false }}" required>
                </div>
    
                <div class="width-100-percent hidden position-absolute" id="provinces-search">
                    <table class="width-100-percent table back-color-white position-relative" id="provinces-table">
                        
                    </table>
                </div>
            </div>
        </div>

        <br>
        <button class="button button-radius-10 back-color-main-2 color-white width-100-percent hover-opacity-08-05"><i class="fas fa-search"></i> Tìm kiếm</button>
    </form>
</div>