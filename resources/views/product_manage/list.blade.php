<x-laptop-layout>
    <x-slot name="title">
        Quản lý sản phẩm
    </x-slot>

    <div style='text-align:center; color:#15c; font-weight:bold; font-size:20px; margin:10px 0;'>
        QUẢN LÝ SẢN PHẨM
    </div>

    <table id="product-table" class="table table-striped table-bordered" width="100%">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>CPU</th>
                <th>RAM</th>
                <th>Ổ cứng</th>
                <th>Khối lượng</th>
                <th>Nhu cầu</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th width="120px">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{$row->tieu_de}}</td>
                <td>{{$row->cpu}}</td>
                <td>{{$row->ram}}</td>
                <td>{{$row->luu_tru}}</td>
                <td>{{$row->khoi_luong}}</td>
                <td>{{$row->nhu_cau}}</td>
                <td>{{number_format($row->gia,0,',','.')}}</td>
                <td>
                    <img src="{{asset('storage/image/'.$row->hinh_anh)}}" width="40px">
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{route('product.detail',['id'=>$row->id])}}" class="btn btn-sm btn-primary">Xem</a>
                        &nbsp;
                        <form method="post" action="{{route('productdelete')}}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
                            @csrf
                            <input type="hidden" name="id" value="{{$row->id}}">
                            <input type="submit" class="btn btn-sm btn-danger" value="Xóa">
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            $('#product-table').DataTable({
                pageLength: 10,
                language: {
                    search: "Search:",
                    lengthMenu: "_MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    zeroRecords: "No matching records found",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        });
    </script>
</x-laptop-layout>