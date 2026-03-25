<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Question $question): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifie.',
            ], 401);
        }

        $like = Like::where('user_id', $user->id)
                    ->where('question_id', $question->id)
                    ->first();

        if ($like) {
            // Si le like existe, on le supprime (unlike)
            $like->delete();
            $liked = false;
        } else {
            // Sinon, on crée un nouveau like
            Like::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'message' => $liked ? 'Like ajouté!' : 'Like supprimé!',
            'data' => [
                'liked' => $liked,
                'likes_count' => $question->likes()->count(),
            ],
        ]);
    }
}
