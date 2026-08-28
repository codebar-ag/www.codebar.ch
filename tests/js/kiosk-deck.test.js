import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { initDeck } from '../../resources/js/kiosk-deck.js'

const teardown = []

let raf
let context

function stubContext() {
    return {
        globalAlpha: 1,
        fillStyle: null,
        setTransform: vi.fn(),
        clearRect: vi.fn(),
        save: vi.fn(),
        restore: vi.fn(),
        translate: vi.fn(),
        transform: vi.fn(),
        fillRect: vi.fn(),
        createLinearGradient: vi.fn(() => ({ addColorStop: vi.fn() })),
    }
}

function mount(durations = [1000, 2000, 1000], { reduce = false } = {}) {
    window.matchMedia.mockReturnValue({ matches: reduce })

    document.body.innerHTML = `
        <main id="deck">
            ${durations.map((dur) => `<section class="slide"${dur ? ` data-dur="${dur}"` : ''}></section>`).join('')}
        </main>
        <div id="bar"><i id="fill"></i><span id="ticks"></span></div>
        <div id="beam"></div>
        <button id="prev"></button>
        <button id="play"></button>
        <button id="next"></button>
        <button id="home"></button>
        <canvas id="field"></canvas>
    `

    const canvas = document.getElementById('field')
    Object.defineProperty(canvas, 'clientWidth', { value: 100, configurable: true })
    Object.defineProperty(canvas, 'clientHeight', { value: 50, configurable: true })

    initDeck()

    return {
        slides: Array.from(document.querySelectorAll('.slide')),
        fill: document.getElementById('fill'),
        ticks: document.getElementById('ticks'),
        beam: document.getElementById('beam'),
        canvas,
    }
}

function click(id) {
    document.getElementById(id).dispatchEvent(new window.Event('click'))
}

function press(key) {
    document.dispatchEvent(new window.KeyboardEvent('keydown', { key, cancelable: true }))
}

function onIndex(slides) {
    return slides.findIndex((slide) => slide.classList.contains('on'))
}

beforeEach(() => {
    vi.useFakeTimers()

    raf = vi.fn()
    vi.stubGlobal('requestAnimationFrame', raf)
    vi.stubGlobal('matchMedia', vi.fn().mockReturnValue({ matches: false }))

    context = stubContext()
    vi.spyOn(window.HTMLCanvasElement.prototype, 'getContext').mockReturnValue(context)

    const addToDocument = document.addEventListener.bind(document)
    vi.spyOn(document, 'addEventListener').mockImplementation((type, handler, options) => {
        teardown.push(() => document.removeEventListener(type, handler, options))
        addToDocument(type, handler, options)
    })
    const addToWindow = window.addEventListener.bind(window)
    vi.spyOn(window, 'addEventListener').mockImplementation((type, handler, options) => {
        teardown.push(() => window.removeEventListener(type, handler, options))
        addToWindow(type, handler, options)
    })
})

afterEach(() => {
    teardown.splice(0).forEach((remove) => remove())
    document.body.innerHTML = ''
    document.body.className = ''
    vi.useRealTimers()
    vi.unstubAllGlobals()
    vi.restoreAllMocks()
})

describe('starting up', () => {
    it('does nothing on a page without a deck', () => {
        document.body.innerHTML = '<p>Keine Slides</p>'

        expect(() => initDeck()).not.toThrow()
    })

    it('opens on the cover slide', () => {
        const { slides } = mount()

        expect(onIndex(slides)).toBe(0)
        expect(document.body.classList.contains('on-cover')).toBe(true)
    })
})

describe('tick marks from the slide durations', () => {
    it('places a tick at each boundary between slides', () => {
        const { ticks } = mount([1000, 2000, 1000])

        const positions = Array.from(ticks.children).map((tick) => tick.style.left)
        expect(positions).toEqual(['25%', '75%'])
    })

    it('gives a slide without a duration fourteen seconds', () => {
        const { slides, ticks } = mount([null, null])

        expect(Array.from(ticks.children).map((tick) => tick.style.left)).toEqual(['50%'])

        vi.advanceTimersByTime(13999)
        expect(onIndex(slides)).toBe(0)

        vi.advanceTimersByTime(1)
        expect(onIndex(slides)).toBe(1)
    })
})

describe('walking through the deck', () => {
    it('moves forward with the next button', () => {
        const { slides } = mount()

        click('next')

        expect(onIndex(slides)).toBe(1)
        expect(document.body.classList.contains('on-cover')).toBe(false)
    })

    it('wraps from the last slide back to the cover', () => {
        const { slides } = mount()

        click('next')
        click('next')
        expect(onIndex(slides)).toBe(2)

        click('next')

        expect(onIndex(slides)).toBe(0)
        expect(document.body.classList.contains('on-cover')).toBe(true)
    })

    it('wraps backwards from the cover to the last slide', () => {
        const { slides } = mount()

        click('prev')

        expect(onIndex(slides)).toBe(2)
    })

    it('returns to the cover with the home button', () => {
        const { slides } = mount()

        click('next')
        click('next')
        click('home')

        expect(onIndex(slides)).toBe(0)
        expect(document.body.classList.contains('on-cover')).toBe(true)
    })

    it('listens to the arrow keys', () => {
        const { slides } = mount()

        press('ArrowRight')
        expect(onIndex(slides)).toBe(1)

        press('ArrowLeft')
        expect(onIndex(slides)).toBe(0)

        press('PageDown')
        press('Home')
        expect(onIndex(slides)).toBe(0)
    })

    it('advances by itself when the slide duration is up', () => {
        const { slides } = mount()

        vi.advanceTimersByTime(1000)
        expect(onIndex(slides)).toBe(1)

        vi.advanceTimersByTime(2000)
        expect(onIndex(slides)).toBe(2)

        vi.advanceTimersByTime(1000)
        expect(onIndex(slides)).toBe(0)
    })

    it('restarts the clock after a manual jump', () => {
        const { slides } = mount()

        click('next')

        vi.advanceTimersByTime(1999)
        expect(onIndex(slides)).toBe(1)

        vi.advanceTimersByTime(1)
        expect(onIndex(slides)).toBe(2)
    })
})

describe('pausing and resuming', () => {
    it('stops the clock while paused', () => {
        const { slides } = mount()

        click('play')

        expect(document.body.classList.contains('is-paused')).toBe(true)

        vi.advanceTimersByTime(10000)
        expect(onIndex(slides)).toBe(0)
    })

    it('resumes with only the remaining time on the clock', () => {
        const { slides } = mount()

        vi.advanceTimersByTime(400)
        click('play')
        vi.advanceTimersByTime(10000)

        click('play')
        expect(document.body.classList.contains('is-paused')).toBe(false)

        vi.advanceTimersByTime(599)
        expect(onIndex(slides)).toBe(0)

        vi.advanceTimersByTime(1)
        expect(onIndex(slides)).toBe(1)
    })

    it('toggles pause with the space bar', () => {
        mount()

        press(' ')
        expect(document.body.classList.contains('is-paused')).toBe(true)

        press(' ')
        expect(document.body.classList.contains('is-paused')).toBe(false)
    })

    it('freezes the bar at the scale the animation reached', () => {
        vi.spyOn(window, 'getComputedStyle').mockReturnValue({ transform: 'matrix(0.42, 0, 0, 1, 0, 0)' })
        const { fill } = mount()

        click('play')

        expect(fill.style.transform).toBe('scaleX(0.42)')
        expect(fill.style.transition).toBe('none')
    })

    it('falls back to the slide mark when no matrix is computed', () => {
        vi.spyOn(window, 'getComputedStyle').mockReturnValue({ transform: 'none' })
        const { fill } = mount()

        click('next')
        click('play')

        expect(fill.style.transform).toBe('scaleX(0.25)')
    })
})

describe('the progress bar animation', () => {
    it('animates towards the next slide mark', () => {
        const { fill } = mount()

        expect(fill.style.transform).toBe('scaleX(0.25)')
        expect(fill.style.transition).toBe('transform 1000ms linear')
    })

    it('jumps without animating when motion is reduced', () => {
        const { fill } = mount([1000, 2000, 1000], { reduce: true })

        expect(fill.style.transform).toBe('scaleX(0.25)')
        expect(fill.style.transition).toBe('none')
    })

    it('sweeps the beam in the direction of travel', () => {
        const { beam } = mount()

        click('next')
        expect(beam.classList.contains('sweep')).toBe(true)
        expect(beam.style.animationDirection).toBe('normal')

        click('prev')
        expect(beam.style.animationDirection).toBe('reverse')
    })

    it('leaves the beam alone when motion is reduced', () => {
        const { beam } = mount([1000, 2000, 1000], { reduce: true })

        click('next')

        expect(beam.classList.contains('sweep')).toBe(false)
    })
})

describe('the background canvas', () => {
    it('scales the canvas to the device pixel ratio, capped at two', () => {
        vi.stubGlobal('devicePixelRatio', 3)

        const { canvas } = mount()

        expect(canvas.width).toBe(200)
        expect(canvas.height).toBe(100)
        expect(context.setTransform).toHaveBeenCalledWith(2, 0, 0, 2, 0, 0)
    })

    it('paints strips each frame and queues the next one', () => {
        mount()

        expect(raf).toHaveBeenCalledTimes(1)
        const frame = raf.mock.calls[0][0]

        frame()

        expect(context.clearRect).toHaveBeenCalled()
        expect(context.fillRect.mock.calls.length).toBeGreaterThan(0)
        expect(raf).toHaveBeenCalledTimes(2)
    })

    it('drifts the strips while playing and holds them while paused', () => {
        mount()
        const frame = raf.mock.calls[0][0]

        frame()
        const first = context.fillRect.mock.calls.map((call) => call[0])
        context.fillRect.mockClear()

        frame()
        const second = context.fillRect.mock.calls.map((call) => call[0])
        expect(second).not.toEqual(first)
        context.fillRect.mockClear()

        click('play')
        frame()
        const third = context.fillRect.mock.calls.map((call) => call[0])
        context.fillRect.mockClear()

        frame()
        expect(context.fillRect.mock.calls.map((call) => call[0])).toEqual(third)
    })
})
