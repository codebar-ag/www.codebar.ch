import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { tableOfContents } from '../../resources/js/table-of-contents.js'

const observers = []

class FakeIntersectionObserver {
    constructor(callback, options) {
        this.callback = callback
        this.options = options
        this.observed = []
        this.disconnected = false
        observers.push(this)
    }

    observe(target) {
        this.observed.push(target)
    }

    disconnect() {
        this.disconnected = true
    }
}

function mount() {
    document.body.innerHTML = `
        <nav class="toc">
            <a href="#intro" data-anchor="intro">Intro</a>
            <a href="#details" data-anchor="details">Details</a>
            <a href="#faq" data-anchor="faq">FAQ</a>
        </nav>
        <div id="article-body">
            <h2 id="intro">Intro</h2>
            <h3 id="details">Details</h3>
            <h2 id="faq">FAQ</h2>
            <h2>Ohne Anker</h2>
        </div>
    `

    const root = document.querySelector('.toc')

    const component = tableOfContents()
    component.$root = root
    component.init()

    return { component, root }
}

function intersect(observer, id, isIntersecting = true) {
    observer.callback([{ isIntersecting, target: document.getElementById(id) }])
}

function currentAnchors(root) {
    return Array.from(root.querySelectorAll('a[data-anchor]')).map((link) => link.getAttribute('aria-current'))
}

beforeEach(() => {
    vi.stubGlobal('IntersectionObserver', FakeIntersectionObserver)
})

afterEach(() => {
    observers.splice(0)
    document.body.innerHTML = ''
    vi.unstubAllGlobals()
    vi.restoreAllMocks()
})

describe('watching the article headings', () => {
    it('observes every heading that carries an anchor', () => {
        mount()

        expect(observers).toHaveLength(1)
        expect(observers[0].observed.map((heading) => heading.id)).toEqual(['intro', 'details', 'faq'])
    })

    it('creates no observer on a page without headings', () => {
        document.body.innerHTML = '<nav class="toc"></nav>'
        const component = tableOfContents()
        component.$root = document.querySelector('.toc')
        component.init()

        expect(observers).toHaveLength(0)
        expect(() => component.destroy()).not.toThrow()
    })

    it('marks the link of the section that scrolled into view', () => {
        const { root } = mount()

        intersect(observers[0], 'details')

        expect(currentAnchors(root)).toEqual(['false', 'true', 'false'])
    })

    it('moves the mark on to the next section', () => {
        const { root } = mount()

        intersect(observers[0], 'details')
        intersect(observers[0], 'faq')

        expect(currentAnchors(root)).toEqual(['false', 'false', 'true'])
    })

    it('keeps the mark while a section merely scrolls out', () => {
        const { root } = mount()

        intersect(observers[0], 'details')
        intersect(observers[0], 'details', false)

        expect(currentAnchors(root)).toEqual(['false', 'true', 'false'])
    })

    it('stops observing once the component is torn down', () => {
        const { component } = mount()

        component.destroy()

        expect(observers[0].disconnected).toBe(true)
    })
})
