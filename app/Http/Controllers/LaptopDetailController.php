<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
            ->where('status', 1)
            ->first();
        
        if (!$laptop) {
            abort(404);
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
            ->first();
        
        if (!$product) {
            return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $num;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->ten ?? $product->tieu_de,
                'price' => $product->gia,
                'image' => $product->hinh_anh,
                'quantity' => $num
            ];
        }
        
        session()->put('cart', $cart);
        
        // Tính tổng số lượng sản phẩm trong giỏ
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
        }
        
        return response()->json($totalItems);
    }
    
    /**
     * Hiển thị trang giỏ hàng
     */
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;
        
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $products[] = (object) $item;
                $total += $item['price'] * $item['quantity'];
            }
        }
        
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
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Xử lý đặt hàng
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'hinh_thuc_thanh_toan' => ['required', 'numeric']
        ]);
        
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt hàng');
        }
        
        // Kiểm tra giỏ hàng
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Giỏ hàng trống');
        }
        
        // Lấy danh sách id sản phẩm
        $list_id = '';
        foreach ($cart as $id => $item) {
            $list_id .= $id . ', ';
        }
        $list_id = substr($list_id, 0, strlen($list_id) - 2);
        
        // Lấy thông tin sản phẩm
        $data = DB::table('san_pham')
            ->whereRaw('id in (' . $list_id . ')')
            ->where('status', 1)
            ->get();
        
        // Tạo đơn hàng
        $order = [
            'ngay_dat_hang' => DB::raw('NOW()'),
            'tinh_trang' => 1,
            'hinh_thuc_thanh_toan' => $request->hinh_thuc_thanh_toan,
            'user_id' => Auth::user()->id
        ];
        
        DB::transaction(function () use ($order, $cart, $data) {
            // Insert đơn hàng
            $id_don_hang = DB::table('don_hang')->insertGetId($order);
            
            // Insert chi tiết đơn hàng
            foreach ($data as $row) {
                DB::table('chi_tiet_don_hang')->insert([
                    'ma_don_hang' => $id_don_hang,
                    'laptop_id' => $row->id,
                    'so_luong' => $cart[$row->id]['quantity'],
                    'don_gia' => $row->gia
                ]);
            }
            
            // Xóa giỏ hàng
            session()->forget('cart');
        });
        
        return redirect()->route('cart.view')->with('status', 'Đặt hàng thành công!');
    }
    
    /**
     * Lấy số lượng sản phẩm trong giỏ hàng (AJAX)
     */
    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
        }
        
        return response()->json(['total_items' => $totalItems]);
    }
}