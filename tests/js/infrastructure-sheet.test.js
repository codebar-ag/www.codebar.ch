import { describe, expect, it } from 'vitest'
import { esc, stationSheet } from '../../resources/js/infrastructure/sheet.js'

const labels = { close: 'Schliessen', details: 'Mehr zu diesem Dienst', processing: 'Datenverarbeitung', provider: 'Anbieter', origin: 'Herkunft des Anbieters', operation: 'Betrieb' }
const flags = { CH: '<svg data-flag="CH"></svg>', US: '<svg data-flag="US"></svg>' }
const station = (over = {}) => ({
    id: 'postmark',
    name: 'E-Mail',
    text: 'Stellt E-Mails zu.',
    provider: 'Postmark',
    vendor: 'Postmark',
    vendor_country: { code: 'US', name: 'USA' },
    place: '',
    countries: [{ code: 'US', name: 'USA' }],
    flags: ['US'],
    operation: 'Eingekaufter Service',
    links: [{ label: 'Anbieter-Website', url: 'https://postmarkapp.com', host: 'postmarkapp.com' }],
    ...over,
})
const render = (over = {}, options = {}) => {
    const el = document.createElement('div')
    el.innerHTML = stationSheet(station(over), { labels, flags, ...options })

    return el
}

describe('esc', () => {
    it('escapes every character that can break out of text or an attribute', () => {
        expect(esc('<b a="1" c=\'2\'>&</b>')).toBe('&lt;b a=&quot;1&quot; c=&#39;2&#39;&gt;&amp;&lt;/b&gt;')
    })

    it('turns numbers, null and undefined into text', () => {
        expect(esc(5)).toBe('5')
        expect(esc(null)).toBe('null')
        expect(esc(undefined)).toBe('undefined')
        expect(esc('')).toBe('')
    })
})

describe('stationSheet', () => {
    it('shows title, provider, text and the four rows in order', () => {
        const el = render()

        expect(el.querySelector('h2').textContent).toBe('E-Mail')
        expect(el.querySelector('h2').id).toBe('infraDialogTitle')
        expect(el.querySelector('.infra-dialog-lead').textContent.trim()).toBe('Postmark')
        expect(el.querySelector('.infra-dialog-text').textContent).toBe('Stellt E-Mails zu.')
        expect([...el.querySelectorAll('dt')].map(d => d.textContent)).toEqual(['Datenverarbeitung', 'Anbieter', 'Herkunft des Anbieters', 'Betrieb'])
        expect([...el.querySelectorAll('dd')].map(d => d.textContent.trim())).toEqual(['USA', 'Postmark', 'USA', 'Eingekaufter Service'])
    })

    it('has a close button with a label', () => {
        const button = render().querySelector('button[data-close]')

        expect(button.type).toBe('button')
        expect(button.getAttribute('aria-label')).toBe('Schliessen')
    })

    it('links to the detail page in a new tab when an address is given', () => {
        const link = render({}, { url: '/infrastruktur/postmark?kunde=web.example&x=1' }).querySelector('a.infra-dialog-open')

        expect(link.getAttribute('href')).toBe('/infrastruktur/postmark?kunde=web.example&x=1')
        expect(link.target).toBe('_blank')
        expect(link.rel).toBe('noopener')
        expect(link.getAttribute('aria-label')).toBe('Mehr zu diesem Dienst')
    })

    it('has no detail link without an address', () => {
        for (const url of [null, undefined, '']) {
            expect(render({}, { url }).querySelector('a.infra-dialog-open')).toBeNull()
        }
    })

    it('opens provider links in a new tab with the title above the address', () => {
        const link = render().querySelector('ul a')

        expect(link.href).toBe('https://postmarkapp.com/')
        expect(link.target).toBe('_blank')
        expect(link.rel).toBe('noopener noreferrer')
        expect(link.querySelector('b').textContent).toBe('↗ Anbieter-Website')
        expect(link.querySelector('span').textContent).toBe('postmarkapp.com')
        expect(link.firstElementChild.tagName).toBe('B')
    })

    it('lists several links and leaves the list out when there is none', () => {
        const two = render({ links: [{ label: 'A', url: 'https://a.ch', host: 'a.ch' }, { label: 'B', url: 'https://b.ch', host: 'b.ch' }] })

        expect(two.querySelectorAll('ul a')).toHaveLength(2)
        expect(render({ links: [] }).querySelector('ul')).toBeNull()
    })

    it('leaves the origin row out when the provider country is unknown', () => {
        const el = render({ vendor_country: null })

        expect([...el.querySelectorAll('dt')].map(d => d.textContent)).toEqual(['Datenverarbeitung', 'Anbieter', 'Betrieb'])
    })

    it('puts the place before the first country only', () => {
        const el = render({ place: 'Frankfurt', countries: [{ code: 'DE', name: 'Deutschland' }, { code: 'CH', name: 'Schweiz' }] })
        const text = el.querySelector('dd').textContent.replace(/\s+/g, ' ').trim()

        expect(text).toBe('Frankfurt, Deutschland Schweiz')
        expect(el.querySelector('dd svg[data-flag="CH"]')).not.toBeNull()
    })

    it('shows flags that exist and skips unknown ones', () => {
        const el = render({ flags: ['US', 'ZZ'], countries: [{ code: 'ZZ', name: 'Nirgendwo' }] })

        expect(el.querySelectorAll('.infra-dialog-lead svg')).toHaveLength(1)
        expect(el.querySelector('dd').textContent.trim()).toBe('Nirgendwo')
    })

    it('works without any flag markup and without countries', () => {
        const el = document.createElement('div')
        el.innerHTML = stationSheet(station({ countries: [], flags: [] }), { labels })

        expect(el.querySelector('dd').textContent.trim()).toBe('')
        expect(el.querySelector('svg[data-flag]')).toBeNull()
    })

    it('never lets data inject markup or scripts', () => {
        const evil = '<img src=x onerror=alert(1)>"\'<script>alert(2)</script>'
        const el = render({
            name: evil, text: evil, provider: evil, vendor: evil, operation: evil, place: evil,
            countries: [{ code: 'US', name: evil }], vendor_country: { code: 'US', name: evil },
            links: [{ label: evil, url: 'https://a.ch/"onmouseover="alert(3)', host: evil }],
        }, { url: '"><script>alert(4)</script>' })

        expect(el.querySelector('img')).toBeNull()
        expect(el.querySelector('script')).toBeNull()
        expect(el.querySelector('[onmouseover]')).toBeNull()
        expect(el.querySelector('[onerror]')).toBeNull()
        expect(el.querySelector('h2').textContent).toBe(evil)
        expect(el.querySelector('a.infra-dialog-open').getAttribute('href')).toBe('"><script>alert(4)</script>')
    })
})
