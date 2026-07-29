---
key: bausteine-styleguide
slug: content-blocks-at-a-glance
title: Every content block at a glance
teaser: >-
  This article exists to show each available content block once, for real. It doubles as a
  reference while writing and as a check that the design holds up everywhere.
published_at: 2026-07-28
published: false
author: sebastian.buergin@codebar.ch
hero: images/templates/cover-template.jpg
hero_alt: Placeholder graphic in a 3:1 aspect ratio
hero_caption: The lead image sits at the same width as everything else on the page.
tags: [Styleguide, Redaktion]
featured: false
---

The opening paragraph sets the scene. The whole site uses a single typeface — Poppins —
set a little larger and more open inside an article. **Bold text** and *italics* work as expected, as do
[links](https://www.codebar.ch) and `inline code`.

## A second-level heading

Section headings differ from the body copy by size and weight, not by a second typeface.

### A third-level heading

Sub-sections appear indented and unnumbered in the table of contents.

- A list item.
- A second item, long enough to run past a single line and show the leading inside one
  item.
    - A nested item.
    - And another.

1. Numbered works too.
2. Second item.

## Images

Every element shares one width — images start and end where the body copy starts
and ends:

:::figure{src="images/templates/cover-template.jpg" width="text" alt="Placeholder at text width"}
A caption sits centred beneath the image.
:::

`width="wide"` remains valid, but no longer changes the width:

:::figure{src="images/templates/cover-template.jpg" width="wide" alt="Placeholder at breakout width"}
The same width as the image above.
:::

## Gallery and comparison

:::gallery{cols="3" caption="A gallery of three images."}
- src: images/templates/cover-template.jpg
  alt: First image
  caption: First image
- src: images/templates/cover-template.jpg
  alt: Second image
  caption: Second image
- src: images/templates/cover-template.jpg
  alt: Third image
  caption: Third image
:::

:::compare{caption="Two states side by side."}
- src: images/templates/cover-template.jpg
  alt: State before
  caption: Before · 412,000 documents
- src: images/templates/cover-template.jpg
  alt: State after
  caption: After · 268,000 documents
:::

## Quote

:::quote{cite="Sebastian Bürgin-Fix"}
The migration is never the problem. The data is.
:::

## Callouts

:::callout{type="info"}
A neutral note for background that should not interrupt the reading flow.
:::

:::callout{type="tip" title="From practice"}
A tip taken from a real project.
:::

:::callout{type="warning" title="Before the first run"}
A complete, restorable backup — and a documented test of the restore. A backup that has
never been restored is an assumption.
:::

:::callout{type="summary"}
A summary closing a longer section.
:::

## Step by step

:::steps
- title: Take inventory
  body: Record every repository, count volume and file types, review access over the last 24 months.
- title: Agree the rules
  body: Decide per document type — migrate, archive or discard. In writing, with a named owner.
- title: Run a pilot
  body: Migrate ten percent of the stock and check it against the rules before the rest follows.
- title: Document the reconciliation
  body: Put the counts before and after side by side, and be able to explain the difference.
:::

## Code

```sql
SELECT checksum, COUNT(*) AS copies
FROM documents
WHERE archived_at IS NOT NULL
GROUP BY checksum
HAVING COUNT(*) > 1
ORDER BY copies DESC;
```

## Table

| Category | Share | Treatment |
| --- | --- | --- |
| Actively used (24 months) | 12 % | Migrate directly |
| Subject to retention | 41 % | Migrate with a retention period |
| Duplicates | 19 % | Merge |
| No legal basis | 28 % | Discard once approved |

## Closing

That is every block once through. Anyone who needs a new one adds it to
`app/Markdown/NewsMarkdown.php` and drops the template into `resources/views/markdown/`.
