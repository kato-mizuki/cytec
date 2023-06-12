<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class SalesController extends Controller
{
    public function purchase(Request $request) {

        //リクエストから商品IDを取得
        $productId = $request->input('product_id');
        // dd($request);

        //Saleモデルのインスタンス生成
        $sale =new Sale();

        //購入処理メソッド呼び出し
        $result = $sale->purchase($productId);

        if(isset($result->success)) {
            return response()->json($result, 200);
        } else {
            return response()->json($result, 400);
        }
    }
}
