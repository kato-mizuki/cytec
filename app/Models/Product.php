<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Company;

class Product extends Model
{
    protected $table = 'products';

    //登録・変更可能なカラムを設定
    protected $fillable = [
        'name',
        'price',
        'stock',
        'comment',
        'image_path',
        'company_id'
    ];

    public function company() {
        return $this->belongsTo('App\Models\Company');
    }
    
    public function getList($input) {

        $companies = Company::all();
        //50行目・58行目で存在しない変数を探そうとしているため、事前に定義しておく
        $keyword = '';
        $companyId = '';

        //検索フォーム 入力内容処理
        if(array_key_exists('keyword', $input)) {
          $keyword = $input['keyword'];
        }
        
        if(array_key_exists('companyId', $input)) {
          $companyId = $input['companyId'];
        }
        
        $query = Product::query();

        if(!empty($keyword)) {
            
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        if(!empty($companyId)) {

            $query->where('company_id', 'LIKE', "$companyId");
        }

        if(empty($keyword) && empty($companyId)){
         $products = Product::all();
        }else{
         $products = $query->get();
        }
        
        return $products; //controllerで呼び出した$productsに処理した結果を返している
    }

    //controller記載のstoreメソッド移行
    public function createNewProduct(Request $request) {

        $filename = $request->image_path->getClientOriginalName();

        $img = $request->image_path->storeAs('public', $filename);

        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');
        $product->image_path = $img;
        $product->company_id = $request->input('company_id');
        $product->save();

        return $product;
    }

    public function updateProduct($data) {

        $product = Product::findOrFail($data['id']);
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->comment =$data['comment'];
        $product->company_id = $data['company_id'];

        if(isset($data['image_path'])) {
            Storage::delete('public', $product->image_path);
            $filename = $data['image_path']->getClientOriginalName();
            $img = $data['image_path']->storeAs('public', $filename);
            $product->image_path = $img;
        }

        $product->save();
        /*
        if ($request->hasFile('image_path')) {
            Storage::delete('public', $product->image_path);
            $filename = $request->image_path->getClientOriginalName();
            $img = $request->image_path->storeAs('public', $filename);
            $product->image_path = $img;
            //$product->save();
        }
        //dd($product);
        //$product->save($request->except('image_path'));
        $product->save();
        */
        return $product;
    }
    

/*
    public function user() {
        return $this->belongsTo('App\User');
    }
*/    
}
