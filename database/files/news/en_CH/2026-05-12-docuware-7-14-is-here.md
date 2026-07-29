---
key: docuware-7-14-is-here
slug: docuware-7-14-is-here
title: DocuWare 7.14 is here
teaser: >-
  The last settings move into the browser, a rebuilt app puts tasks on your phone, and file cabinets can be moved between two cloud organisations.
published_at: 2026-05-12
author: sebastian.buergin@codebar.ch
hero: images/news/placeholders/docuware-7-14.svg
hero_alt: Placeholder graphic for the DocuWare 7.14 release
tags: [DMS/ECM]
---

This release tidies up. The last desktop application for settings disappears, the mobile app has been rebuilt from
scratch, and two modules are being retired.

## Everything in the browser

DocuWare Administration is discontinued. Whatever was left in it now lives in the DocuWare Configuration:

- **File connections** under “Integrations”, with a preview and a direct upload of the CSV file. The detour via FTP is
  gone.
- **Effective file cabinet rights** in User Management – a single view of what a person is actually allowed to do.
- **License overview** in the Organization Settings, showing license type and applications in use per person.

## The new app

The mobile app has been rebuilt. It is simply called “DocuWare”, while the previous one continues as “DocuWare
Classic”. Its scope follows what is genuinely needed away from the desk:

- Find documents and pass them on straight from the app – the case for field sales and service technicians.
- Work through tasks grouped by process.
- Push notifications for new tasks. Only with these do approvals stop sitting for days because nobody checked their
  inbox.
- Hand files over to DocuWare from other apps.
- Tabs for switching between a task and the document it belongs to.

## Security and automation

- **Two-factor authentication** used to be voluntary. It can now be made mandatory across the organisation, with
  exceptions for individual people or entire roles.
- **Cloud-to-cloud transfer** moves or copies documents and whole file cabinets from one DocuWare Cloud organisation to
  another. Intended for consolidation after an acquisition or separation after a spin-off.
- **Annotations stay with the document:** a workflow can hand a document over to an interface as a PDF including stamps
  and notes. Previously only the original or a PDF without those additions was possible.
- **Two new Platform API calls** create user groups and separate clipped documents again.
- **IDP keys** are set automatically on creation, so there is nothing left to copy by hand.

## What is going away

- **DocuWare Request** is discontinued and hidden by default. Anyone using “Export as backup (with electronic
  signing)” in an existing configuration is unaffected.
- **Connect to Outlook** is being phased out, as Microsoft retires Classic Outlook by 2029. Its successor is the
  DocuWare for Outlook add-in – which now takes over the existing storage configurations, including storage location,
  indexing and how attachments are handled. Nothing needs rebuilding.

## More information

All new features, with screenshots and use cases, are documented in the Knowledge Center:

- [Knowledge Center – What's New in DocuWare Version 7.14](https://knowledgecenter.docuware.com/docs/news-docuware-version-714)
- [Technical Release Notes 7.14](https://knowledgecenter.docuware.com/docs/news-714-technical-release-notes)
- [The new DocuWare mobile app](https://knowledgecenter.docuware.com/docs/april-26-news-new-mobile-app)
