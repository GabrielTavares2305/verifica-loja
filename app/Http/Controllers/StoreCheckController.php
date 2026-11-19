<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckStoreRequest;
use App\Services\DomainExtractor;

class StoreCheckController extends Controller
{
    public function __construct(private DomainExtractor $extractor) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(CheckStoreRequest $request): string
    {
        $url = $request->validated('url');
        $domain = $this->extractor->extract($url);

        return 'Domínio: '.$domain;
    }
}
