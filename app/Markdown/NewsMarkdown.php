<?php

declare(strict_types=1);

namespace App\Markdown;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Renders an article body: GitHub-flavoured Markdown plus fenced block directives.
 *
 *     :::figure{src="…" width="wide" alt="…"}
 *     Bildlegende
 *     :::
 *
 * The directives exist so the look of a figure, a callout or a step list lives in the
 * design system rather than in hand-written HTML inside every article. Raw HTML in the
 * source is escaped — an article file is content, not a template.
 */
class NewsMarkdown
{
    /** Directives whose body is a YAML list rather than prose. */
    private const array YAML_BODY = ['gallery', 'compare', 'steps'];

    private const array KNOWN = ['figure', 'gallery', 'compare', 'quote', 'callout', 'steps', 'video'];

    public function toHtml(string $markdown): string
    {
        $segments = $this->split($markdown);

        $html = '';

        foreach ($segments as $segment) {
            $html .= $segment['type'] === 'markdown'
                ? $this->convert($segment['content'])
                : $this->renderDirective($segment['name'], $segment['attributes'], $segment['content']);
        }

        return $this->addHeadingIds($html);
    }

    /**
     * Estimated reading time. Directive bodies count too, but their fence lines do not.
     */
    public function readingMinutes(string $markdown): int
    {
        $text = preg_replace('/^:::\w*(\{[^}]*\})?[ \t]*$/m', '', $markdown) ?? $markdown;
        $words = preg_match_all('/[\p{L}\p{N}]+/u', strip_tags($this->convert($text)));

        return max(1, (int) ceil(($words ?: 0) / 200));
    }

    /**
     * Gives every h2/h3 a stable anchor so the table of contents can link to it.
     * Runs on the assembled document so duplicate headings across segments still differ.
     */
    private function addHeadingIds(string $html): string
    {
        $seen = [];

        return (string) preg_replace_callback(
            '/<h([23])>(.*?)<\/h\1>/s',
            function (array $match) use (&$seen): string {
                $id = $this->anchor(trim(strip_tags($match[2])), $seen);

                return '<h'.$match[1].' id="'.e($id).'">'.$match[2].'</h'.$match[1].'>';
            },
            $html
        );
    }

    /**
     * @param  array<string, int>  $seen
     */
    private function anchor(string $title, array &$seen): string
    {
        $id = Str::slug($title);

        if ($id === '') {
            $id = 'abschnitt';
        }

        $seen[$id] = ($seen[$id] ?? 0) + 1;

        return $seen[$id] > 1 ? $id.'-'.$seen[$id] : $id;
    }

    /**
     * Headings of level 2 and 3, for the table of contents. Anchors match toHtml().
     *
     * @return array<int, array{id: string, level: int, title: string}>
     */
    public function headings(string $markdown): array
    {
        $withoutDirectives = preg_replace('/^:::.*?^:::\s*$/ms', '', $markdown) ?? $markdown;

        preg_match_all('/^(#{2,3})\s+(.+?)\s*$/m', $withoutDirectives, $matches, PREG_SET_ORDER);

        $headings = [];
        $seen = [];

        foreach ($matches as $match) {
            $title = trim(strip_tags($this->convert($match[2])));

            $headings[] = [
                'id' => $this->anchor($title, $seen),
                'level' => strlen($match[1]),
                'title' => $title,
            ];
        }

        return $headings;
    }

    /**
     * @return array<int, array{type: string, name: string, attributes: array<string, string>, content: string}>
     */
    private function split(string $markdown): array
    {
        $pattern = '/^:::([a-z]+)[ \t]*(\{[^}]*\})?[ \t]*\R(.*?)^:::[ \t]*$/ms';

        $segments = [];
        $offset = 0;

        if (preg_match_all($pattern, $markdown, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE) === false) {
            return [['type' => 'markdown', 'name' => '', 'attributes' => [], 'content' => $markdown]];
        }

        foreach ($matches as $match) {
            [$whole, $start] = $match[0];

            $before = substr($markdown, $offset, $start - $offset);

            if (trim($before) !== '') {
                $segments[] = ['type' => 'markdown', 'name' => '', 'attributes' => [], 'content' => $before];
            }

            $name = $match[1][0];

            $segments[] = in_array($name, self::KNOWN, true)
                ? [
                    'type' => 'directive',
                    'name' => $name,
                    'attributes' => $this->parseAttributes($match[2][0]),
                    'content' => $match[3][0],
                ]
                // An unknown directive is a typo in the article, not a reason to lose the text.
                : ['type' => 'markdown', 'name' => '', 'attributes' => [], 'content' => $match[3][0]];

            $offset = $start + strlen($whole);
        }

        $rest = substr($markdown, $offset);

        if (trim($rest) !== '') {
            $segments[] = ['type' => 'markdown', 'name' => '', 'attributes' => [], 'content' => $rest];
        }

        return $segments;
    }

    /**
     * @return array<string, string>
     */
    private function parseAttributes(string $raw): array
    {
        if ($raw === '') {
            return [];
        }

        preg_match_all('/([a-z_]+)\s*=\s*"([^"]*)"/i', $raw, $matches, PREG_SET_ORDER);

        $attributes = [];

        foreach ($matches as $match) {
            $attributes[strtolower($match[1])] = $match[2];
        }

        return $attributes;
    }

    /**
     * @param  array<string, string>  $attributes
     */
    private function renderDirective(string $name, array $attributes, string $body): string
    {
        $data = [
            'attributes' => $attributes,
            'width' => $attributes['width'] ?? 'text',
        ];

        if (in_array($name, self::YAML_BODY, true)) {
            $data['items'] = $this->parseYamlList($body);
        } else {
            $data['body'] = $this->convert($body);
            $data['text'] = trim(strip_tags($this->convert($body)));
        }

        return View::make('markdown.'.$name, $data)->render();
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function parseYamlList(string $body): array
    {
        try {
            $parsed = Yaml::parse($body);
        } catch (ParseException) {
            return [];
        }

        if (! is_array($parsed)) {
            return [];
        }

        $items = [];

        foreach ($parsed as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $item = [];

            foreach ($entry as $key => $value) {
                if (is_string($key) && (is_string($value) || is_int($value))) {
                    $item[$key] = (string) $value;
                }
            }

            if ($item !== []) {
                $items[] = $item;
            }
        }

        return $items;
    }

    private function convert(string $markdown): string
    {
        $environment = (new Environment([
            // Article files are content. Anything that looks like markup in them is
            // text, not a template — this is what keeps a typo from becoming an XSS.
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]))
            ->addExtension(new CommonMarkCoreExtension)
            ->addExtension(new GithubFlavoredMarkdownExtension);

        $converter = new MarkdownConverter($environment);

        try {
            return $converter->convert($markdown)->getContent();
        } catch (CommonMarkException) {
            return '';
        }
    }
}
