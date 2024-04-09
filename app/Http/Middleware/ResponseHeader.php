<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		$response = $next($request);

		$response->headers->set('X-Frame-Options', 'DENY');

		$referer = $request->headers->get('referer');
		
		$url = parse_url($referer);

		if (!empty($url['scheme']) && !empty($url['host'])) {
			$path  = !empty($url['path'])  ? $url['path'] : '';
			$query = !empty($url['query']) ?  urlencode('?' . $url['query']) : '';
			
			$referer = $url['scheme'] . '://' . $url['host'] . $path . $query;

		} else {
			$referer = urlencode($referer);
		}

		$request->headers->set('referer' ,$referer);


		return $response;
    }
}
