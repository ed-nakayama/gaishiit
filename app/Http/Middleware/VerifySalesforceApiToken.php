<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SalesforceからのAPI呼び出しを共有シークレット(Bearerトークン)で認証する。
 * .envに SALESFORCE_API_TOKEN を設定し、config/const.php 等で読み出す想定。
 * Salesforce側では Named Credential の Authentication Protocol を Custom にし、
 * Authorization ヘッダーに "Bearer {同じトークン}" を設定しておく。
 */
class VerifySalesforceApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('const.SALESFORCE_API_TOKEN');
        $given = $request->bearerToken();

        if (empty($expected) || $given !== $expected) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
