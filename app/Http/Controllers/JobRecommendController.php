<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobRecommendationService;

class JobRecommendController extends Controller
{
    public function recommend(Request $request, JobRecommendationService $service)
    {
        $validated = $request->validate([
            'career_text' => 'required|string|max:20000',
            // 未指定時はサービス側のデフォルト(5件)を使う
            'count' => 'nullable|integer|in:' . implode(',', JobRecommendationService::ALLOWED_COUNTS),
        ]);

        $count = $validated['count'] ?? JobRecommendationService::DEFAULT_COUNT;

        try {
            $recommendations = $service->recommend($validated['career_text'], $count);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        return response()->json(['recommendations' => $recommendations]);
    }
}
