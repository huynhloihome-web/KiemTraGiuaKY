<x-laptop-layout>

<div class="container mt-4">

{{-- ===== THÔNG BÁO ===== --}}

@if(session('error'))
<div class="alert alert-danger text-center">
    {{ session('error') }}
</div>
@endif


@if(session('order_success'))
<div class="alert alert-success text-center">

    <i class="fa fa-check-circle fa-2x text-success"></i>

    <h4 class="mt-2 font-weight-bold">
        ĐẶT HÀNG THÀNH CÔNG!
    </h4>

    <p>
        Mã đơn hàng:
        <strong class="text-danger">
            #{{ session('order_success')['order_id'] }}
        </strong>
    </p>

    <p>
        Tổng tiền:
        <strong class="text-danger">
            {{ number_format(session('order_success')['total'],0,',','.') }}đ
        </strong>
    </p>

    <p>
        {{ session('order_success')['mail_status'] }}
    </p>

</div>
@endif



{{-- ===== TIÊU ĐỀ ===== --}}

<h5 class="text-center font-weight-bold text-primary mt-4">
    DANH SÁCH SẢN PHẨM
</h5>



@if(count($products) > 0 && !session('order_success'))

<table class="table table-bordered table-hover mt-3">

<thead class="bg-light">

<tr class="text-center">

<th width="6%">STT</th>

<th>Tên sản phẩm</th>

<th width="12%">Số lượng</th>

<th width="20%">Đơn giá</th>

<th width="10%">Xóa</th>

</tr>

</thead>



<tbody>

@php
$stt = 1;
$tong = 0;
@endphp


@foreach($products as $item)

@php
$thanhtien = $item->price * $item->quantity;
$tong += $thanhtien;
@endphp


<tr id="row-{{ $item->id }}">

<td class="text-center">
{{ $stt++ }}
</td>


<td>
<strong>{{ $item->name }}</strong>
</td>


<td class="text-center">
{{ $item->quantity }}
</td>


<td class="text-right">
{{ number_format($item->price,0,',','.') }}đ
</td>


<td class="text-center">
<button
class="btn btn-danger btn-sm remove-item"
data-id="{{ $item->id }}">
Xóa
</button>
</td>

</tr>

@endforeach

</tbody>



{{-- ===== TỔNG TIỀN ===== --}}

<tfoot>
<tr>
    <td colspan="3" class="text-center font-weight-bold">
        Tổng cộng
    </td>

    <td class="text-right font-weight-bold text-dark">
        {{ number_format($tong,0,',','.') }}đ
    </td>

    <td></td>
</tr>
</tfoot>


</table>



{{-- ===== FORM ĐẶT HÀNG ===== --}}

<div class="text-center mt-3">

<label class="font-weight-bold">
Hình thức thanh toán
</label>

<br>

<form method="POST" action="{{ route('cart.checkout') }}">
@csrf

<select
name="hinh_thuc_thanh_toan"
class="form-control d-inline-block mt-2"
style="width:200px;">

<option value="1">Tiền mặt</option>
<option value="2">Chuyển khoản ngân hàng</option>

</select>

<br>

<button
type="submit"
class="btn btn-primary mt-2 px-4">

ĐẶT HÀNG

</button>

</form>

</div>



@elseif(!session('order_success'))

{{-- ===== GIỎ HÀNG RỖNG ===== --}}

<div class="alert alert-info text-center mt-4">

<i class="fa fa-shopping-cart fa-3x mb-3"></i>

<h4>
Giỏ hàng của bạn đang trống!
</h4>

<a href="{{ url('/') }}" class="btn btn-primary mt-2">
Mua sắm ngay
</a>

</div>

@endif

</div>



{{-- ===== AJAX XÓA ===== --}}

<script>

$(document).ready(function(){

$('.remove-item').click(function(){

var id = $(this).data('id');
var row = $('#row-'+id);

if(confirm('Bạn có muốn xóa sản phẩm này?')){

$.ajax({

type:"POST",
url:"{{ route('cart.remove') }}",

data:{
"_token":"{{ csrf_token() }}",
"id":id
},

success:function(response){

row.remove();

$('#cart-number-product')
.html(response.total_items);

location.reload();

},

error:function(){
alert('Có lỗi xảy ra!');
}

});

}

});

});

</script>

</x-laptop-layout>
