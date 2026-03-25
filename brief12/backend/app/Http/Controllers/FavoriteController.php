<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        if (!($user instanceof User)) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifie.',
            ], 401);
        }

        $favorites = $user->favorites()->with(['question.user'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $favorites,
        ]);
    }

    public function toggle(Question $question): JsonResponse
    {
        $user = Auth::user();

        if (!($user instanceof User)) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifie.',
            ], 401);
        }

        $existing = Favorite::where('user_id', $user->id)
            ->where('question_id', $question->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'message' => 'Question retirée des favoris!',
                'data' => ['favorited' => false],
            ]);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question ajoutée aux favoris!',
                'data' => ['favorited' => true],
            ], 201);
        }
    }
}
