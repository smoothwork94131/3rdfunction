<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;

class ApiController extends Controller
{
    public function updateQuantityBySku(Request $request) {
        $sku = $request->sku;
        $quantity = $request->quantity;

        $series = DB::table('categories')
            ->select('name')
            ->where('parent', '!=', 0)
            ->where('status', 1)
            ->get();

        foreach($series as $serie) {
            $table = strtolow($series->name);

            DB::table($table)
                ->where('sku', $sku)
                ->update(['quantity' => $quantity]);
        }
    }
}
