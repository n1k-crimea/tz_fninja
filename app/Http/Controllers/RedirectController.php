<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __invoke(Request $request, string $code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        $link->clicks()->create([
            'ip_address' => $request->ip(),
        ]);

        return redirect()->away($link->original_url);
    }
}
