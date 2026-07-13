<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrisService
{
    public function loadStaticPayloadFromFile(string $path): ?string
    {
        if (! is_file($path)) {
            return null;
        }

        $content = (string) file_get_contents($path);
        $payload = strtoupper(preg_replace('/\s+/', '', $content) ?? '');

        return $payload !== '' ? $payload : null;
    }

    public function buildDynamicPayload(string $staticPayload, float $amount): string
    {
        $payload = strtoupper(trim($staticPayload));
        $payload = preg_replace('/6304[0-9A-F]{4}$/', '', $payload) ?? $payload;

        $tokens = $this->parseTopLevelTlv($payload);
        $tokens = array_values(array_filter($tokens, fn (array $token) => $token['tag'] !== '63'));

        $tokens = $this->upsertTag($tokens, '01', '12', '00');
        $tokens = $this->upsertTag($tokens, '54', $this->formatAmount($amount), '53');

        $withoutCrc = $this->encodeTlv($tokens);
        $crc = $this->crc16CcittFalse($withoutCrc . '6304');

        return $withoutCrc . '6304' . $crc;
    }

    public function renderSvg(string $payload, int $size = 300): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($payload);
    }

    private function parseTopLevelTlv(string $payload): array
    {
        $index = 0;
        $length = strlen($payload);
        $tokens = [];

        while ($index + 4 <= $length) {
            $tag = substr($payload, $index, 2);
            $valueLength = (int) substr($payload, $index + 2, 2);
            $start = $index + 4;
            $end = $start + $valueLength;

            if ($end > $length) {
                break;
            }

            $value = substr($payload, $start, $valueLength);
            $tokens[] = [
                'tag' => $tag,
                'value' => $value,
            ];

            $index = $end;
        }

        return $tokens;
    }

    private function encodeTlv(array $tokens): string
    {
        $payload = '';

        foreach ($tokens as $token) {
            $value = (string) ($token['value'] ?? '');
            $payload .= (string) $token['tag']
                . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT)
                . $value;
        }

        return $payload;
    }

    private function upsertTag(array $tokens, string $tag, string $value, ?string $insertAfterTag = null): array
    {
        foreach ($tokens as $i => $token) {
            if (($token['tag'] ?? '') === $tag) {
                $tokens[$i]['value'] = $value;
                return $tokens;
            }
        }

        $insertIndex = count($tokens);

        if ($insertAfterTag !== null) {
            foreach ($tokens as $i => $token) {
                if (($token['tag'] ?? '') === $insertAfterTag) {
                    $insertIndex = $i + 1;
                    break;
                }
            }
        }

        array_splice($tokens, $insertIndex, 0, [[
            'tag' => $tag,
            'value' => $value,
        ]]);

        return $tokens;
    }

    private function formatAmount(float $amount): string
    {
        $formatted = number_format($amount, 2, '.', '');
        $formatted = rtrim(rtrim($formatted, '0'), '.');

        return $formatted !== '' ? $formatted : '0';
    }

    private function crc16CcittFalse(string $input): string
    {
        $crc = 0xFFFF;
        $polynomial = 0x1021;

        $bytes = unpack('C*', $input);
        if ($bytes === false) {
            return '0000';
        }

        foreach ($bytes as $byte) {
            $crc ^= ($byte << 8);

            for ($i = 0; $i < 8; $i++) {
                if (($crc & 0x8000) !== 0) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
