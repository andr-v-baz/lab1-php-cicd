<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;

class LanguageWebController extends Controller
{
    public function index(): View
    {
        $languages = Language::whereIn('code', ['en', 'uk'])
            ->orderByRaw("CASE WHEN code = 'en' THEN 1 ELSE 2 END")
            ->get();

        return view('languages.index', compact('languages'));
    }
}
