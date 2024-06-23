@extends('Layouts.Partner.partner_layout')

@php
    $thang = $sapXep = null;
    if(!empty(request()->thang))
        $thang = '&thang=' . request()->thang;
    if(!empty(request()->sap_xep))
        $sapXep = '&sap_xep=' . request()->sap_xep;
@endphp

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
    <div class="flex-between">
        <div>
            <h4>Tìm ngày thuê phòng:</h4>
            <form class="flex" method="GET" action="">
                <input class="form-control" type="date" name="ngay_thue_phong" value="{{ !empty(request()->ngay_thue_phong) ? request()->ngay_thue_phong : false }}" required>
                &nbsp;
                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                &nbsp;
                <a href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}">
                    <button class="btn" type="button"><i class="fas fa-redo-alt"></i></button>
                </a>
            </form>
        </div>

        <div>
            <h4>Thêm ngày thuê phòng:</h4>
            <form class="flex" method="POST" action="{{ route('partner.quan-ly-cho-o.them-lich-dat-phong', ['id' => request()->id]) }}">
                @csrf
                <input class="form-control" type="date" name="calendar_room_date" required>
                &nbsp;
                <button class="btn btn-primary">Thêm</button>
            </form>
        </div>
    </div>

    <div>
        <h4>Danh sách ngày thuê phòng</h4>
        <div class="flex-between">
            <select name="" id="" onchange="dieuHuong('{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}' + '?page=1{{ $thang }}&sap_xep=' + this.value)">
                @if (request()->sap_xep == 'ASC')
                    <option value="ASC">Cũ hơn</option>
                    <option value="DESC">Mới hơn</option>
                @else
                    <option value="DESC">Mới hơn</option>
                    <option value="ASC">Cũ hơn</option> 
                @endif
            </select>

            <div class="flex">
                <select name="" id="" onchange="dieuHuong('{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}' + '?page=1&thang=' + this.value + '{{ $sapXep }}')">
                    <option value="{{ sprintf("%02s", date('m')) }}">Tháng này</option>
                    @for ($i = 1; $i <= 12; $i++)
                        @if ($i == request()->thang)
                            <option value="{{ sprintf("%02s", $i) }}" selected>Tháng {{ $i }}</option>
                        @else
                            <option value="{{ sprintf("%02s", $i) }}">Tháng {{ $i }}</option>
                        @endif
                    @endfor
                </select>
            </div>
        </div>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày thuê phòng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($calendar as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ date_format(date_create($value->calendar_room_date),"d-m-Y") }}</td>
                        <td>
                            <button class="btn btn-danger" onclick="dieuHuong('{{ route('partner.quan-ly-cho-o.xoa-lich-dat-phong', ['id' => request()->id, 'date' => $value->calendar_room_date]) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @php
            $pageNumber = ceil($calendar->total()/10);
            $currentPage = $calendar->currentPage();
        @endphp

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($pageNumber <= 3)
                    @for ($i = 1; $i <= $pageNumber; $i++)
                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}?page={{ $i }}{{ $thang }}{{ $sapXep }}">{{ $i }}</a>
                        </li>
                    @endfor
                @else
                    @if ($currentPage != 1)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}?page=1{{ $thang }}{{ $sapXep }}">Trang đầu</a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}?page={{ $currentPage-1 }}{{ $thang }}{{ $sapXep }}">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                    @endif

                    @if ($currentPage >=3)
                        @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                            @if ($i <= $pageNumber)
                                <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                    <a class="page-link" href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}?page={{ $i }}{{ $thang }}{{ $sapXep }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                    @else
                        @for ($i = 1; $i <= 3; $i++)
                            <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                                <a class="page-link" href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}?page={{ $i }}{{ $thang }}{{ $sapXep }}">{{ $i }}</a>
                            </li>
                        @endfor
                    @endif
                    
                    @if ($currentPage != $pageNumber)
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}?page={{ $currentPage+1 }}{{ $thang }}{{ $sapXep }}">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item cursor">
                            <a class="page-link" href="{{ route('partner.quan-ly-cho-o.lich-dat-phong', ['id' => request()->id]) }}?page={{ $pageNumber }}{{ $thang }}{{ $sapXep }}">Trang cuối</a>
                        </li>
                    @endif
                @endif
            </ul>
        </nav>
    </div>
@endsection