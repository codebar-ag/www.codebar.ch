---
key: llm-gateway-open-source
slug: llm-gateway-open-source
title: 'AI in our own server room: when too many requests arrive at once'
teaser: >-
    Our AI models run on our own hardware. When too many requests arrive at the same time, they
    fail for want of resources. What helped us wasn't more hardware, but a clear order in which
    they get processed.
published_at: 2026-07-31
updated_at: 2026-07-31
published: true
author: sebastian.buergin@codebar.ch
hero: images/news/llm-gateway-open-source.svg
hero_alt: >-
    Several simultaneous requests line up in a queue and are passed on to a language model one
    after another
thumb: images/news/llm-gateway-open-source-card.svg
tags: [Open Source, AI]
featured: false
---

## Six months of AI in our daily work

For about six months now, no topic has occupied us as much as AI. We have tried a great deal,
discarded some of it again, and found a few things that genuinely help day to day: prototypes come
together faster, receipts turn into data without anyone typing them out, and when programming, we
write less ourselves and review more instead.

One experience runs through all of it, at least for us: AI delivers real value and genuine
efficiency gains where processes are properly established and the data quality holds up. Without
that foundation, quality and efficiency improve only so far with AI – and rarely for long.

We will be showing more of this over the coming weeks. First up is a tool we built for ourselves,
because our own hardware reached its limit.

## Why we keep the models in-house

Wherever possible, we run AI on local models: on our own infrastructure or on our customers'.
Customer data, contracts and internal documents stay where they already are. Which model does the
computing is therefore not a purely technical question for us. It has to suit the customer and the
way they want to work with LLMs.

Our impression: for a long time, local models followed instructions too unreliably. For our use
cases, that has changed. In most of our processes they now hold reliably to what we give them, and
here less is often more. A small model with a precise instruction frequently gets us further than
a large one without, while being considerably more efficient.

For this we have a MacBook sitting in the server room. M5 Max, 128 GB of memory, running Ollama
and LiteLLM – and open to everyone in the company.

- [codebar.ch/ki/llm](https://www.codebar.ch/ki/llm) – which models we run
- [codebar.ch/ki/llm-analytics](https://www.codebar.ch/ki/llm-analytics) – what they actually get
  asked to do

## Where things start to seize up

There is no single perfect model, not for us at least. In our workflows, each agent therefore runs
on the model that suits its task: extracting data from receipts calls for a different one than
writing a summary, and for a classification a small, fast model will do.

And that is exactly where the problem sits. A model has to be loaded into memory before it can
answer. Ours need anywhere between a few and well over a hundred gigabytes of it – loading them
all at once is not possible with the resources we have. Every switch therefore means: load first,
then compute. And while one request is being processed, no second one can slip in.

In practice it looked like this: two people and an agent each start something within moments of
one another. The first request gets processed, the other two wait – and eventually run into a
timeout. Not because the machine is too slow, but because three of them asked at the same time.

A bigger machine would take the edge off. Only it isn't possible everywhere, it is rarely the most
efficient answer, and it merely pushes the limit out until the next model comes along. What really
moved us forward was a clear order in which the requests get processed.

## What we built

So we built ourselves a gateway. It sits between the requests and the model and does something
very simple: it accepts every request, immediately returns a number that the request can later be
identified by, and puts it in a queue. It gets processed as soon as the model is free. The answer
is then collected using that number.

What mattered to us here was that nothing about sending should change: whoever used to send
straight to the model sends exactly the same thing as before, and only collecting the answer is
added as a second step.

Along with the number come the position in the queue and an estimate of the waiting time,
extrapolated from the most recent actual response times of the same model. Not a promise, but a
figure that a program or a person can work with.

## The technology behind it

The format defined by OpenAI is a workable standard. Almost every piece of software that runs
models locally – Ollama, LiteLLM, vLLM, llama.cpp – offers an OpenAI-compatible endpoint, even
when there is no OpenAI behind it at all. The usual call, `POST /v1/chat/completions`, is
synchronous: you send the request and hold the connection open until the answer arrives.

The same standard already provides for this with the Responses API. Instead of going to
`POST /v1/chat/completions`, the request goes to `POST /v1/responses`, an ID comes straight back,
and you collect the result later via `GET /v1/responses/{id}` – precisely the asynchronous
behaviour we need. It just isn't available everywhere, and even where it is, the client has to ask
for it explicitly. Anyone who doesn't, or can't, keeps sending synchronously.

We therefore treat every request as an asynchronous job, including one submitted synchronously. So
you carry on sending what you would have sent anyway:

```bash
curl -s https://llm.async.example.com/v1/chat/completions \
  -H "Authorization: Bearer $MY_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model":"qwen3.6:35b",
       "messages":[{"role":"user","content":"Summarise these minutes in five sentences."}]}'
```

What comes back, though, is not the answer but an ID:

```json
{
    "id": "resp_019fb90bbb8872ef9cbd309e471092c0",
    "object": "chat.completion.queued",
    "status": "queued",
    "model": "qwen3.6:35b",
    "created": 1785515981,
    "poll_url": "/v1/responses/resp_019fb90bbb8872ef9cbd309e471092c0",
    "queue": {
        "position": 1,
        "depth": 1,
        "workers": 1,
        "estimated_seconds": 18,
        "estimated_completion_at": 1785515999
    }
}
```

The connection is free again immediately. `estimated_seconds` is the figure a client can align its
polling interval with; the answer is collected via the `poll_url`, the route the Responses API
provides for.

Under the bonnet, the application is written in Laravel and files every job in a database queue.
Queue workers pick it up from there and hand it on, one at a time, to the local model interface –
Ollama and LiteLLM in our case. The gateway is reachable through a Cloudflare Tunnel and is
started as a launchd agent directly on the machine the models run on.

The code is publicly available on GitHub, exactly as we use it. Also in the repository: an OpenAPI
description of the endpoints, a Bruno collection to try things out, and the operating
configuration.

- [codebar-ag/llm-gateway.codebar.ai on GitHub](https://github.com/codebar-ag/llm-gateway.codebar.ai)

## How does it look at your end?

We are glad to help if you want to run AI on your own infrastructure: choosing the models,
building the workflows, and setting the order in which the requests get processed.

We prefer to start from your actual use case. Tell us about it –
[let's talk](https://www.codebar.ch/kontakt).

## Sources

- [OpenAI Responses API](https://platform.openai.com/docs/api-reference/responses) – the standard
  for asynchronous calls
- [Ollama](https://ollama.com) and [LiteLLM](https://www.litellm.ai) – the model interfaces we run
- [Cloudflare Tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/)
  – how the gateway is reachable
- [Bruno](https://www.usebruno.com) – what the collection in the repository is built with
- [Laravel](https://laravel.com) – the framework the application is written in
