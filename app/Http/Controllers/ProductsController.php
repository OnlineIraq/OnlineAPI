<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function get(Request $request) {
        $product=new Products();
        if($request->search!='') {
            $product=$product->where('KuTitle','like','%'.$request->search.'%')
            ->orWhere('EnTitle','like','%'.$request->search.'%')
            ->orWhere('ArTitle','like','%'.$request->search.'%');
        }
        $product=$product->orderByDesc('id')->paginate(25);
        return response()->json($product);
    }

     public function getProducts(Request $request) {
        $product=Products::where('id',$request->id)->first();
        return response()->json($product);
    }

    public function getAll(Request $request) {
        $product=new Products();
        $product=$product->orderByDesc('id')->get();
        return response()->json($product);
    }


    public function add(Request $request) {
        $request->validate([
            'KuTitle'=>'required|string',
            'EnTitle'=>'required|string',
            'ArTitle'=>'required|string',
            'KuDesc'=>'required',
            'EnDesc'=>'required',
            'ArDesc'=>'required',
            'Price'=>'required',
        ]);

        $product=new Products();


        $product->KuTitle=$request->KuTitle;
        $product->EnTitle=$request->EnTitle;
        $product->ArTitle=$request->ArTitle;
        $product->KuDesc=$request->KuDesc;
        $product->EnDesc=$request->EnDesc;
        $product->ArDesc=$request->ArDesc;
        $product->Price=$request->Price;

        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $product->image=$img;
        }
        else {
            $product->image='noImage.png';
        }

        $product->save();

    }

    public function update(Request $request) {

        $request->validate([
            'id'=>'required|numeric',
            'KuTitle'=>'required|string',
            'EnTitle'=>'required|string',
            'ArTitle'=>'required|string',
            'KuDesc'=>'required',
            'EnDesc'=>'required',
            'ArDesc'=>'required',
            'Price'=>'required',
        ]);

        $product=Products::find($request->id);


        $product->KuTitle=$request->KuTitle;
        $product->EnTitle=$request->EnTitle;
        $product->ArTitle=$request->ArTitle;
        $product->KuDesc=$request->KuDesc;
        $product->EnDesc=$request->EnDesc;
        $product->ArDesc=$request->ArDesc;
        $product->Price=$request->Price;

        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $product->image=$img;
        }
        $product->save();
    }

    public function delete(Request $request) {
        $request->validate([
            'id'=>'required|numeric',
        ]);

        $product=Products::find($request->id)->delete();
    }

}

?>
