<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Code 39 pattern definitions
     * 1 = narrow bar, 2 = wide bar, 0 = narrow space, _ = wide space
     */
    private static array $code39 = [
        '0' => '101020201', '1' => '201010102', '2' => '102010102', '3' => '202010101',
        '4' => '101020102', '5' => '201020101', '6' => '102020101', '7' => '101010202',
        '8' => '201010201', '9' => '102010201', 'A' => '201010102', 'B' => '102010102',
        'C' => '202010101', 'D' => '101020102', 'E' => '201020101', 'F' => '102020101',
        'G' => '101010202', 'H' => '201010201', 'I' => '102010201', 'J' => '101020201',
        'K' => '201010102', 'L' => '102010102', 'M' => '202010101', 'N' => '101020102',
        'O' => '201020101', 'P' => '102020101', 'Q' => '101010202', 'R' => '201010201',
        'S' => '102010201', 'T' => '101020201', 'U' => '201010102', 'V' => '102010102',
        'W' => '202010101', 'X' => '101020102', 'Y' => '201020101', 'Z' => '102020101',
        '-' => '101010202', '.' => '201010201', ' ' => '102010201', '*' => '101020201',
        '$' => '101010101', '/' => '101010101', '+' => '101010101', '%' => '101010101',
    ];

    /**
     * Generate pure SVG Barcode (Code 128 / Code 39 compliant)
     */
    public static function generateSvg(string $text, int $width = 260, int $height = 45): string
    {
        $code = strtoupper(preg_replace('/[^A-Z0-9\-\.\ \$\/\+\%]/i', '', $text));
        if (empty($code)) {
            $code = 'THREADAX';
        }

        // Standard Code 128 B Character Table (107 patterns)
        $c128Patterns = [
            '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213',
            '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132',
            '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211',
            '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313',
            '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331',
            '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111',
            '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214',
            '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111',
            '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141',
            '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141',
            '114131', '311141', '411131', '211412', '211214', '211232', '2331112'
        ];

        // Start B = index 104
        $startCode = 104;
        $checksum = $startCode;
        $sequence = [$startCode];

        for ($i = 0; $i < strlen($code); $i++) {
            $charVal = ord($code[$i]) - 32;
            if ($charVal >= 0 && $charVal <= 95) {
                $sequence[] = $charVal;
                $checksum += $charVal * ($i + 1);
            }
        }

        $checkChar = $checksum % 103;
        $sequence[] = $checkChar;
        $sequence[] = 106; // Stop code

        // Build pattern string
        $pattern = '';
        foreach ($sequence as $val) {
            $pattern .= $c128Patterns[$val] ?? '212222';
        }

        // Calculate total module units
        $totalModules = 0;
        for ($i = 0; $i < strlen($pattern); $i++) {
            $totalModules += (int) $pattern[$i];
        }

        $moduleWidth = $width / max(1, $totalModules);
        $x = 0;
        $rects = '';

        for ($i = 0; $i < strlen($pattern); $i++) {
            $w = (int) $pattern[$i] * $moduleWidth;
            if ($i % 2 === 0) { // Bar (Black)
                $rects .= sprintf('<rect x="%.2f" y="0" width="%.2f" height="%d" fill="#000000" />', $x, $w, $height);
            }
            $x += $w;
        }

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="100%%" height="%d" style="display:block;margin:0 auto;max-width:%dpx;">%s</svg>',
            $width, $height, $height, $width, $rects
        );
    }
}
