<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Smalot\PdfParser\Parser as PdfParser;

/**
 * アップロードされた職務経歴ファイル(Word/Excel/PDF)からプレーンテキストを抽出する。
 *
 * 必要なライブラリ(未導入の場合はサーバーで以下を実行してください):
 *   composer require phpoffice/phpword smalot/pdfparser
 * (maatwebsite/excel は導入済みの前提)
 */
class CareerFileTextExtractorService
{
    public const ALLOWED_EXTENSIONS = ['docx', 'xlsx', 'xls', 'pdf'];

    // Salesforce(Apex)側の同期コールアウトは6MBまでという制約があるため、
    // base64化後のサイズがそれを超えないよう、原本ファイルは3MBまでに制限する。
    public const MAX_FILE_SIZE_BYTES = 3 * 1024 * 1024;

    public function extract(string $tmpFilePath, string $originalFileName): string
    {
        $extension = strtolower((string) pathinfo($originalFileName, PATHINFO_EXTENSION));

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new \InvalidArgumentException(
                '対応していないファイル形式です（対応形式: ' . implode('/', self::ALLOWED_EXTENSIONS) . '）。'
            );
        }

        if (!is_file($tmpFilePath) || filesize($tmpFilePath) === 0) {
            throw new \InvalidArgumentException('ファイルの読み込みに失敗しました。');
        }

        if (filesize($tmpFilePath) > self::MAX_FILE_SIZE_BYTES) {
            $limitMb = self::MAX_FILE_SIZE_BYTES / 1024 / 1024;
            throw new \InvalidArgumentException("ファイルサイズが上限（{$limitMb}MB）を超えています。");
        }

        $text = match ($extension) {
            'docx' => $this->extractFromWord($tmpFilePath),
            'xlsx', 'xls' => $this->extractFromExcel($tmpFilePath),
            'pdf' => $this->extractFromPdf($tmpFilePath),
            default => '',
        };

        $text = trim($text);
        if ($text === '') {
            throw new \RuntimeException(
                'ファイルからテキストを抽出できませんでした（画像のみのPDF等の可能性があります）。'
            );
        }

        return $text;
    }

    private function extractFromWord(string $path): string
    {
        // 古い .doc(バイナリ形式) は非対応。.docx のみサポート。
        $phpWord = WordIOFactory::load($path, 'Word2007');

        $lines = [];
        foreach ($phpWord->getSections() as $section) {
            $lines[] = $this->extractContainerText($section);
        }

        return implode("\n", array_filter($lines, fn ($l) => $l !== ''));
    }

    private function extractContainerText(AbstractContainer $container): string
    {
        $lines = [];
        foreach ($container->getElements() as $element) {
            if ($element instanceof Text) {
                $lines[] = $element->getText();
            } elseif ($element instanceof AbstractContainer) {
                $lines[] = $this->extractContainerText($element);
            }
        }

        return implode("\n", array_filter($lines, fn ($l) => $l !== ''));
    }

    private function extractFromExcel(string $path): string
    {
        $sheets = Excel::toArray(null, $path);

        $lines = [];
        foreach ($sheets as $rows) {
            foreach ($rows as $row) {
                $cells = array_map(fn ($cell) => trim((string) ($cell ?? '')), $row);
                $cells = array_filter($cells, fn ($c) => $c !== '');
                if (!empty($cells)) {
                    $lines[] = implode(' ', $cells);
                }
            }
        }

        return implode("\n", $lines);
    }

    private function extractFromPdf(string $path): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($path);

        return $pdf->getText();
    }
}
