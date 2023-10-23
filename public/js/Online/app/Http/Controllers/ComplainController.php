<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use Illuminate\Http\Request;

class ComplainController extends Controller
{
    public function get(Request $request) {
        $complains=new Complain();
        if($request->search!='') {
            $complains=$complains->where('username','like','%'.$request->search.'%')->orWhere('phone','like','%'.$request->search.'%');
        }
        $complains=$complains->orderByDesc('id')->paginate(25);
        return response()->json($complains);
    }

    public function add(Request $request) {
        $request->validate([
            'username'=>'required|string',
            'phone'=>'required|string',
            'desc'=>'required',
        ]);

        $complain=new Complain();

        $complain->username=$request->username;
        $complain->phone=$request->phone;
        $complain->desc=$request->desc;

        $complain->save();
    }


}

?>
