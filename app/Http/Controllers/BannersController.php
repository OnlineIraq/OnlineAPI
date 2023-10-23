<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannersController extends Controller
{

    public function get (Request $request) {
        $banners=new Banner();
        if($request->search!='') {
            $banners=$banners->where('name','like','%'.$request->search.'%');
        }
        $banners=$banners->orderByDesc('id')->paginate(25);
        return response()->json($banners);
    }

    public function getBanner(Request $request) {

        $banners=Banner::where('id',$request->id)->first();
        return response()->json($banners);

    }

    public function getAll (Request $request) {
        $banners=new Banner();
        $banners=$banners->orderByDesc('id')->get();
        return response()->json($banners);
    }


    public function add(Request $request) {
        $request->validate([
            'name'=>'required|string',
        ]);

        $banner=new Banner();


        $banner->name=$request->name;
        $banner->status=$request->status?1:0;


        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $banner->image=$img;
        }
        else {
            $banner->image='noImage.png';
        }

        $banner->save();

    }

    public function update(Request $request) {

        $request->validate([
            'id'=>'required|numeric',
            'name'=>'required|string',
        ]);

        $banner=banner::find($request->id);


        $banner->name=$request->name;
        $banner->status=$request->status?1:0;


        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $banner->image=$img;
        }
        $banner->save();
    }

    public function updateStatus(Request $request)
    {
        $complain = Banner::find($request->id);
        $complain->status = $complain->status == 1 ? 0 : 1;
        $complain->save();
    }

    public function delete(Request $request) {
        $request->validate([
            'id'=>'required|numeric',
        ]);

        $banner=banner::find($request->id)->delete();
    }

}

?>
