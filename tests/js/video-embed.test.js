import { afterEach, describe, expect, it } from 'vitest'

import { videoEmbed } from '../../resources/js/video-embed.js'

function mount(src) {
    document.body.innerHTML = `<div class="video" data-src="${src}"></div>`

    const component = videoEmbed()
    component.$root = document.querySelector('.video')

    return { component }
}

afterEach(() => {
    document.body.innerHTML = ''
})

describe('click-to-load video', () => {
    it('loads nothing until the visitor asks', () => {
        const { component } = mount('https://www.youtube-nocookie.com/embed/abc123')

        expect(component.loaded).toBe(false)
        expect(component.embedSrc).toBe('')
    })

    it('builds the autoplay embed URL on load', () => {
        const { component } = mount('https://www.youtube-nocookie.com/embed/abc123')

        component.load()

        expect(component.loaded).toBe(true)
        expect(component.embedSrc).toBe('https://www.youtube-nocookie.com/embed/abc123?autoplay=1')
    })

    it('appends to an existing query string instead of starting a second one', () => {
        const { component } = mount('https://www.youtube-nocookie.com/embed/abc123?start=42')

        component.load()

        expect(component.embedSrc).toBe('https://www.youtube-nocookie.com/embed/abc123?start=42&autoplay=1')
    })
})
