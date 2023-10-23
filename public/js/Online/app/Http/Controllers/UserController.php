<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function get (Request $request) {
        $users=new User();
        if($request->search!='') {
            $users=$users->where('name','like','%'.$request->search.'%')->orWhere('email','like','%'.$request->search.'%');
        }
        $users=$users->orderByDesc('id')->paginate(25);
        return response()->json($users);
    }

    public function add(Request $request) {
        $request->validate([
            'name'=>'required|string|max:191',
            'email'=>'required|email|unique:users',
            'password'=>'required|max:191',
        ]);

        $user=new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->save();

    }

    public function update(Request $request) {

        $request->validate([
            'id'=>'required|numeric',
            'name'=>'required|string|max:191',
            'email'=>'required|email|unique:users,email,'.$request->id,
        ]);

        $user=User::find($request->id);
        $user->name=$request->name;
        $user->email=$request->email;
            $user->password=bcrypt($request->password);

        $user->save();
    }

    public function delete(Request $request) {
        $request->validate([
            'id'=>'required|numeric',
        ]);

        $user=User::find($request->id)->delete();
    }

}
