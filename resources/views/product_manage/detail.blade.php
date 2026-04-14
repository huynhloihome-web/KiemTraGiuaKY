<x-laptop-layout>
    <x-slot name="title">
        Chi tiết sản phẩm
    </x-slot>

    <div class="mt-3">
        <div style="text-align:center; color:#15c; font-weight:bold; font-size:20px; margin-bottom:15px;">
            THÔNG TIN CHI TIẾT SẢN PHẨM
        </div>

        <div class="row">
            <div class="col-4 text-center">
                <img src="{{asset('storage/image/'.$data->hinh_anh)}}" width="100%">
            </div>
            <div class="col-8">
                <table class="table table-bordered">
                    <tr>
                        <th width="220px">Tiêu đề</th>
                        <td>{{$data->tieu_de}}</td>
                    </tr>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <td>{{$data->ten}}</td>
                    </tr>
                    <tr>
                        <th>Giá</th>
                        <td>{{number_format($data->gia,0,',','.')}}đ</td>
                    </tr>
                    <tr>
                        <th>CPU</th>
                        <td>{{$data->cpu}}</td>
                    </tr>
                    <tr>
                        <th>RAM</th>
                        <td>{{$data->ram}}</td>
                    </tr>
                    <tr>
                        <th>Lưu trữ</th>
                        <td>{{$data->luu_tru}}</td>
                    </tr>
                    <tr>
                        <th>Màn hình</th>
                        <td>{!! $data->man_hinh !!}</td>
                    </tr>
                    <tr>
                        <th>Khối lượng</th>
                        <td>{{$data->khoi_luong}}</td>
                    </tr>
                    <tr>
                        <th>Nhu cầu</th>
                        <td>{{$data->nhu_cau}}</td>
                    </tr>
                </table>

                <a href="{{route('productlist')}}" class="btn btn-primary">Quay lại</a>
            </div>
        </div>
    </div>
</x-laptop-layout>