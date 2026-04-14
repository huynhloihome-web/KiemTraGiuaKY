<?php
// app/Http/Controllers/LaptopDetailController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderSuccessNotification;

class LaptopDetailController extends Controller
{
    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        // Lấy thông tin laptop
        $laptop = DB::table('san_pham')
            ->where('id', $id)
            ->where('status', 1) // Chỉ lấy sản phẩm chưa bị xóa mềm
            ->first();
        
        if (!$laptop) {
            abort(404, 'Sản phẩm không tồn tại');
        }
        
        // Lấy thông tin danh mục
        $category = DB::table('danh_muc_laptop')
            ->where('id', $laptop->id_danh_muc)
            ->first();
        
        // Lấy danh sách danh mục cho menu
        $categories = DB::table('danh_muc_laptop')->get();
        
        return view('laptop.chitiet', compact('laptop', 'category', 'categories'));
    }
    
    /**
     * Thêm sản phẩm vào giỏ hàng (AJAX)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric'],
            'num' => ['required', 'numeric', 'min:1']
        ]);
        
        $id = $request->input('id');
        $num = $request->input('num');
        
        // Lấy thông tin sản phẩm
        $product = DB::table('san_pham')
            ->where('id', $id)
            ->where('status', 1)
            ->select('id', 'ten', 'tieu_de', 'gia', 'hinh_anh')
            ->first();
        
        if (!$product) {
            return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            // Nếu sản phẩm đã có trong giỏ, cập nhật số lượng
            $cart[$id]['quantity'] += $num;
        } else {
            // Thêm sản phẩm mới vào giỏ
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->ten ?? $product->tieu_de,
                'price' => $product->gia,
                'image' => $product->hinh_anh,
                'quantity' => $num
            ];
        }
        
        session()->put('cart', $cart);
        
        // Trả về số lượng sản phẩm trong giỏ
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json($totalItems);
    }
    
    /**
     * Hiển thị trang giỏ hàng
     */
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $products = [];
        
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $products[] = (object) $item;
                $total += $item['price'] * $item['quantity'];
            }
        }
        
        // Lấy danh sách danh mục cho menu
        $categories = DB::table('danh_muc_laptop')->get();
        
        return view('laptop.gio-hang', compact('products', 'total', 'categories'));
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng (AJAX)
     */
    public function removeCart(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric']
        ]);
        
        $id = $request->input('id');
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        // Tính lại tổng số lượng
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'success' => true,
            'total_items' => $totalItems,
            'cart_count' => count($cart)
        ]);
    }
    
public function checkout(Request $request)
{
    // Validate hình thức thanh toán
    $request->validate([
        'hinh_thuc_thanh_toan' => ['required', 'numeric', 'in:1,2']
    ]);

    // Kiểm tra đăng nhập
    if (!Auth::check()) {
        return redirect()
            ->route('login')
            ->with('error', 'Vui lòng đăng nhập để đặt hàng');
    }

    // Kiểm tra giỏ hàng
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()
            ->route('cart.view')
            ->with('error', 'Giỏ hàng trống');
    }

    // Lấy sản phẩm
    $productIds = array_keys($cart);
    $products = DB::table('san_pham')
        ->whereIn('id', $productIds)
        ->where('status', 1)
        ->get();

    $total = 0;
    $details = [];

    foreach ($cart as $id => $item) {
        $product = $products->firstWhere('id', $id);
        if (!$product) continue;

        $quantity = $item['quantity'];
        $price = $product->gia;
        $total += $price * $quantity;

        $details[] = [
            'laptop_id' => $id,
            'so_luong' => $quantity,
            'don_gia' => $price
        ];
    }

    // Lưu đơn hàng
    $orderId = null;
    DB::transaction(function () use (&$orderId, $details, $request) {
        $orderId = DB::table('don_hang')->insertGetId([
            'ngay_dat_hang' => DB::raw('NOW()'),
            'tinh_trang' => 1,
            'hinh_thuc_thanh_toan' => $request->hinh_thuc_thanh_toan,
            'user_id' => Auth::id()
        ]);

        foreach ($details as &$d) {
            $d['ma_don_hang'] = $orderId;
        }

        DB::table('chi_tiet_don_hang')->insert($details);
    });

    // Chuẩn bị dữ liệu gửi mail
    $user = Auth::user();
    
    $quantities = [];
    foreach ($cart as $id => $item) {
        $quantities[$id] = $item['quantity'];
    }

    $orderInfo = [
        'order_id' => $orderId,
        'products' => $products,
        'quantities' => $quantities,
        'total' => $total,
        'payment_method' => $request->hinh_thuc_thanh_toan,
        'user_name' => $user->name,
        'user_email' => $user->email,
        'order_date' => now()->format('d/m/Y H:i:s')
    ];

    // GỬI MAIL
    $mailStatus = '';
    try {
        Notification::send($user, new OrderSuccessNotification($orderInfo));
        $mailStatus = 'Email xác nhận đã được gửi đến ' . $user->email;
    } catch (\Exception $e) {
        \Log::error('Gửi mail thất bại: ' . $e->getMessage());
        $mailStatus = 'Đặt hàng thành công nhưng gửi email thất bại. Chúng tôi sẽ liên hệ lại sau.';
    }

    // XÓA GIỎ HÀNG
    session()->forget('cart');

    // THÔNG BÁO THÀNH CÔNG
    return redirect()
        ->route('cart.view')
        ->with('order_success', [
            'order_id' => $orderId,
            'total' => $total,
            'mail_status' => $mailStatus
        ]);
}
    
    /**
     * Lấy số lượng sản phẩm trong giỏ hàng (AJAX)
     */
    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json(['count' => $totalItems]);
    }
}