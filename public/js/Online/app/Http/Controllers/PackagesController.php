<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Package;
use Illuminate\Http\Request;

class PackagesController extends Controller
{

    public function get (Request $request) {
        $packages=new Package();
        if($request->search!='') {
            $packages=$packages->where('KuTitle','like','%'.$request->search.'%')->orWhere('EnTitle','like','%'.$request->search.'%');
        }
        $packages=$packages->orderByDesc('id')->paginate(25);
        return response()->json($packages);
    }

   public function getPackage($id) {
        $package=new Package();
        $package=$package->where('id',$id)->first();
        return response()->json($package);
    }


    public function getAll (Request $request) {
        $packages=new Package();
        $packages=$packages->orderByDesc('id')->get();
        return response()->json($packages);
    }


    public function add(Request $request) {
        $request->validate([
            'KuTitle'=>'required|string|max:191',
            'EnTitle'=>'required|string',
            'KuDesc'=>'required',
            'EnDesc'=>'required',
        ]);

        $package=new Package();


        $package->KuTitle=$request->KuTitle;
        $package->EnTitle=$request->KuTitle;
        $package->KuDesc=$request->KuDesc;
        $package->EnDesc=$request->EnDesc;

        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $package->image=$img;
        }
        else {
            $package->image='noImage.png';
        }

        $package->save();

    }

    public function update(Request $request) {

        $request->validate([
            'id'=>'required|numeric',
            'KuTitle'=>'required|string|max:191',
            'EnTitle'=>'required',
            'KuDesc'=>'required',
            'EnDesc'=>'required',
        ]);

        $package=Package::find($request->id);


        $package->KuTitle=$request->KuTitle;
        $package->EnTitle=$request->KuTitle;
        $package->KuDesc=$request->KuDesc;
        $package->EnDesc=$request->EnDesc;

        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $package->image=$img;
        }
        $package->save();
    }

    public function delete(Request $request) {
        $request->validate([
            'id'=>'required|numeric',
        ]);

        $package=Package::find($request->id)->delete();
    }

}

?>
