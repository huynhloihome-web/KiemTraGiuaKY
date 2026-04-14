<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .container {
            width: 600px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        h2 {
            color: green;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            color: red;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>XÁC NHẬN ĐƠN HÀNG THÀNH CÔNG</h2>

    <p>
        Xin chào
        <strong>
            {{ $orderInfo['user_name'] ?? 'Quý khách' }}
        </strong>,
    </p>

    <p>
        Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi.
    </p>

    <p>
        <strong>Mã đơn hàng:</strong>
        #{{ $orderInfo['order_id'] }}
    </p>

    <p>
        <strong>Ngày đặt:</strong>
        {{ $orderInfo['order_date'] ?? date('d/m/Y H:i:s') }}
    </p>

    <p>
        <strong>Hình thức thanh toán:</strong>
        {{ $orderInfo['payment_method'] }}
    </p>

    <h3>Chi tiết đơn hàng</h3>

    <table>

        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>

        <tbody>

        @php $stt = 1; @endphp

        @foreach($orderInfo['products'] as $product)

        <tr>
            <td>{{ $stt++ }}</td>
            <td>{{ $product->tieu_de ?? $product->ten }}</td>
            <td>{{ $orderInfo['quantities'][$product->id] }}</td>
            <td>{{ number_format($product->gia,0,',','.') }}đ</td>
            <td>
                {{ number_format(
                    $product->gia * $orderInfo['quantities'][$product->id],
                    0, ',', '.'
                ) }}đ
             </td>
        </tr>

        @endforeach

        </tbody>

        <tfoot>

            <tr>
                <td colspan="4" class="total">Tổng cộng</td>
                <td class="total">
                    {{ number_format($orderInfo['total'], 0, ',', '.') }}đ
                </td>
            </tr>

        </tfoot>

    </table>

    <p style="margin-top:20px;">
        Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.
    </p>

    <p>
        Trân trọng,<br>
        <strong>Laptop Store</strong>
    </p>

</div>

</body>
</html>