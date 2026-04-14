{{-- resources/views/laptop/chitiet.blade.php --}}
@extends('components.laptop-layout')

@section('title', $laptop->tieu_de ?? $laptop->ten)

@section('content')

<div class="row mt-4">

    {{-- Hình ảnh bên trái --}}
    <div class="col-md-4 text-center">
        <img src="{{ asset('storage/image/' . $laptop->hinh_anh) }}"
             alt="{{ $laptop->ten }}"
             class="img-fluid"
             style="max-height:300px; object-fit:contain;">
    </div>


    {{-- Nội dung bên phải --}}
    <div class="col-md-8">

        {{-- Tên sản phẩm --}}
        <h4 class="font-weight-bold mb-3">
            {{ $laptop->tieu_de ?? $laptop->ten }}
        </h4>

        {{-- Thông số chính --}}
        <p>
            <strong>CPU:</strong>
            {{ $laptop->cpu }}
        </p>

        <p>
            <strong>RAM:</strong>
            {{ $laptop->ram }}
        </p>

        <p>
            <strong>Ổ cứng:</strong>
            {{ $laptop->luu_tru }}
        </p>

        <p>
            <strong>Chip đồ họa:</strong>
            {{ $laptop->chip_do_hoa }}
        </p>

        <p>
            <strong>Màn hình:</strong>
            {{ $laptop->man_hinh }}
        </p>

        <p>
            <strong>Hệ điều hành:</strong>
            {{ $laptop->he_dieu_hanh }}
        </p>

        {{-- Giá --}}
        <h4 class="text-danger font-weight-bold mt-3">
            Giá: {{ number_format($laptop->gia,0,',','.') }} VND
        </h4>


        {{-- Số lượng + Thêm giỏ --}}
        <div class="mt-3 pb-3 border-bottom">

            <div class="d-flex align-items-center">

                <label class="mr-2 font-weight-bold mb-0">
                    Số lượng mua:
                </label>

                <input type="number"
                       id="product-quantity"
                       class="form-control"
                       style="width:80px;"
                       min="1"
                       value="1">

                <button class="btn btn-primary ml-2"
                        id="add-to-cart"
                        data-id="{{ $laptop->id }}">

                    Thêm vào giỏ hàng

                </button>

            </div>

        </div>


        {{-- THÔNG TIN KHÁC (đúng vị trí như ảnh) --}}
        <div class="mt-3">

            <h5 class="font-weight-bold">
                Thông tin khác
            </h5>

            <p>
                <strong>Khối lượng:</strong>
                {{ $laptop->khoi_luong }}
            </p>

            <p>
                <strong>Webcam:</strong>
                {{ $laptop->webcam ?? 'FHD webcam' }}
            </p>

            <p>
                <strong>Pin:</strong>
                {{ $laptop->pin }}
            </p>

            <p>
                <strong>Kết nối không dây:</strong>
                {{ $laptop->ket_noi ?? 'Wi-Fi, Bluetooth' }}
            </p>

            <p>
                <strong>Bàn phím:</strong>
                {{ $laptop->ban_phim ?? 'Chiclet' }}
            </p>

            <p>
                <strong>Cổng kết nối:</strong>
                {!! $laptop->cong_ket_noi !!}
            </p>

        </div>

    </div>

</div>



{{-- AJAX thêm vào giỏ --}}
<script>

$(document).ready(function() {

    // Không cho số lượng < 1
    $('#product-quantity').on('change', function() {

        if ($(this).val() < 1) {
            $(this).val(1);
        }

    });


    // Thêm vào giỏ hàng
    $('#add-to-cart').click(function() {

        var id = $(this).data('id');
        var num = $('#product-quantity').val();

        $.ajax({

            type: "POST",
            dataType: "json",
            url: "{{ route('cart.add') }}",

            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "num": num
            },

            beforeSend: function() {

                $('#add-to-cart')
                    .html('<i class="fa fa-spinner fa-spin"></i> Đang thêm...')
                    .prop('disabled', true);

            },

            success: function(data) {

                $('#cart-number-product').html(data);

                $('#add-to-cart')
                    .html('<i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng')
                    .prop('disabled', false);

                alert('Đã thêm sản phẩm vào giỏ hàng!');

            },

            error: function() {

                $('#add-to-cart')
                    .html('<i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng')
                    .prop('disabled', false);

                alert('Có lỗi xảy ra!');

            }

        });

    });

});

</script>

@endsection