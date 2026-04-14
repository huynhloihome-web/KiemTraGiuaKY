<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class Controller5 extends Controller
{
    public function timkiem(Request $request)
    {
        $keyword = $request->keyword;

        $laptops = DB::table('san_pham')
            ->where('status', 1) 
            ->where(function ($query) use ($keyword) {
                $query->where('ten', 'like', "%$keyword%")
                    ->orWhere('tieu_de', 'like', "%$keyword%")
                    ->orWhere('cpu', 'like', "%$keyword%");
            })
            ->get();

        return view('laptop.timkiem', compact('laptops', 'keyword'));
    }
}