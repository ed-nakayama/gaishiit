<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rpa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * rpas テーブル（レコードは1件のみ）のデータを返すAPI。
 *
 * 認証は共有シークレットキー方式（ヘッダー X-Api-Key で照合。既存のCOMPANY_API_KEYと共用）。
 *
 * ルーティング例（routes/api.php）:
 *   Route::get('/rpas', [RpaApiController::class, 'show']);
 */
class RpaApiController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        if (! $this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rpa = Rpa::first();

        if (! $rpa) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        return response()->json($rpa);
    }

    private function isAuthorized(Request $request): bool
    {
        $key = $request->header('X-Api-Key');

        return $key !== null && hash_equals((string) config('services.company_api.key'), (string) $key);
    }
}
