<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $companies = Company::all();

      /* 検索フォーム 入力内容処理 */
        $keyword = $request->input('keyword');
        $companyId = $request->input('companyId');
        
        //dd($companyId);
        $query = Product::query();

        if(!empty($keyword)) {
        
            $query->where('name', 'LIKE', "%{$keyword}%");

            //$products = $query->get();
        }

        if(!empty($companyId)) {

            $query->where('company_id', 'LIKE', "$companyId");

            //$products = $query->get();
        }
        if(empty($keyword) && empty($companyId)){
         $products = Product::all();
        }else{
         $products = $query->get();
        }
        
        return view('products.index', compact('products', 'keyword', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();

        return view('products.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $filename = $request->image_path->getClientOriginalName();

        $img = $request->image_path->storeAs('public', $filename);

        //dd($img);

        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');
        $product->image_path = $img;
        $product->company_id = $request->input('company_id');
        $product->save();


        return redirect()->route('list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $companies = Company::all();
        
        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment =$request->input('comment');
        $product->company_id = $request->input('company_id');
        
        if ($request->hasFile('image_path')) {
            Storage::delete('public', $product->image_path);
            $filename = $request->image_path->getClientOriginalName();
            $img = $request->image_path->storeAs('public', $filename);
            $product->image_path = $img;
            $product->save();
        }
        //dd($product);
        $product->save($request->except('image_path'));
       

        return redirect()->route('list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('list');
    }
}
