<?php

namespace App\Services;

use Illuminate\Support\Str;
use InvalidArgumentException;

class DomainExtractor
{
    /**
     * Extract the normalized domain from a URL.
     *
     * @throws InvalidArgumentException
     */
    public function extract(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (! $host) {
            throw new InvalidArgumentException('Não foi possivel extrair o dominio da URL: '.$url);
        }
        $lower = Str::lower($host);
        $domain = Str::chopStart($lower, 'www.');

        return $domain;
    }
}
