<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RecordClick;
use App\Services\LinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __invoke(Request $request, string $code, LinkService $links): RedirectResponse
    {
        $link = $links->resolve($code);

        abort_if($link === null, 404);

        $request->attributes->set(RecordClick::ATTRIBUTE, $link->id);

        return redirect()->away($link->originalUrl);
    }
}
