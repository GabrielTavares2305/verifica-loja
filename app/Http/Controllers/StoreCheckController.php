<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreCheckController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): string
    {
        return 'URL recebida: '.$request->input('url');
    }
}
