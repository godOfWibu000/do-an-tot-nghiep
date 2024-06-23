@extends('Layouts.Partner.partner_layout')

@section('redirect')
    <li><a href="{{ route('partner.thong-ke.index') }}">Thống kê đặt phòng</a></li>
    <li class="active">{{ $title }}</li>
@endsection

@section('content')
    <h4>Lọc theo thời gian:
        <a href="{{ route('partner.thong-ke.chi-tiet-thong-ke-dat-phong', ['id' => request()->id]) }}">
            <button class="btn btn-primary"><i class="fas fa-undo"></i></button>
        </a>
    </h4>
    <div class="flex-between">
        <form action="" method="GET">
            <span class="flex">
                <h5>Từ:&nbsp;</h5>
                <input type="date" name="thong_ke_tu" class="form-control" id="ngay-bat-dau">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <h5>Đến:&nbsp;</h5>
                <input type="date" name="thong_ke_den" class="form-control" id="ngay-ket-thuc">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-primary">Lọc</button>
            </span>
        </form>
        <span class="flex">
            <select name="" id="" class="form-control" onchange="dieuHuong('{{ route('partner.thong-ke.chi-tiet-thong-ke-dat-phong', ['id' => request()->id]) }}?thang=' + this.value)">
                <option value="7n">7 ngày qua</option>
                @for ($i = 1; $i <= date('m'); $i++)
                    @if ($i == request()->thang)
                        <option value="{{ sprintf("%02s", $i); }}" selected>Tháng {{ $i }}</option>
                    @else
                        <option value="{{ sprintf("%02s", $i); }}">Tháng {{ $i }}</option>
                    @endif
                @endfor
            </select>
        </span>
    </div>
    <br>
    <div>
        <i class="fas fa-sort"></i>
        <select name="" id="" onchange="dieuHuong(location.href.toString().split('&sap_xep=')[0] + '&sap_xep=' + this.value)">
            @if (request()->sap_xep == 'ASC')
                <option value="ASC">Cũ hơn</option>
                <option value="DESC">Mới hơn</option>
            @else
                <option value="DESC">Mới hơn</option>
                <option value="ASC">Cũ hơn</option>
            @endif
        </select>
    </div>
    <hr>

    <canvas id="chartRevenues" style="width:100%;max-width:600px"></canvas>
    <canvas id="chartTotalBookings" style="width:100%;max-width:600px"></canvas>

    <hr>
    <a href="{{ route('partner.thong-ke.export-xlsx', ['id' => request()->id]) }}?thong_ke_tu={{ request()->thong_ke_tu }}&thong_ke_den={{ request()->thong_ke_den }}&thang={{ request()->thang }}&sap_xep={{ request()->sap_xep }}">
        <button class="btn btn-success">
            <i class="fas fa-file-excel"></i> In ra file Excel
        </button>
    </a>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
        // let xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
        // let yValues = [55, 49, 44, 24, 15];
        // const barColors = ["red", "green","blue","orange","brown"];

        let xValuesRevenue = [];
        let yValuesRevenue = [];
        let xValuesTotalBookings = [];
        let yValuesTotalBookings = [];
        @foreach ($statistical as $key => $value)
            xValuesRevenue[{{ $key }}] = xValuesTotalBookings[{{ $key }}] = '{{ $value->statistical_bookings_date }}';
            yValuesRevenue[{{ $key }}] = "{{ $value->revenue }}";

            yValuesTotalBookings[{{ $key }}] = '{{ $value->total_number_bookings }}';
        @endforeach

        new Chart("chartRevenues", {
            type: "bar",
            data: {
                labels: xValuesRevenue,
                datasets: [{
                    backgroundColor: '#34495e',
                    data: yValuesRevenue
                }]
            },
            options: {
                legend: {display: false},
                title: {
                    display: true,
                    text: "Doanh thu(VND)"
                }
            }
        });

        new Chart("chartTotalBookings", {
            type: "bar",
            data: {
                labels: xValuesTotalBookings,
                datasets: [{
                    backgroundColor: '#2980b9',
                    data: yValuesTotalBookings
                }]
            },
            options: {
                legend: {display: false},
                title: {
                    display: true,
                    text: "Số lượng đặt phòng"
                }
            }
        });

        @if(empty(request()->thong_ke_tu))
            document.getElementById('ngay-bat-dau').value = getNgayThang();
        @else
            document.getElementById('ngay-bat-dau').value = '{{ request()->thong_ke_tu }}';
        @endif
        @if(empty(request()->thong_ke_den))
            document.getElementById('ngay-ket-thuc').value = getNgayThang();
        @else
            document.getElementById('ngay-ket-thuc').value = '{{ request()->thong_ke_den }}';
        @endif
    </script>
@endsection