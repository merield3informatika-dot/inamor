<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class MobileAccountController extends Controller
{
    public function index(Request $request): View
    {
        return view('mobile.account', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('mobile.account-edit', [
            'user' => $request->user(),
        ]);
    }
}