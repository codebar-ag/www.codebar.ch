import { beforeEach, describe, expect, it } from 'vitest'
import { FILTER_PARAM, initFilter } from '../../resources/js/infrastructure/filter.js'

const KEYS = ['network', 'platform', 'services']
const mount = (search = '', hash = '') => {
    window.history.replaceState(null, '', '/infrastruktur' + search + hash)
    document.body.innerHTML = '<button data-infra-filter-reset aria-pressed="true">Alle</button>'
        + KEYS.map(k => `<button data-infra-filter="${k}" aria-pressed="false">${k}</button>`).join('')
        + KEYS.map(k => `<section data-infra-layer="${k}"></section>`).join('')

    return initFilter()
}
const button = key => document.querySelector(`[data-infra-filter="${key}"]`)
const reset = () => document.querySelector('[data-infra-filter-reset]')
const visible = () => [...document.querySelectorAll('[data-infra-layer]')].filter(s => !s.hidden).map(s => s.dataset.infraLayer)
const pressed = () => KEYS.filter(k => button(k).getAttribute('aria-pressed') === 'true')

beforeEach(() => {
    document.body.innerHTML = ''
})

describe('infrastructure filter', () => {
    it('shows everything at the start with "all" pressed', () => {
        const filter = mount()

        expect(visible()).toEqual(KEYS)
        expect(pressed()).toEqual([])
        expect(reset().getAttribute('aria-pressed')).toBe('true')
        expect(filter.active()).toEqual([])
    })

    it('narrows to one area and writes it into the address', () => {
        mount()
        button('platform').click()

        expect(visible()).toEqual(['platform'])
        expect(pressed()).toEqual(['platform'])
        expect(reset().getAttribute('aria-pressed')).toBe('false')
        expect(location.search).toBe('?bereich=platform')
    })

    it('combines several areas in the order of the page', () => {
        mount()
        button('services').click()
        button('network').click()

        expect(visible()).toEqual(['network', 'services'])
        expect(location.search).toBe('?bereich=network,services')
    })

    it('removes an area with a second click and shows everything when none is left', () => {
        mount()
        button('network').click()
        button('network').click()

        expect(visible()).toEqual(KEYS)
        expect(reset().getAttribute('aria-pressed')).toBe('true')
        expect(location.search).toBe('')
    })

    it('treats every area selected as no filter', () => {
        mount()
        KEYS.forEach(k => button(k).click())

        expect(visible()).toEqual(KEYS)
        expect(pressed()).toEqual([])
        expect(location.search).toBe('')
    })

    it('clears the selection with "all"', () => {
        mount()
        button('network').click()
        button('services').click()
        reset().click()

        expect(visible()).toEqual(KEYS)
        expect(pressed()).toEqual([])
        expect(location.search).toBe('')
    })

    it('starts with the areas from the address and ignores unknown or repeated ones', () => {
        const filter = mount('?bereich=services,nothing,network,services,')

        expect(filter.active()).toEqual(['network', 'services'])
        expect(visible()).toEqual(['network', 'services'])
        expect(pressed()).toEqual(['network', 'services'])
    })

    it('shows everything for an empty or unknown address value', () => {
        for (const search of ['?bereich=', '?bereich=nothing', '?bereich=,,', '?other=1']) {
            mount(search)

            expect(visible()).toEqual(KEYS)
            expect(reset().getAttribute('aria-pressed')).toBe('true')
        }
    })

    it('keeps the customer and the graphic mark in the address', () => {
        mount('?kunde=web.example', '#grafik')
        button('platform').click()

        expect(location.search).toBe('?kunde=web.example&bereich=platform')
        expect(location.hash).toBe('#grafik')

        reset().click()

        expect(location.search).toBe('?kunde=web.example')
        expect(location.hash).toBe('#grafik')
    })

    it('does nothing on a page without filter buttons', () => {
        document.body.innerHTML = '<section data-infra-layer="network"></section>'

        expect(initFilter()).toBeNull()
        expect(visible()).toEqual(['network'])
    })

    it('works without the "all" button and without sections', () => {
        document.body.innerHTML = '<button data-infra-filter="network" aria-pressed="false"></button><button data-infra-filter="platform" aria-pressed="false"></button>'
        window.history.replaceState(null, '', '/infrastruktur')
        const filter = initFilter()

        button('network').click()

        expect(filter.active()).toEqual(['network'])
        expect(FILTER_PARAM).toBe('bereich')
    })
})
