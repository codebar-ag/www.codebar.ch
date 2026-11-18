<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OdooPriceSource
{
    /**
     * @return array<string, int>
     */
    public function license(): array
    {
        $text = $this->text($this->get(config()->string('odoo-pricing.sources.license')));

        if (preg_match('/Custom(?:(?!Light).){0,200}?(\d{1,3},\d{2})\D+(\d{1,3},\d{2})/su', $text, $custom) !== 1) {
            throw new RuntimeException('The Custom plan prices were not found on the Odoo pricing page.');
        }

        if (preg_match('/Light\D{0,60}?(\d{1,3},\d{2})/su', $text, $light) !== 1) {
            throw new RuntimeException('The light user price was not found on the Odoo pricing page.');
        }

        return [
            'license.full.yearly' => $this->cents($custom[1]),
            'license.full.monthly' => $this->cents($custom[2]),
            'license.light.yearly' => $this->cents($light[1]),
        ];
    }

    /**
     * @return array<string, int>
     */
    public function hosting(): array
    {
        $url = config()->string('odoo-pricing.sources.hosting');
        $page = $this->get($url);

        preg_match_all('/<script[^>]+src="([^"]+\.js[^"]*)"/i', $page, $scripts);

        foreach ($scripts[1] as $source) {
            $script = $this->get($this->absolute($url, $source));

            if (preg_match("/'€':\{'workers':([\d.]+),'hosting':[\d.]+,'staging':([\d.]+),'storage':([\d.]+)/u", $script, $prices) !== 1) {
                continue;
            }

            if (preg_match("/'yearly':([\d.]+)/", $script, $factor) !== 1) {
                continue;
            }

            return [
                'hosting.worker' => $this->cents($prices[1]),
                'hosting.staging' => $this->cents($prices[2]),
                'hosting.storage_gb' => $this->cents($prices[3]),
                'hosting.factor.yearly' => (int) round((float) $factor[1] * 100),
            ];
        }

        throw new RuntimeException('The Odoo.sh price table was not found in the scripts of the pricing page.');
    }

    private function get(string $url): string
    {
        $response = Http::timeout(20)->withUserAgent('Mozilla/5.0 (compatible; price-check)')->get($url);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf('%s answered with status %d.', $url, $response->status()));
        }

        return $response->body();
    }

    private function text(string $html): string
    {
        $withoutCode = (string) preg_replace('#<(script|style)\b.*?</\1>#si', ' ', $html);
        $text = html_entity_decode(strip_tags((string) preg_replace('/<[^>]+>/', ' ', $withoutCode)), ENT_QUOTES | ENT_HTML5);

        return (string) preg_replace('/\s+/u', ' ', $text);
    }

    private function cents(string $amount): int
    {
        return (int) round((float) str_replace(',', '.', $amount) * 100);
    }

    private function absolute(string $base, string $path): string
    {
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        $parts = parse_url($base);

        return ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '').'/'.ltrim($path, '/');
    }
}
