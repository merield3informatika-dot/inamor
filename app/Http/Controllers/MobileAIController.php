<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class MobileAIController extends Controller
{
    public function index(Request $request): View
    {
        return view('mobile.ai.index', [
            'user' => $request->user(),
        ]);
    }
}