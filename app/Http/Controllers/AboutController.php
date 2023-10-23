<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{


    public function get(Request $request) {
        $about=About::get();
        return response()->json($about);
    }

    public function update(Request $request) {

        $request->validate([
            'id'=>'required|numeric',
            'KuDesc'=>'required|string',
            'EnDesc'=>'required|string',
            'ArDesc'=>'required|string',
        ]);

        $about=About::find($request->id);

        $about->KuDesc=$request->KuDesc;
        $about->EnDesc=$request->EnDesc;
        $about->ArDesc=$request->ArDesc;

        $about->save();
    }


}

?>
