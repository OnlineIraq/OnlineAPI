<?php

namespace App\Http\Controllers;

use App\Models\Sites;
use Illuminate\Http\Request;

class SitesController extends Controller
{

    public function get(Request $request) {
        $sites=new Sites();
        if($request->search!='') {
            $sites=$sites->where('KuTitle','like','%'.$request->search.'%')->orWhere('EnTitle','like','%'.$request->search.'%')
            ->orWhere('ArTitle','like','%'.$request->search.'%')->orWhere('lat','like','%'.$request->search.'%')->orWhere('long','like','%'.$request->search.'%');
        }
        $sites=$sites->orderByDesc('id')->paginate(25);
        return response()->json($sites);
    }

    public function getAll(Request $request) {
        $sites=new Sites();

        $sites=$sites->orderByDesc('id')->get();
        return response()->json($sites);
    }

    public function add(Request $request) {
        $request->validate([
            'KuTitle'=>'required|string|max:191',
            'EnTitle'=>'required|string',
            'ArTitle'=>'required|string',
            'lat'=>'required',
            'long'=>'required',
        ]);

        $sites=new Sites();


        $sites->KuTitle=$request->KuTitle;
        $sites->EnTitle=$request->EnTitle;
        $sites->ArTitle=$request->ArTitle;
        $sites->lat=$request->lat;
        $sites->long=$request->long;

        $sites->save();

    }

    public function update(Request $request) {

        $request->validate([
            'id'=>'required|numeric',
            'KuTitle'=>'required|string|max:191',
            'EnTitle'=>'required|string',
            'ArTitle'=>'required|string',
            'lat'=>'required',
            'long'=>'required',
        ]);

        $sites=Sites::find($request->id);


        $sites->KuTitle=$request->KuTitle;
        $sites->EnTitle=$request->EnTitle;
        $sites->ArTitle=$request->ArTitle;
        $sites->lat=$request->lat;
        $sites->long=$request->long;

        $sites->save();
    }

    public function delete(Request $request) {
        $request->validate([
            'id'=>'required|numeric',
        ]);

        Sites::find($request->id)->delete();
    }

}

?>
