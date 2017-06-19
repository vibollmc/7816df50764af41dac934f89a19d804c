<?php

namespace App\Http\Middleware;

use Closure, Session, Agent;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('user_agent')) {
            if (Agent::isMobile()) {
                $agent['moblie'] = true;
                $agent['device'] = 'moblie';
            }else{
                $agent['moblie'] = false;
                $agent['device'] = 'destop';
            }
            Session::set('user_agent', $agent);
        }
        return $next($request);
    }
}
