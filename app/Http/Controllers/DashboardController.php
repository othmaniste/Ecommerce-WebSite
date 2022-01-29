<?php

namespace App\Http\Controllers;

use File;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

 

    public function dashboard(){
        $nbr_Products = DB::table('products')->count();        
        return view('Dashboard.dashboard',[
            'nbr_Products' => $nbr_Products
            ]);
    }

    public function newProduct(){
        return view('Dashboard.CreateProduct');
    }


    public function addProduct(Request $req){

        $validateData = $req->validate([
            'productName'=>'required|min:4|max:100',
            'description' =>'required|min:4',
            'salesPrice' =>'required'
        ]);

        $product = new Product;
        $product->name = $req->productName;
        $product->description = $req->description;
        $product->age = $req->flexRadioDefault;
        $product->price = $req->salesPrice;
        $product->regularPrice = $req->regularPrice;
        $product->category = $req->category;
        $product->quantity = $req->quantity;
        $product->size = $req->size;
        

        if($req->hasFile('mainImage')){
            $file = $req->file('mainImage');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
           
            $file->move('imgs/',$filename);
            $product->image_path = 'imgs/'.$filename;
        }else{
            $product->image_path = 'image not found';
        }

        if($req->hasFile('productImage')){
            $file = $req->file('productImage');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move('imgs2/',$filename);
            $product->product_image = 'imgs2/'.$filename;
        }else{
            $product->product_image = 'image not found';
        }

        if($req->hasFile('sideImage')){
            $file = $req->file('sideImage');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move('imgs/',$filename);
            $product->side_image = 'imgs/'.$filename;
        }else{
            $product->side_image = 'image not found';
        }
        // if($req->hasFile('manImage')){
        //     $file = $req->file('manImage');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time().'.'.$extension;
        //     $file->move('imgs/',$filename);
        //     $product->man_image = 'imgs/'.$filename;
        // }else{
        //     $product->man_image = 'image not found';
        // }
        // if($req->hasFile('womanImage')){
        //     $file = $req->file('womanImage');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time().'.'.$extension;
        //     $file->move('imgs/',$filename);
        //     $product->women_image = 'imgs/'.$filename;
        // }else{
        //     $product->women_image = 'image not found';
        // }
        // $product->image_path = $req->mainImage;
        $product->color = $req->color;
        $product->material = $req->Material;
            // material
        $product->save();
        // return $req->file('imageTest')->store('IMGS');
        return redirect('create-product');
    }

    public function listProducts(){
        // $products = DB::table('products')->paginate(7);
        $products = Product::paginate(2);
        return view('Dashboard.listProducts',['products' => $products]);
    }

    public function Delete($id){
        $product = Product::findorfail($id);
        if($product){
            $product->delete();
        }
        return redirect('list-products');
    }

}
