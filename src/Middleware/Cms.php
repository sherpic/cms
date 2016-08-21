<?php

namespace Soda\Cms\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Models\Page;
use Soda\Cms\Models\PageType;

class Cms {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        //this is a work around for a laravel bug - the guard flicks back to the default when run through an auth Gate
        //so we need to temporarily set the guard to the incomming guard here instead.
        Block::disableDrafts();
        BlockType::disableDrafts();
        Page::disableDrafts();
        PageType::disableDrafts();

        return $next($request);
    }
}
