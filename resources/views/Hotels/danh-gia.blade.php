{{-- List --}}
<div class="rates-list" id="all-rates-list">
    @foreach ($ratesList as $item)
        <div class="p-2">
            <div class="p-1 rate border border-radius-10">
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
    $pageNumber = ceil($ratesList->total()/1);
    $currentPage = $ratesList->currentPage();
@endphp

<nav aria-label="Page navigation example">
    <ul class="pagination">
        @if ($pageNumber <= 3)
            @for ($i = 1; $i <= $pageNumber; $i++)
                <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                    <a class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $id ]) }}')">{{ $i }}</a>
                </li>
            @endfor
        @else
            @if ($currentPage != 1)
                <li class="page-item cursor">
                    <a class="page-link" onclick="locDanhGia(1, '{{ route('loc-danh-gia', ['id' =>  $id ]) }}')">Trang đầu</a>
                </li>
                <li class="page-item cursor">
                    <a class="page-link" onclick="locDanhGia({{ $currentPage - 1 }}, '{{ route('loc-danh-gia', ['id' =>  $id ]) }}')">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>
            @endif

            @if ($currentPage >=3)
                @for ($i = $currentPage-1; $i <= $currentPage + 1; $i++)
                    @if ($i <= $pageNumber)
                        <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                            <a class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $id ]) }}')",>{{ $i }}</a>
                        </li>
                    @endif
                @endfor
            @else
                @for ($i = 1; $i <= 3; $i++)
                    <li class="page-item cursor {{ checkPagination($currentPage, $i) }}">
                        <a class="page-link" onclick="locDanhGia({{ $i }}, '{{ route('loc-danh-gia', ['id' =>  $id ]) }}')">{{ $i }}</a>
                    </li>
                @endfor
            @endif
            
            @if ($currentPage != $pageNumber)
                <li class="page-item cursor">
                    <a class="page-link" onclick="locDanhGia({{ $currentPage + 1 }}, '{{ route('loc-danh-gia', ['id' =>  $id ]) }}')">
                        <i class="fas fa-angle-right"></i>
                    </a>
                </li>
                <li class="page-item cursor">
                    <a class="page-link" onclick="locDanhGia({{ $pageNumber }}, '{{ route('loc-danh-gia', ['id' =>  $id ]) }}')">Trang cuối</a>
                </li>
            @endif
        @endif
    </ul>
</nav>