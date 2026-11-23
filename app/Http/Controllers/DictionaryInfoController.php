<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class DictionaryInfoController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'name' => 'Electronic Dictionary',
            'description' => 'Electronic dictionary for word translation',
            'supported_languages' => ['en', 'uk'],
            'version' => '1.0',
        ], 200);
    }
}