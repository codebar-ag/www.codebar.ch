<?php

declare(strict_types=1);

use App\Markdown\NewsMarkdown;

function markdown(): NewsMarkdown
{
    return new NewsMarkdown;
}

it('escapes raw html in an article body', function () {
    // Article files are content written by editors, not templates. A stray tag
    // must render as text rather than execute.
    $html = markdown()->toHtml('Ein Satz mit <script>alert(1)</script> darin.');

    expect($html)->toContain('&lt;script&gt;');
    expect($html)->not->toContain('<script>');
})->group('unit', 'markdown');

it('renders a figure directive as a figure element', function () {
    $html = markdown()->toHtml(<<<'MD'
        :::figure{src="/images/templates/cover-template.jpg" width="wide" alt="Ein Bild"}
        Die Bildlegende
        :::
        MD);

    expect($html)
        ->toContain('<figure')
        ->toContain('news-block--wide')
        ->toContain('alt="Ein Bild"')
        ->toContain('Die Bildlegende');
})->group('unit', 'markdown');

it('renders a steps directive from its yaml body', function () {
    $html = markdown()->toHtml(<<<'MD'
        :::steps
        - title: Inventar erstellen
          body: Alle Ablagen erfassen.
        - title: Regeln festlegen
          body: Pro Dokumentart entscheiden.
        :::
        MD);

    expect($html)
        ->toContain('news-steps')
        ->toContain('Inventar erstellen')
        ->toContain('Pro Dokumentart entscheiden.');
})->group('unit', 'markdown');

it('renders a callout with the type it was given', function () {
    $html = markdown()->toHtml(<<<'MD'
        :::callout{type="warning" title="Achtung"}
        Vorher ein Backup ziehen.
        :::
        MD);

    expect($html)
        ->toContain('news-callout')
        ->toContain('amber')
        ->toContain('Achtung');
})->group('unit', 'markdown');

it('keeps the text of an unknown directive instead of dropping it', function () {
    // A typo in a directive name must not silently swallow a paragraph.
    $html = markdown()->toHtml(<<<'MD'
        :::figrue{src="x"}
        Wichtiger Text
        :::
        MD);

    expect($html)->toContain('Wichtiger Text');
})->group('unit', 'markdown');

it('gives headings anchors that match the table of contents', function () {
    $source = <<<'MD'
        ## Erster Abschnitt

        Text.

        ### Unterabschnitt

        Text.
        MD;

    $headings = markdown()->headings($source);
    $html = markdown()->toHtml($source);

    expect($headings)->toHaveCount(2)
        ->and($headings[0]['id'])->toBe('erster-abschnitt')
        ->and($headings[0]['level'])->toBe(2)
        ->and($headings[1]['level'])->toBe(3);

    foreach ($headings as $heading) {
        expect($html)->toContain('id="'.$heading['id'].'"');
    }
})->group('unit', 'markdown');

it('disambiguates two identical headings', function () {
    $source = "## Fazit\n\nA\n\n## Fazit\n\nB";

    $headings = markdown()->headings($source);
    $html = markdown()->toHtml($source);

    expect($headings[0]['id'])->toBe('fazit')
        ->and($headings[1]['id'])->toBe('fazit-2')
        ->and($html)->toContain('id="fazit"')
        ->and($html)->toContain('id="fazit-2"');
})->group('unit', 'markdown');

it('estimates reading time from the word count', function () {
    $short = markdown()->readingMinutes('Nur ein paar Worte.');
    $long = markdown()->readingMinutes(str_repeat('Wort ', 1000));

    expect($short)->toBe(1)
        ->and($long)->toBe(5);
})->group('unit', 'markdown');
