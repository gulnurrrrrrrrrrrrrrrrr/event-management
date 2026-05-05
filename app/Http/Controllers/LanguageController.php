<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    
    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, ['ru', 'en', 'kk'])) {
            $locale = 'ru';
        }

        Session::put('locale', $locale);

        return redirect()->back();
    }
}