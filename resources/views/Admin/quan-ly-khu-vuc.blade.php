@extends('Layouts.admin.admin_layout')

@section('redirect')
    <li><a href="{{ route('admin.quan-ly-khu-vuc.index') }}">Quản lý khu vực</a></li>
    <li class="active">Danh sách khu vực</li>
@endsection

@section('content')
    <div>
        <label for="">Lọc: </label>
        <input type="text" class="form-control" oninput="searchProvinces(this);">
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên tỉnh/ thành</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="area">

        </tbody>
    </table>
@endsection

@section('js')
    <script>
        function renderProvinces(array){
            let element = '';
            let i = 1;
            array.map(value => {
                element += `
                    <tr>
                        <td>${i++}</td>
                        <td>${value.name}</td>
                        <td>
                            <a href="chi-tiet-khu-vuc/${value.name}">
                                <button class="btn btn-primary"><i class="fas fa-info-circle"></i></button>
                            </a>
                        </td>
                    </tr>`;
            });
            document.getElementById('area').innerHTML = element;
        }

        let provinces = [];
        $.get('http://localhost/de_tai_do_an_tot_nghiep/public/api/provinces', function(res){
            let data = JSON.parse(res);
            provinces = data;

            renderProvinces(provinces);
        })

        function searchProvinces(input){
            console.log('ok');
            let inputValue = input.value;
            let provincesSearch = provinces.filter(value => {
                return value.name.toUpperCase().includes(inputValue.toUpperCase());
            });
            renderProvinces(provincesSearch);
        }
    </script>
@endsection