<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Bienvenue.',
        ]);
    }
}
