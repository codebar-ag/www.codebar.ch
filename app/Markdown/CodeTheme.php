<?php

declare(strict_types=1);

namespace App\Markdown;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;
use Tempest\Highlight\Themes\EscapesWebTheme;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\WebTheme;

/**
 * The look of a code block in an article: CssTheme's token classes, wrapped in the
 * frame that carries the language label and the copy button.
 *
 * The wrapper sits outside the <pre> on purpose — <pre> is the scroll container, and
 * a button positioned inside it scrolls away with the first long line. Both labels
 * are rendered server-side and read back by Alpine, because a translated string
 * belongs in lang/, not in a bundle.
 */
final readonly class CodeTheme implements WebTheme
{
    use EscapesWebTheme;

    private CssTheme $tokens;

    public function __construct()
    {
        $this->tokens = new CssTheme;
    }

    public function before(TokenType $tokenType): string
    {
        return $this->tokens->before($tokenType);
    }

    public function after(TokenType $tokenType): string
    {
        return $this->tokens->after($tokenType);
    }

    public function preBefore(Highlighter $highlighter): string
    {
        $language = $highlighter->getCurrentLanguage()?->getName() ?? 'txt';

        return '<div class="news-code" x-data="codeBlock"'
            .' data-label-copy="'.e(__('Copy')).'"'
            .' data-label-copied="'.e(__('Copied')).'">'
            .'<span class="news-code__lang" aria-hidden="true">'.e($language).'</span>'
            .'<pre data-lang="'.e($language).'" class="notranslate" x-ref="code">';
    }

    public function preAfter(Highlighter $highlighter): string
    {
        // The label is in the markup, not only in x-text, so the button reads correctly
        // before Alpine boots. Without JavaScript it stays a button that does nothing —
        // hence hidden until the component initialises.
        return '</pre>'
            .'<button type="button" class="news-code__copy" hidden x-ref="button"'
            .' x-on:click="copy" x-bind:class="stateClass" aria-live="polite">'
            .'<span x-text="label">'.e(__('Copy')).'</span>'
            .'</button>'
            .'</div>';
    }
}
