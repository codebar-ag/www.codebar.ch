---
key: docuware-viewer-plugin
slug: how-far-can-you-customise-docuware
title: How far can you customise DocuWare?
teaser: >-
    A customer wants to keep their documents in their existing system and still work with them
    in DocuWare. We built a plugin and tested how far it gets you.
published_at: 2026-09-23
updated_at: 2026-09-23
published: true
author: sebastian.buergin@codebar.ch
hero: images/news/docuware-viewer-plugin.svg
hero_alt: "A document sits in another system; a plugin wires the DocuWare viewer to it and shows the document where the DocuWare document would normally appear"
thumb: images/news/docuware-viewer-plugin-card.svg
tags: [DMS/ECM]
featured: false
---

## The starting point

DocuWare is a standard product. It offers many functions, but it does not cover every
requirement. A customer's request gave us the chance to test how far a browser plugin can take
it.

The customer keeps their documents in another system, one they cannot replace and one that keeps
receiving new documents. Moving every document into DocuWare is not an option, because the
documents must not be duplicated. So DocuWare should hold only the index data, while the
documents stay where they are. The staff should still work in DocuWare and reach all data and
documents from there, regardless of where those documents are stored.

## What we tested

We designed an extension that hooks into the DocuWare document viewer, developers call this
"injection". When a record is opened, the plugin checks the index data. If the document is in
DocuWare, nothing happens. If it is in the other system, the plugin fetches the file from there
and shows it exactly where the DocuWare document would normally appear. Nothing is stored in
DocuWare itself.

In practice you do not notice any of this. You click the record in the result list and the
document appears, with paging, zooming, searching, printing and downloading as usual. If the
plugin finds no matching file, DocuWare behaves as it always does.

## In the video

:::video{src="https://player.vimeo.com/video/1229533037?h=a2be7c8fa4&dnt=1" title="The DocuWare viewer with the plugin: a document from another system is shown"}
A record without a file in DocuWare, the document comes from the other system.
:::

## The risk

The plugin builds on DocuWare's code. If an update changes something in the Web Client, the
plugin has to be adjusted. You end up reacting to the vendor's changes instead of deciding for
yourself. That does not rule out the approach, but you should be aware of it before a solution
like this goes into production.

## The alternative

The service could run as a standalone application outside DocuWare, with DocuWare simply linking
to it. Since version 7.12, links in index fields are shown as clickable hyperlinks, a Ctrl+click
is all it takes. That way you control behaviour, design and logic yourself, and a DocuWare update
does not affect the function. This solution is less seamless, but cleanly separated.

## How does it look at your end?

Would you like to extend DocuWare with functions it does not offer out of the box? We are glad to
help, for example with an interface to another system. Tell us about it –
[let's talk](https://www.codebar.ch/kontakt).

## Further information

- [Clickable links in result lists](https://knowledgecenter.docuware.com/docs/news-712-clickable-hyperlinks-in-result-list)
  – DocuWare Knowledge Center, what's new in 7.12
- [Chrome Extensions](https://developer.chrome.com/docs/extensions) – Google's documentation,
  how browser plugins are built
