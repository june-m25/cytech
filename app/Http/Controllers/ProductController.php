<?php

namespace App\Http\Controllers;

//使用するモデルを記載
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Read(読み取り)
    //index=データ一覧表示
    public function index()
    {
        //商品一覧画面
        $products = Product::all();
        //products_tableデータがすべて格納
        //Product=モデル名

        return view('products.index', compact('products'));
        //compact=格納データをビュー側で利用できる
        //products=変数名

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Create(作成)
    //create=新規作成用フォーム表示
    public function create()
    {
        //商品新規登録画面
        //会社情報必要
        $companies = Company::all();
                
        return view('products.create', compact('companies'));
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //Create(作成)
     //store=データ新規保存
    public function store(Request $request)
    {
        //商品新規登録画面

        //Request=送られてきたデータを変数へ
        //->$validate=変数データが特定条件を満たしているかをチェック
        //required=必須,nullable=未入力OK
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
            //2048=2メガバイト
        ]);

        //新規登録ボタン

        //変数:$requestから情報取得
        //new=新しいレコードを追加
        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
        ]);

        //もし画像が含まれている場合、保存する
        if($request->hasFile('img_path')){
        //→アップロードファイルが存在しているかチェック

            $filename = $request->img_path->getClientOriginalName();
            //→アップロードファイル名を取得

            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            //→特定の場所(storage/app/public/products)に特定の名前($filename)で保存
            //products=保存先フォルダ名
            //public=アクセス権限(公開設定=誰でもアクセスOk)

            $product->img_path = '/storage/' . $filePath;
            //→保存場所(storage/app/public/products)、保存名
        }

        //データベースに新しいレコードとして保存
        $product->save();

        //全ての処理が終わったら商品一覧画面に戻る
        return redirect('products');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //Read(読み取り)
     //show=データ個別表示
    public function show(Product $product)
    {
        //商品情報詳細画面
        //指定されたIDでデータベースから検索する

        return view('products.show', compact('products'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Update(更新)
    //edit=データ編集用フォーム表示
    public function edit(Product $product)
    {
        //商品情報編集画面

        $companies = Company::all();
        //→会社情報が必要

        return view('products.edit', compact('products', 'companies'));

    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Update(更新)
    //update=データ更新
    public function update(Request $request, Product $product)
    {
        //更新ボタン

        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        //商品情報を更新
        //モデルの値を書き換える
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;

        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
        //→ビューにメッセージを送る(代入：success)
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Delete(削除)
    //destroy=データ削除
    public function destroy(Product $product)
    {
        //削除ボタン

        $product->delete();

        return redirect('/products');
        
    }
}
