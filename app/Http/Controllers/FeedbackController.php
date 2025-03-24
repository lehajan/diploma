<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'realty_id' => 'required|integer|exists:realties,id',
            'rating' => 'required|numeric|min:0|max:5',
            'comment' => 'required|string',
        ]);

        $data['user_id'] = Auth::id(); //получение ID пользователя из аутентификации
        $feedback = Feedback::create($data);
        $feedback->load('user');

        return response()->json(['отзыв создан!']);
    }

    public function delete(Feedback $feedback)
    {
        if (Auth::id() !== $feedback->user_id){
            return response()->json('У вас нет прав для удаления этого отзыва');
        }

        $feedback->delete();
        return response()->json('отзыв удален!');
    }
}
