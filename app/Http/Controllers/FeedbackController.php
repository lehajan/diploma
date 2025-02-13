<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function create(request $request)
    {
        $data = $request->validate([
            'rating' => 'required | numeric | min:0 | max:5',
            'comment' => 'required',
        ]);
        $user_id = Auth::id();
        $data['user_id'] = $user_id;
        Feedback::create($data
//            'user_id' => $data['user_id'],
//            'rating' => $data['rating'],
//            'comment' => $data['comment']
        );
        return response()->json(['отзыв создан!']);
    }
}
