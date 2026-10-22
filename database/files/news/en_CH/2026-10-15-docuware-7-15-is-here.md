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
too. New customers already start on 7.15; existing cloud customers receive the update from
mid-October, in stages over about two months. DocuWare announces the date by email. On-premises
7.15 is also due in mid-October, but the new DocuWare and Aura are cloud-only for now.

## The new DocuWare

The Web Client has been completely redeveloped. Search covers all file cabinets, even without
index fields, the interface is the same on every screen size, and it meets the WCAG 2.2 AA
accessibility guidelines. Since the summer, lists, versions, a recycle bin and editing documents
directly from a workflow task have been added, among other things. The mobile app from 7.14 is
its companion.

Important for the transition: classic and new DocuWare run side by side, with the same data and
settings. A stamp set in the classic viewer also shows in the new one. Each person switches when
it suits them. No migration is needed, and the classic client remains available for at least
another year.

## Aura: questions instead of search terms

Instead of filling in a search dialog with index fields, you ask a question in plain language:
"Where is the receipt from 27 February?" Aura answers with the matching document, summarises
documents, compares two contracts with each other, extracts data or drafts a reply email.
Answers include source references with page numbers, and earlier conversations can be picked up
again.

Aura only sees what the person is allowed to see according to their file cabinet permissions
(see "Before the update"). The AI runs in DocuWare's own environment on servers in Germany, not
at an external AI service. Aura can also be called from other applications through the API.

## What Aura costs

Aura is billed by usage, in so-called credits. A complex question across several file cabinets
with long documents takes around 30,000 credits, a simple question considerably fewer. Cloud
customers receive a free allowance to try Aura with their own data; the number of documents is
limited. Those who use Aura day to day buy additional credits, with a lower price per credit at
higher volumes. Administrators can set limits per person and group.

## Document processing

Text recognition now reads handwriting as well. Administrators describe in plain language which
data a document should yield, no model training required. On top of that come automatic
classification, splitting of document batches and processing in the background. "Master Data
Matching" checks extracted values against master data from the ERP, such as supplier number and
address.

The new configuration directly in DocuWare is included in 7.15 as early access: processes are
put together by drag and drop, without DocuWare Workflow. Over the coming months, storing from
Connect to Mail, e-invoices and table fields will follow, among other things.

## Workflow Designer and Forms

From 7.15, new workflows are created exclusively in the web-based designer. It arranges
activities automatically, colours them by type and previews notifications while you edit them.
Existing workflows can still be edited in the desktop application, which disappears with 7.16 in
spring 2027. Existing workflows appear in the browser as "to be migrated" and are taken over with
one click; if you prefer, test on a copy first.

In DocuWare Forms, values from dropdown lists can now feed into calculations, so fewer fields
have to be filled in by hand.

## Before the update: check two things

:::callout{type="warning" title="Control access through file cabinet permissions, not dialogs"}
The new search and Aura find documents wherever a person has file cabinet permissions,
regardless of search and result dialogs. Where access was previously restricted through dialogs,
people may see documents after the update that were hidden from them before. In the new client,
the functions in the result list's context menu also follow file cabinet permissions only. So
check before the update that the file cabinet permissions are correct.
:::

:::callout{type="warning" title="If you use SSO, act before the update"}
7.15 brings a new Identity Service with new addresses. Organisations using Single Sign-On add an
additional callback URL to their SSO configuration before their update date. Without this entry,
signing in via SSO is no longer possible after the update. On-premises is not affected.
:::

## Further information

- [The new DocuWare](https://start.docuware.com/new-docuware) – DocuWare's overview
- [Knowledge Center for 7.15](https://go.docuware.com/knowledgecenter715) – all new features and
  release notes
- [Single Sign-On for DocuWare Cloud](https://start.docuware.com/blog/product-news/single-sign-on-for-docuware-cloud)
  – the callback URL before the update
- [Callback URL instructions](https://support.docuware.com/en-us/knowledgebase/article/KBA-38173)
  – DocuWare Support, KBA-38173
- [Moving to the web-based Workflow Designer](https://start.docuware.com/blog/product-news/transition-to-docuwares-web-based-workflow-designer)
  – timeline up to 7.16
