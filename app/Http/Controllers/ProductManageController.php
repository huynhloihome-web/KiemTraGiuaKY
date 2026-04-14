<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductManageController extends Controller
{
    public function index()
    {
        $data = DB::table('san_pham')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('product_manage.list', compact('data'));
    }

    public function detail($id)
    {
        $data = DB::table('san_pham as sp')
            ->join('danh_muc_laptop as dm', 'sp.id_danh_muc', '=', 'dm.id')
            ->select(
                'sp.*',
                'dm.ten_danh_muc'
            )
            ->where('sp.id', $id)
            ->where('sp.status', 1)
            ->first();

        if (!$data) {
            abort(404);
        }

        return view('product_manage.detail', compact('data'));
    }

    public function delete(Request $request)
    {
        DB::table('san_pham')
            ->where('id', $request->id)
            ->update(['status' => 0]);

        return redirect()->route('productlist');
    }
}