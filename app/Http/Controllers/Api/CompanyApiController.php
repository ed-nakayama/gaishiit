<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * salesforce_id をキーに、対応する id（企業ID）だけを返すAPI。
 *
 * 認証は共有シークレットキー方式（ヘッダー X-Api-Key で照合）。
 *
 * ルーティング例（routes/api.php）:
 *   Route::get('/companies/lookup-id', [CompanyApiController::class, 'lookupId']);
 */
class CompanyApiController extends Controller
{
    /**
     * GET /api/companies/lookup-id?salesforce_id=xxx
     * -> {"id": "12345"} または 404
     */
    public function lookupId(Request $request): JsonResponse
    {
        if (! $this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $salesforceId = $request->query('salesforce_id');

        if (empty($salesforceId)) {
            return response()->json(['error' => 'salesforce_id is required'], 422);
        }

        $company = Company::where('salesforce_id', $salesforceId)->first(['id']);

        if (! $company) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        return response()->json(['id' => $company->id]);
    }

    /**
     * GET /api/companies/open-flag?id=xxx
     * -> {"open_flag": 1} または 404
     */
    public function openFlag(Request $request): JsonResponse
    {
        if (! $this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id = $request->query('id');

        if (empty($id)) {
            return response()->json(['error' => 'id is required'], 422);
        }

        $company = Company::where('id', $id)->first(['open_flag']);

        if (! $company) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        return response()->json(['open_flag' => (int) $company->open_flag]);
    }

    private function isAuthorized(Request $request): bool
    {
        $key = $request->header('X-Api-Key');

        return $key !== null && hash_equals((string) config('services.company_api.key'), (string) $key);
    }
}
