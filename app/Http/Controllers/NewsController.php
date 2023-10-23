<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function get (Request $request) {
        $news=new News();
        if($request->search!='') {
            $news=$news->where('KuTitle','like','%'.$request->search.'%')
            ->orWhere('EnTitle','like','%'.$request->search.'%')
            ->orWhere('ArTitle','like','%'.$request->search.'%');
        }
        $news=$news->orderByDesc('id')->paginate(25);
        return response()->json($news);
    }

     public function getNews(Request $request) {
        $news=News::where('id',$request->id)->first();
        return response()->json($news);
    }

    public function getAll (Request $request) {
        $news=new News();
        $news=$news->orderByDesc('id')->get();
        return response()->json($news);
    }


    public function add(Request $request) {
        $request->validate([
            'KuTitle'=>'required|string',
            'EnTitle'=>'required|string',
            'ArTitle'=>'required|string',
            'KuDesc'=>'required',
            'EnDesc'=>'required',
            'ArDesc'=>'required',
        ]);

        $news=new News();


        $news->KuTitle=$request->KuTitle;
        $news->EnTitle=$request->EnTitle;
        $news->ArTitle=$request->ArTitle;
        $news->KuDesc=$request->KuDesc;
        $news->EnDesc=$request->EnDesc;
        $news->ArDesc=$request->ArDesc;

        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $news->image=$img;
        }
        else {
            $news->image='noImage.png';
        }

        $news->save();

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
        ]);

        $news=News::find($request->id);


        $news->KuTitle=$request->KuTitle;
        $news->EnTitle=$request->EnTitle;
        $news->ArTitle=$request->ArTitle;
        $news->KuDesc=$request->KuDesc;
        $news->EnDesc=$request->EnDesc;
        $news->ArDesc=$request->ArDesc;

        if($request->hasFile('image')) {
            $image=$request->file('image');
            $img=time().$image->getClientOriginalName();
            $image->move('images',$img);

            $news->image=$img;
        }
        $news->save();
    }

    public function delete(Request $request) {
        $request->validate([
            'id'=>'required|numeric',
        ]);

        $news=News::find($request->id)->delete();
    }

}

?>
