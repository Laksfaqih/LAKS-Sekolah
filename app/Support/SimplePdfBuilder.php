<?php

namespace App\Support;

class SimplePdfBuilder
{
    /**
     * @param  list<string>  $lines
     */
    public static function make(string $title, array $lines): string
    {
        $pages = collect(array_chunk($lines, 42))
            ->map(function (array $pageLines) use ($title): string {
                $content = "BT\n/F1 16 Tf\n50 800 Td\n".self::text($title)." Tj\n";
                $content .= "/F1 10 Tf\n0 -22 Td\n";

                foreach ($pageLines as $line) {
                    $content .= self::text($line)." Tj\n0 -14 Td\n";
                }

                return $content."ET";
            })
            ->all();

        $objects = [
            1 => "<< /Type /Catalog /Pages 2 0 R >>",
            2 => '<< /Type /Pages /Kids ['.collect($pages)->keys()->map(fn (int $index) => (4 + ($index * 2))." 0 R")->implode(' ').'] /Count '.count($pages).' >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        foreach ($pages as $index => $content) {
            $pageObject = 4 + ($index * 2);
            $contentObject = $pageObject + 1;

            $objects[$pageObject] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents {$contentObject} 0 R >>";
            $objects[$contentObject] = "<< /Length ".strlen($content)." >>\nstream\n{$content}\nendstream";
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

    private static function text(string $text): string
    {
        $escaped = str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $text);

        return "({$escaped})";
    }
}
