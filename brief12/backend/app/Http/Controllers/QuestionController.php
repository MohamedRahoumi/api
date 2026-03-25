<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['user:id,name', 'responses', 'likes']);

        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        $questions = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Questions recuperees avec succes.',
            'data' => $questions,
        ]);
    }

    public function show($id)
    {
        $question = Question::with(['user:id,name', 'responses.user:id,name', 'likes', 'favorites'])->findOrFail($id);


        $question->incrementViews();

        return response()->json([
            'success' => true,
            'message' => 'Question recuperee avec succes.',
            'data' => $question,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $question = Question::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user()->id,
            'views' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question creee avec succes.',
            'data' => $question,
        ], 201);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);


        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question supprimee avec succes.',
        ]);
    }
}
