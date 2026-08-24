<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CareerFileTextExtractorService;
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

    /**
     * Word(.docx) / Excel(.xlsx,.xls) / PDF でアップロードされた職務経歴ファイルから
     * テキストを抽出し、通常のテキスト版と同じレコメンドロジックにかける。
     *
     * Salesforce(Apex)側からは、ファイルをbase64文字列にしてJSONで送信する想定。
     */
    public function recommendFromFile(
        Request $request,
        JobRecommendationService $service,
        CareerFileTextExtractorService $extractor
    ) {
        $validated = $request->validate([
            'file_name' => 'required|string|max:255',
            'file_base64' => 'required|string',
            'count' => 'nullable|integer|in:' . implode(',', JobRecommendationService::ALLOWED_COUNTS),
        ]);

        $count = $validated['count'] ?? JobRecommendationService::DEFAULT_COUNT;

        $binary = base64_decode($validated['file_base64'], true);
        if ($binary === false) {
            return response()->json(['message' => 'ファイルのデコードに失敗しました。'], 400);
        }

        $extension = strtolower((string) pathinfo($validated['file_name'], PATHINFO_EXTENSION));
        $tmpPath = tempnam(sys_get_temp_dir(), 'career_');
        // 拡張子で読み込みライブラリを分岐させているため、一時ファイルにも同じ拡張子を付け直す
        $tmpPathWithExt = $tmpPath . '.' . $extension;
        rename($tmpPath, $tmpPathWithExt);
        file_put_contents($tmpPathWithExt, $binary);

        try {
            $careerText = $extractor->extract($tmpPathWithExt, $validated['file_name']);
            $recommendations = $service->recommend($careerText, $count);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        } finally {
            @unlink($tmpPathWithExt);
        }

        return response()->json(['recommendations' => $recommendations]);
    }
}
