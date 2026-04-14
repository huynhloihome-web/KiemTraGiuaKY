@extends('components.laptop-layout')

@section('title', 'Giỏ hàng')

@section('content')

<div class="container mt-4">

    {{-- THÔNG BÁO LỖI --}}
    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif


    {{-- THÔNG BÁO ĐẶT HÀNG THÀNH CÔNG --}}
    @if(session('order_success'))

        <div class="alert alert-success text-center">

            <h4 class="mb-2">
                <i class="fa fa-check-circle"></i>
                ĐẶT HÀNG THÀNH CÔNG
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

            <a href="{{ url('/') }}"
               class="btn btn-primary mt-2">

               Tiếp tục mua sắm

            </a>

        </div>

    @endif



    {{-- DANH SÁCH SẢN PHẨM --}}
    <h5 class="text-center font-weight-bold text-primary">

        DANH SÁCH SẢN PHẨM

    </h5>


    {{-- CÓ SẢN PHẨM --}}
    @if(count($products) > 0 && !session('order_success'))

        <table class="table table-bordered mt-3">

            <thead class="bg-light">

                <tr class="text-center">

                    <th style="width:8%">STT</th>

                    <th>Tên sản phẩm</th>

                    <th style="width:12%">Số lượng</th>

                    <th style="width:15%">Đơn giá</th>

                    <th style="width:10%">Xóa</th>

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
                            {{ $item->name }}
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



            {{-- TỔNG TIỀN --}}
            <tfoot>

                <tr>

                    <td colspan="3"></td>

                    <td class="font-weight-bold text-center">

                        Tổng cộng

                    </td>

                    <td class="font-weight-bold text-right text-danger">

                        {{ number_format($tong,0,',','.') }}đ

                    </td>

                </tr>

            </tfoot>

        </table>



        {{-- FORM ĐẶT HÀNG --}}
        <div class="text-center mt-3">

            <label class="font-weight-bold">

                Hình thức thanh toán

            </label>

            <br>


            <form method="POST" action="{{ route('cart.checkout') }}">
            @csrf

            <select name="hinh_thuc_thanh_toan"
                    class="form-control d-inline-block mt-2"
                    style="width:250px;"
                    required>

                <option value="1">
                    Thanh toán khi nhận hàng (COD)
                </option>

                <option value="2">
                    Chuyển khoản ngân hàng
                </option>

            </select>

            <br>

            <button type="submit"
                    class="btn btn-primary mt-3 px-4">

                    <i class="fa fa-credit-card"></i>
                    ĐẶT HÀNG

            </button>
        </form>

        </div>



    {{-- GIỎ HÀNG RỖNG --}}
    @elseif(!session('order_success'))

        <div class="alert alert-info text-center mt-4">

            <i class="fa fa-shopping-cart fa-3x mb-3"></i>

            <h4>

                Giỏ hàng của bạn đang trống!

            </h4>

            <a href="{{ url('/') }}"
               class="btn btn-primary mt-2">

               Mua sắm ngay

            </a>

        </div>

    @endif

</div>



{{-- AJAX XÓA SẢN PHẨM --}}
<script>

$(document).ready(function(){

    $('.remove-item').click(function(){

        var id = $(this).data('id');

        var row = $('#row-' + id);

        if(confirm('Bạn có muốn xóa sản phẩm này?')){

            $.ajax({

                type: "POST",

                url: "{{ route('cart.remove') }}",

                data: {

                    "_token": "{{ csrf_token() }}",

                    "id": id

                },

                success: function(response){

                    row.remove();

                    location.reload();

                },

                error: function(){

                    alert('Có lỗi xảy ra!');

                }

            });

        }

    });

});

</script>

@endsection