import { afterEach, describe, expect, it } from 'vitest'

import { turnstile } from '../../resources/js/turnstile.js'

const SRC = 'https://challenges.cloudflare.com/turnstile/v0/api.js'

const mounted = []

function mount() {
    const component = turnstile()
    mounted.push(component)
    component.$root = document.createElement('div')
    component.$root.dataset.turnstileSrc = SRC
    component.init()

    return component
}

function scripts() {
    return [...document.head.querySelectorAll(`script[src="${SRC}"]`)]
}

afterEach(() => {
    mounted.splice(0).forEach((component) => component.destroy())
    scripts().forEach((script) => script.remove())
})

describe('the deferred turnstile widget', () => {
    it('does not load the script before the visitor interacts', () => {
        mount()

        expect(scripts()).toHaveLength(0)
    })

    it('loads the script on the first interaction', () => {
        mount()

        window.dispatchEvent(new Event('pointerdown'))

        expect(scripts()).toHaveLength(1)
        expect(scripts()[0].async).toBe(true)
    })

    it('loads the script only once', () => {
        mount()

        window.dispatchEvent(new Event('keydown'))
        window.dispatchEvent(new Event('scroll'))

        expect(scripts()).toHaveLength(1)
    })
})
