<?php

namespace App\Http\Middleware;

use App\Services\ClickService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordClick
{
    public const string ATTRIBUTE = 'recordClickForLinkId';

    public function __construct(private ClickService $clicks) {}

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $linkId = $request->attributes->get(self::ATTRIBUTE);

        if (is_int($linkId)) {
            $this->clicks->record($linkId, $request->ip());
        }
    }
}
