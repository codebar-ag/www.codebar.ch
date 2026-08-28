import { afterEach, beforeEach, describe, expect, it } from 'vitest'

import { readingProgress } from '../../resources/js/reading-progress.js'

const mounted = []

function mount({ top = 100, height = 1100 } = {}) {
    document.body.innerHTML = `
        <div class="progress"><span class="bar"></span></div>
        <article id="article-body"></article>
    `

    const article = document.getElementById('article-body')
    Object.defineProperty(article, 'offsetTop', { value: top, configurable: true })
    Object.defineProperty(article, 'offsetHeight', { value: height, configurable: true })

    const bar = document.querySelector('.bar')

    const component = readingProgress()
    component.$refs = { bar }
    component.init()
    mounted.push(component)

    return { component, bar }
}

function scrollTo(y) {
    Object.defineProperty(window, 'scrollY', { value: y, configurable: true })
    window.dispatchEvent(new window.Event('scroll'))
}

beforeEach(() => {
    window.innerHeight = 600
    Object.defineProperty(window, 'scrollY', { value: 0, configurable: true })
})

afterEach(() => {
    mounted.splice(0).forEach((component) => component.destroy())
    document.body.innerHTML = ''
})

describe('tracking reading progress', () => {
    it('starts empty at the top of the page', () => {
        const { bar } = mount()

        expect(bar.style.width).toBe('0%')
    })

    it('grows as the visitor scrolls through the article', () => {
        const { bar } = mount()

        scrollTo(350)

        expect(bar.style.width).toBe('50%')
    })

    it('fills completely at the end of the article', () => {
        const { bar } = mount()

        scrollTo(600)

        expect(bar.style.width).toBe('100%')
    })

    it('never runs past full when scrolling beyond the article', () => {
        const { bar } = mount()

        scrollTo(5000)

        expect(bar.style.width).toBe('100%')
    })

    it('never runs below empty above the article', () => {
        Object.defineProperty(window, 'scrollY', { value: 0, configurable: true })
        const { bar } = mount({ top: 400 })

        expect(bar.style.width).toBe('0%')
    })

    it('shows a full bar for an article shorter than the window', () => {
        const { bar } = mount({ height: 300 })

        expect(bar.style.width).toBe('100%')
    })

    it('recalculates when the window is resized', () => {
        const { bar } = mount()

        Object.defineProperty(window, 'scrollY', { value: 350, configurable: true })
        window.innerHeight = 100
        window.dispatchEvent(new window.Event('resize'))

        expect(bar.style.width).toBe('25%')
    })

    it('leaves the bar alone when there is no article on the page', () => {
        const { bar } = mount()
        document.getElementById('article-body').remove()

        scrollTo(350)

        expect(bar.style.width).toBe('0%')
    })

    it('stops updating once the component is torn down', () => {
        const { component, bar } = mount()

        component.destroy()
        scrollTo(350)

        expect(bar.style.width).toBe('0%')
    })
})
