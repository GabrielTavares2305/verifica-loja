<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckStoreRequest;

class StoreCheckController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CheckStoreRequest $request): string
    {
        return 'URL recebida: '.$request->validated('url');
    }
}
