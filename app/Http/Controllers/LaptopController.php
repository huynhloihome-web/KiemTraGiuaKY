<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaptopController extends Controller
{
    public function index(Request $request)
{
    // Lấy tham số brand và sort từ URL
    $brandId = $request->query('brand');
    $sort = $request->query('sort');

    // Khởi tạo truy vấn
    $query = DB::table('san_pham');

    // Nếu người dùng chọn thương hiệu, lọc theo id_danh_muc
    if ($brandId) {
        $query->where('id_danh_muc', $brandId);
    }

    // Xử lý sắp xếp giá (nếu có)
    if ($sort === 'asc') {
        $query->orderBy('gia', 'asc');
    } elseif ($sort === 'desc') {
        $query->orderBy('gia', 'desc');
    }

    /**
     * Quy tắc hiển thị:
     * - Nếu không chọn thương hiệu và không sắp xếp (Trang chủ mới vào): Lấy đúng 20 cái.
     * - Nếu đã chọn thương hiệu: Lấy toàn bộ sản phẩm của thương hiệu đó.
     */
    if (!$brandId && !$sort) {
        $laptops = $query->limit(20)->get();
    } else {
        $laptops = $query->get();
    }

    // Lấy danh sách danh mục để truyền vào Menu (nếu menu của bạn dùng dữ liệu động)
    $categories = DB::table('danh_muc_laptop')->get();

    return view('laptop.index', [
            'laptops' => $laptops,
            'categories' => $categories,
            'currentBrand' => $brandId,
            'currentSort' => $sort
        ]);
}
}