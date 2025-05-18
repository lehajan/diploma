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

        return response()->json(['message' => 'отзыв создан!']);
    }

    public function delete(Feedback $feedback)
    {
        if (Auth::id() !== $feedback->user_id){
            return response()->json('У вас нет прав для удаления этого отзыва');
        }

        $feedback->delete();
        return response()->json(['message' => 'отзыв удален!']);
    }

    public function outputFeedback(Request $request, $realtyId)
    {
        // Валидация что realtyId существует
        $request->validate([
            'realty_id' => 'sometimes|integer|exists:realties,id',
            'sort' => 'sometimes|string|in:date,rating' // новый параметр сортировки
        ]);

        $sort = $request->input('sort', 'date'); // по умолчанию сортировка по дате

        $query = Feedback::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->where('realty_id', $realtyId);

        // Применяем сортировку
        if ($sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $feedbacks = $query->get();

        $formattedFeedbacks = $feedbacks->map(function($feedback) {
            return [
                'id' => $feedback->id,
                'user' => [
                    'id' => $feedback->user->id,
                    'name' => $feedback->user->name,
                ],
                'rating' => $feedback->rating,
                'comment' => $feedback->comment,
                'created_at' => $feedback->created_at->format('d.m.Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'feedbacks' => $formattedFeedbacks,
            'average_rating' => $feedbacks->avg('rating') ?? 0,
            'total' => $feedbacks->count()
        ]);
    }
}
