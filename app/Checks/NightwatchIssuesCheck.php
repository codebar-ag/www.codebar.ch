<?php

declare(strict_types=1);

namespace App\Checks;

use Illuminate\Support\Facades\Http;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Throwable;

/**
 * Fails while Nightwatch has an open issue for this application.
 * Nightwatch has no REST API, so this calls the list_issues tool of its MCP endpoint (one stateless JSON-RPC request).
 */
class NightwatchIssuesCheck extends Check
{
    public function run(): Result
    {
        $result = Result::make();

        try {
            $response = Http::withToken(config()->string('default.nightwatch.api_token'))
                ->accept('application/json, text/event-stream')
                ->timeout(15)
                ->post(config()->string('default.nightwatch.url').'/mcp', [
                    'jsonrpc' => '2.0',
                    'id' => 1,
                    'method' => 'tools/call',
                    'params' => [
                        'name' => 'list_issues',
                        'arguments' => array_filter([
                            'application_id' => config('default.nightwatch.application_id'),
                            'environment_id' => config('default.nightwatch.environment_id'),
                            'status' => 'open',
                        ]),
                    ],
                ]);
        } catch (Throwable $exception) {
            return $result->shortSummary('nightwatch unreachable')->failed("Nightwatch request failed: {$exception->getMessage()}");
        }

        if ($response->status() === 401) {
            return $result->shortSummary('nightwatch token invalid')->failed('Nightwatch token expired or invalid, run `php artisan nightwatch:login`.');
        }

        $text = $response->fluent()->string('result.content.0.text')->toString();

        if ($response->failed() || $response->json('result.isError') !== false || $text === '') {
            return $result->shortSummary('nightwatch error')->failed('Unexpected Nightwatch response: '.($text ?: $response->status()));
        }

        // list_issues answers with markdown, one "### #<ref> <title>" heading per issue.
        $count = preg_match_all('/^### #\d+/m', $text);

        $result->shortSummary("open nightwatch issues: {$count}");

        if ($count > 0) {
            return $result->failed();
        }

        return $result->ok();
    }
}
