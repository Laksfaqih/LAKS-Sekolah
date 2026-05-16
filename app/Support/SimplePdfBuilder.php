<?php

namespace App\Support;

class SimplePdfBuilder
{
    private const PAGE_WIDTH = 595;

    private const PAGE_HEIGHT = 842;

    private const LEFT_MARGIN = 40;

    private const TOP_MARGIN = 48;

    private const TITLE_FONT_SIZE = 14;

    private const BODY_FONT_SIZE = 9;

    private const LINE_HEIGHT = 13;

    private const MAX_CONTENT_LINES = 52;

    /**
     * @param  list<string>  $lines
     */
    public static function make(string $title, array $lines): string
    {
        return self::buildPdf(
            collect(array_chunk($lines, 42))
                ->map(function (array $pageLines) use ($title): array {
                    return array_merge([$title, ''], $pageLines);
                })
                ->all(),
        );
    }

    /**
     * @param  list<string>  $metaLines
     * @param  list<string>  $headers
     * @param  list<list<string>>  $rows
     */
    public static function makeTable(
        string $title,
        array $metaLines,
        string $subtitle,
        array $headers,
        array $rows,
    ): string {
        $columnWidths = self::columnWidths(count($headers));
        $tableWidth = array_sum($columnWidths) + (count($columnWidths) * 3) + 1;
        $pages = [];
        $currentPage = self::pageHeaderLines($title, $metaLines, $subtitle, $tableWidth);
        $tableHeader = self::tableHeaderLines($headers, $columnWidths);

        $currentPage = array_merge($currentPage, $tableHeader);

        if ($rows === []) {
            $emptyRow = self::rowLines(
                array_fill(0, count($headers), ''),
                $columnWidths,
                'Belum ada data.'
            );

            $currentPage = array_merge($currentPage, $emptyRow, [self::borderLine($columnWidths)]);
            $pages[] = $currentPage;

            return self::buildPdf($pages);
        }

        foreach ($rows as $row) {
            $rowLines = self::rowLines($row, $columnWidths);

            if (count($currentPage) + count($rowLines) + 1 > self::MAX_CONTENT_LINES) {
                $currentPage[] = self::borderLine($columnWidths);
                $pages[] = $currentPage;
                $currentPage = array_merge(
                    self::pageHeaderLines($title, $metaLines, $subtitle, $tableWidth),
                    $tableHeader,
                    $rowLines,
                );

                continue;
            }

            $currentPage = array_merge($currentPage, $rowLines);
        }

        $currentPage[] = self::borderLine($columnWidths);
        $pages[] = $currentPage;

        return self::buildPdf($pages);
    }

    /**
     * @param  list<list<string>>  $pages
     */
    private static function buildPdf(array $pages): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '<< /Type /Pages /Kids ['.collect($pages)->keys()->map(fn (int $index) => (5 + ($index * 2)).' 0 R')->implode(' ').'] /Count '.count($pages).' >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>',
        ];

        foreach ($pages as $index => $pageLines) {
            $pageObject = 5 + ($index * 2);
            $contentObject = $pageObject + 1;
            $content = self::pageContent($pageLines);

            $objects[$pageObject] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 '.self::PAGE_WIDTH.' '.self::PAGE_HEIGHT."] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents {$contentObject} 0 R >>";
            $objects[$contentObject] = '<< /Length '.strlen($content)." >>\nstream\n{$content}\nendstream";
        }

        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $id => $object) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$object}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$i])."\n";
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    /**
     * @param  list<string>  $lines
     */
    private static function pageContent(array $lines): string
    {
        $content = "BT\n";
        $content .= '/F1 '.self::TITLE_FONT_SIZE." Tf\n";
        $content .= self::LEFT_MARGIN.' '.(self::PAGE_HEIGHT - self::TOP_MARGIN)." Td\n";
        $content .= self::text(array_shift($lines) ?? '')." Tj\n";
        $content .= '/F2 '.self::BODY_FONT_SIZE." Tf\n";
        $content .= '0 -'.(self::LINE_HEIGHT + 6)." Td\n";

        foreach ($lines as $line) {
            $content .= self::text($line)." Tj\n";
            $content .= '0 -'.self::LINE_HEIGHT." Td\n";
        }

        return $content.'ET';
    }

    /**
     * @param  list<string>  $metaLines
     * @return list<string>
     */
    private static function pageHeaderLines(string $title, array $metaLines, string $subtitle, int $tableWidth): array
    {
        $lines = [''];

        foreach ($metaLines as $line) {
            foreach (self::wrapText($line, $tableWidth) as $wrappedLine) {
                $lines[] = $wrappedLine;
            }
        }

        if ($subtitle !== '') {
            foreach (self::wrapText($subtitle, $tableWidth) as $wrappedLine) {
                $lines[] = $wrappedLine;
            }
        }

        $lines[] = '';
        $lines[] = str_repeat('=', $tableWidth);

        array_unshift($lines, $title);

        return $lines;
    }

    /**
     * @param  list<string>  $headers
     * @param  list<int>  $columnWidths
     * @return list<string>
     */
    private static function tableHeaderLines(array $headers, array $columnWidths): array
    {
        return [
            self::borderLine($columnWidths),
            self::formatRow($headers, $columnWidths),
            self::borderLine($columnWidths),
        ];
    }

    /**
     * @param  list<string>  $cells
     * @param  list<int>  $columnWidths
     * @return list<string>
     */
    private static function rowLines(array $cells, array $columnWidths, ?string $emptyMessage = null): array
    {
        if ($emptyMessage !== null) {
            $fullWidth = array_sum($columnWidths) + ((count($columnWidths) - 1) * 3);

            return [
                '| '.str_pad(self::truncate($emptyMessage, $fullWidth), $fullWidth).' |',
            ];
        }

        $wrappedCells = [];
        $maxLines = 1;

        foreach ($columnWidths as $index => $width) {
            $cellLines = self::wrapText((string) ($cells[$index] ?? ''), $width);
            $wrappedCells[$index] = $cellLines;
            $maxLines = max($maxLines, count($cellLines));
        }

        $lines = [];

        for ($lineIndex = 0; $lineIndex < $maxLines; $lineIndex++) {
            $lineCells = [];

            foreach ($columnWidths as $columnIndex => $width) {
                $lineCells[] = $wrappedCells[$columnIndex][$lineIndex] ?? '';
            }

            $lines[] = self::formatRow($lineCells, $columnWidths);
        }

        $lines[] = self::borderLine($columnWidths);

        return $lines;
    }

    /**
     * @param  list<string>  $cells
     * @param  list<int>  $columnWidths
     */
    private static function formatRow(array $cells, array $columnWidths): string
    {
        $parts = [];

        foreach ($columnWidths as $index => $width) {
            $parts[] = str_pad(self::truncate((string) ($cells[$index] ?? ''), $width), $width);
        }

        return '| '.implode(' | ', $parts).' |';
    }

    /**
     * @param  list<int>  $columnWidths
     */
    private static function borderLine(array $columnWidths): string
    {
        return '+-'.collect($columnWidths)->map(fn (int $width) => str_repeat('-', $width))->implode('-+-').'-+';
    }

    /**
     * @return list<int>
     */
    private static function columnWidths(int $columnCount): array
    {
        return match ($columnCount) {
            5 => [8, 12, 16, 20, 12],
            6 => [10, 14, 8, 14, 10, 14],
            default => array_fill(0, $columnCount, max(8, (int) floor(86 / max($columnCount, 1)))),
        };
    }

    /**
     * @return list<string>
     */
    private static function wrapText(string $text, int $width): array
    {
        $normalized = preg_replace('/\s+/', ' ', trim($text)) ?? '';

        if ($normalized === '') {
            return [''];
        }

        $words = explode(' ', $normalized);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            if (strlen($word) > $width) {
                if ($current !== '') {
                    $lines[] = $current;
                    $current = '';
                }

                foreach (str_split($word, $width) as $chunk) {
                    $lines[] = $chunk;
                }

                continue;
            }

            $candidate = $current === '' ? $word : $current.' '.$word;

            if (strlen($candidate) <= $width) {
                $current = $candidate;

                continue;
            }

            $lines[] = $current;
            $current = $word;
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        return $lines === [] ? [''] : $lines;
    }

    private static function truncate(string $text, int $width): string
    {
        if (strlen($text) <= $width) {
            return $text;
        }

        if ($width <= 3) {
            return substr($text, 0, $width);
        }

        return substr($text, 0, $width - 3).'...';
    }

    private static function text(string $text): string
    {
        $sanitized = str_replace(["\r", "\n", "\t"], ' ', $text);
        $escaped = str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $sanitized);

        return "({$escaped})";
    }
}
