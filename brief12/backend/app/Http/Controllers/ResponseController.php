<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ResponseController extends Controller
{
    public function store(Request $request, Question $question): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['question_id'] = $question->id;

        $response = Response::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Réponse ajoutée avec succès!',
            'data' => $response->load('user:id,name'),
        ], 201);
    }

    public function destroy(Response $response): JsonResponse
    {
        $user = Auth::user();
        if ($response->user_id !== Auth::id() && !($user instanceof User && $user->isAdmin())) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé.',
            ], 403);
        }

        $response->delete();

        return response()->json([
            'success' => true,
            'message' => 'Réponse supprimée avec succès!',
        ]);
    }
}
