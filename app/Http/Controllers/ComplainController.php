<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use App\Models\Replay;
use Illuminate\Http\Request;

class ComplainController extends Controller
{
    public function get(Request $request)
    {
        $complains = Complain::with('replies');
        if ($request->search != '') {
            $complains = $complains->where('username', 'like', '%' . $request->search . '%')->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        if ($request->isRead != -1) {
            $complains = $complains->where('isRead', $request->isRead);
        }
        $complains = $complains->orderByDesc('id')->paginate(25);
        return response()->json($complains);
    }

    public function getComplain($username)
    {
        $complains = Complain::with('replies');

        $complains =  $complains->where('username', $username)->orderByDesc('created_at')->get();

        if ($complains->isEmpty()) {
            return response()->json(['message' => 'No complaints found for the given username'], 404);
        }

        return response()->json(['complains' => $complains]);
    }


    public function add(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'phone' => 'required|string',
            'desc' => 'required',
        ]);

        $complain = new Complain();

        $complain->username = $request->username;
        $complain->phone = $request->phone;
        $complain->desc = $request->desc;
        if ($request->has('token_id')) {
            $complain->token_id = $request->token_id;
        }
        $complain->save();
    }

    public function updateRead(Request $request)
    {
        $complain = Complain::find($request->id);
        $complain->isRead = 1;
        $complain->save();
    }

    public function addReplies(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'complain_id' => 'required',
        ]);

        $replay = new Replay();
        $replay->complain_id = $request->complain_id;
        $replay->title = $request->title;
        $replay->body = $request->body;
        // Check if the isAdmin parameter is provided and not empty
        if ($request->has('isAdmin')) {
            $replay->isAdmin = $request->isAdmin;
        }
        $replay->save();
    }

    public function getReplies($complainId)
    {
        // Retrieve the complaint along with its associated replies
        $complain = Complain::with('replies')->find($complainId);

        if (!$complain) {
            return response()->json(['message' => 'Complaint not found'], 404);
        }

        return response()->json(['replies' => $complain->replies]);
    }
}
