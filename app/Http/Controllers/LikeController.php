<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Like;
use App\Models\Realty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function addLike(Request $request, $feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)
            ->where('feedback_id', $feedback->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'feedback_id' => $feedback->id
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $feedback->likes()->count()
        ]);
    }
}
