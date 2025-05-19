<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Realty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    public function addToArchive(Realty $realty, Request $request)
    {
        $user = Auth::user();

        if ($realty->user_id !== $user->id) {
            return response()->json(['error' => 'Вы не можете архивировать это объявление'], 403);
        }

        if ($realty->isArchived) {
            return response()->json(['error' => 'Объявление уже в архиве'], 400);
        }

        $archive = Archive::create([
            'realty_id' => $realty->id,
            'user_id' => $user->id
        ]);

        $realty->update(['is_archived' => true]);

        return response()->json(['message' => 'Объявление перемещено в архив']);
    }

    public function restore($realtyId)
    {
        $archive = Archive::where('realty_id', $realtyId)->firstOrFail();
        $user = Auth::user();

        $realty = $archive->realty;
        $realty->update(['is_archived' => false]);

        $archive->delete();

        return response()->json([
            'message' => 'Объявление восстановлено из архива'
        ]);
    }

    public function showArchive()
    {
        $user = Auth::user();
        $archived = Archive::with('realty')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($item) {
                return $item->realty;
            });

        return response()->json([
            'archives' => $archived
        ]);
    }
}
