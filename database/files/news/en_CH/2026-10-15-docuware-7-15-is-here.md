---
key: docuware-7-15-is-here
slug: docuware-7-15-is-here
title: DocuWare 7.15 is here
teaser: >-
    This release brings the new DocuWare to the cloud: a rebuilt client, the AI assistant Aura
    and document processing that reads handwriting too. Classic and new run side by side for a
    while.
published_at: 2026-10-15
published: false
author: sebastian.buergin@codebar.ch
hero: images/news/docuware-7-15-is-here.svg
hero_alt: "A search dialog with index fields becomes a question in plain language, which the assistant answers with the matching document"
thumb: images/news/docuware-7-15-is-here-card.svg
tags: [DMS/ECM]
---

This is the biggest release in years. With 7.15 the new DocuWare arrives in the cloud: a client
rebuilt from the ground up, the AI assistant Aura and document processing that reads handwriting
too. The update starts in mid-October and is rolled out in stages until the end of the year.
On-premises follows later.

## The new DocuWare

The Web Client has been completely redeveloped. Search covers all file cabinets, the interface is
the same on every screen size, and it meets the WCAG 2.2 AA accessibility guidelines. The mobile
app from 7.14 is its companion.

Important for the transition: classic and new DocuWare run side by side, with the same data and
settings. Each person switches when it suits them. No migration is needed.

## Aura: questions instead of search terms

Instead of filling in a search dialog with index fields, you ask a question in plain language:
"Where is the receipt from 27 February?" Aura answers with the matching document, summarises
documents, compares two contracts with each other, extracts data or drafts a reply email.

Permissions apply as they do everywhere in DocuWare: Aura only shows what the person is allowed
to see anyway. The AI runs in DocuWare's own environment on servers in Germany, not at an
external AI service. Aura can also be called from other applications through the API. The
assistant is part of the new DocuWare and is available to all cloud customers.

## Document processing

Text recognition now reads handwriting as well. Administrators describe in plain language which
data a document should yield, no model training required. On top of that come automatic
classification, splitting of document batches and processing in the background. "Master Data
Matching" checks extracted values against master data from the ERP, such as supplier number and
address. Everything is configured directly in DocuWare, in almost 20 languages.

## Workflow Designer in the browser only

From 7.15, new workflows are created exclusively in the web-based designer. Existing workflows
can still be edited in the desktop application, which disappears with 7.16 in spring 2027.
Existing workflows appear in the browser as "to be migrated" and are taken over with one click;
if you prefer, test on a copy first.

## Before the update: check Single Sign-On

:::callout{type="warning" title="If you use SSO, act before the update"}
7.15 brings a new Identity Service with new addresses. Organisations using Single Sign-On add an
additional callback URL to their SSO configuration before their update date. Without this entry,
signing in via SSO is no longer possible after the update. DocuWare announces the date by email;
on-premises is not affected.
:::

## Further information

- [The new DocuWare](https://start.docuware.com/new-docuware) – DocuWare's overview
- [Single Sign-On for DocuWare Cloud](https://start.docuware.com/blog/product-news/single-sign-on-for-docuware-cloud)
  – the callback URL before the update
- [Callback URL instructions](https://support.docuware.com/en-us/knowledgebase/article/KBA-38173)
  – DocuWare Support, KBA-38173
- [Moving to the web-based Workflow Designer](https://start.docuware.com/blog/product-news/transition-to-docuwares-web-based-workflow-designer)
  – timeline up to 7.16
