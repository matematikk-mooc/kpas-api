<?php

namespace App\Http\Middleware;

use App\LTI\Authenticator;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class LtiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('cookie')) {
            logger("LtiMiddleware has cookie.");
            session()->setId($request->get('cookie'));
            session()->start();
        }
        if (Arr::get(session()->get('settings'), 'custom_canvas_user_id')) {
            // custom_canvas_user_id found
            return $next($request);
        }
        if (!$this->isLtiAuthenticated()
            || ($request->has('lti_message_type') && $this->checkIds($request))
        ) {
            logger("LtiMiddleware: Trying to authenticate.");
            Authenticator::authenticate();
        }
        logger("LtiMiddleware: next request: ".$request);
        return $next($request);
    }

    protected function isLtiAuthenticated(): bool
    {
        return session()->has('settings')
            && Arr::has(session()->get('settings', []), [
                'custom_canvas_user_id',
                'custom_canvas_course_id',
                #'custom_canvas_user_login_id',
            ]);
    }

    protected function checkIds($request): bool
    {
        return
        ((Arr::get(session()->get('settings'), 'custom_canvas_user_id') !== $request->get('custom_canvas_user_id')) ||
        (Arr::get(session()->get('settings'), 'custom_canvas_course_id') !== $request->get('custom_canvas_course_id')));
    }
}
