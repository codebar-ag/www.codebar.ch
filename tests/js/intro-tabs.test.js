import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { INTRO_TAB_KEY, introTabs } from '../../resources/js/intro-tabs.js'

const TABS = [1, 2, 3]

function mount() {
    document.body.innerHTML = `
        <fieldset class="intro-tabs">
            <input type="radio" name="intro-tab" id="intro-tab-0" data-tab="0" checked>
            ${TABS.map((index) => `
                <label for="intro-tab-${index}">
                    <input type="radio" name="intro-tab" id="intro-tab-${index}"
                           data-tab="${index}" data-shortcut="${index}">
                </label>
            `).join('')}
            <input type="text" id="somewhere-else">
        </fieldset>
    `

    const root = document.querySelector('.intro-tabs')

    root.getBoundingClientRect = () => ({ top: 0, bottom: 500 })

    const component = introTabs()
    component.$root = root
    component.init()

    return { component, root }
}

function checkedIndex() {
    return Array.from(document.querySelectorAll('input[data-tab]')).findIndex((tab) => tab.checked)
}

function press(key, options = {}) {
    window.dispatchEvent(new window.KeyboardEvent('keydown', { key, bubbles: true, cancelable: true, ...options }))
}

beforeEach(() => {
    window.sessionStorage.clear()
    window.innerHeight = 800
})

afterEach(() => {
    document.body.innerHTML = ''
    vi.restoreAllMocks()
})

describe('remembering the tab for the session', () => {
    it('opens on the start panel when the session is new', () => {
        mount()

        expect(checkedIndex()).toBe(0)
        expect(window.sessionStorage.getItem(INTRO_TAB_KEY)).toBeNull()
    })

    it('stores the tab a visitor picks', () => {
        const { root } = mount()

        const third = root.querySelector('input[data-tab="2"]')
        third.checked = true
        third.dispatchEvent(new window.Event('change', { bubbles: true }))

        expect(window.sessionStorage.getItem(INTRO_TAB_KEY)).toBe('2')
    })

    it('stores the tab a keyboard shortcut picks', () => {
        mount()

        press('3')

        expect(checkedIndex()).toBe(3)
        expect(window.sessionStorage.getItem(INTRO_TAB_KEY)).toBe('3')
    })

    it('stores the tab the arrow keys land on', () => {
        mount()

        press('ArrowRight')

        expect(window.sessionStorage.getItem(INTRO_TAB_KEY)).toBe('1')
    })

    it('reopens the stored tab on the next page load', () => {
        window.sessionStorage.setItem(INTRO_TAB_KEY, '2')

        mount()

        expect(checkedIndex()).toBe(2)
    })

    it('falls back to the first tab when the stored one no longer exists', () => {
        window.sessionStorage.setItem(INTRO_TAB_KEY, '9')

        mount()

        expect(checkedIndex()).toBe(0)
    })

    it('keeps working when storage is blocked', () => {
        vi.spyOn(window.sessionStorage.__proto__, 'setItem').mockImplementation(() => {
            throw new Error('access denied')
        })
        vi.spyOn(window.sessionStorage.__proto__, 'getItem').mockImplementation(() => {
            throw new Error('access denied')
        })

        expect(() => {
            mount()
            press('3')
        }).not.toThrow()

        expect(checkedIndex()).toBe(3)
    })
})

describe('keyboard navigation', () => {
    it('jumps to the tab of each number key', () => {
        mount()

        for (const index of TABS) {
            press(String(index))
            expect(checkedIndex(), `key ${index}`).toBe(index)
        }
    })

    it('has no shortcut left for the start panel', () => {
        mount()

        press('3')
        press('4')

        expect(checkedIndex()).toBe(3)
    })

    it('enters the tabs from the start panel with the right arrow', () => {
        mount()

        press('ArrowRight')

        expect(checkedIndex()).toBe(1)
    })

    it('enters the tabs from the start panel with the left arrow', () => {
        mount()

        press('ArrowLeft')

        expect(checkedIndex()).toBe(3)
    })

    it('wraps the arrow keys through the three tabs, never back to the start panel', () => {
        mount()

        press('3')
        press('ArrowRight')
        expect(checkedIndex()).toBe(1)

        press('ArrowLeft')
        expect(checkedIndex()).toBe(3)
    })

    it('ignores shortcuts while the visitor is typing in a field', () => {
        mount()

        document.querySelector('#somewhere-else').dispatchEvent(
            new window.KeyboardEvent('keydown', { key: '3', bubbles: true }),
        )

        expect(checkedIndex()).toBe(0)
    })

    it('leaves shortcuts with a modifier to the browser', () => {
        mount()

        press('3', { metaKey: true })

        expect(checkedIndex()).toBe(0)
    })

    it('ignores the arrow keys once the window is scrolled past', () => {
        const { root } = mount()
        root.getBoundingClientRect = () => ({ top: -1200, bottom: -400 })

        press('ArrowRight')

        expect(checkedIndex()).toBe(0)
    })

    it('stops listening once the component is torn down', () => {
        const { component } = mount()

        component.destroy()
        press('3')

        expect(checkedIndex()).toBe(0)
    })
})

describe('arrow buttons', () => {
    it('moves to the next tab when step(1) runs, same as the right arrow', () => {
        const { component } = mount()

        component.step(1)

        expect(checkedIndex()).toBe(1)
        expect(window.sessionStorage.getItem(INTRO_TAB_KEY)).toBe('1')
    })

    it('moves to the previous tab when step(-1) runs, same as the left arrow', () => {
        const { component } = mount()

        component.step(-1)

        expect(checkedIndex()).toBe(3)
    })

    it('wraps forward past the last tab back to the first', () => {
        const { component } = mount()

        component.step(1)
        component.step(1)
        component.step(1)
        expect(checkedIndex()).toBe(3)

        component.step(1)
        expect(checkedIndex()).toBe(1)
    })

    it('never lands back on the start panel', () => {
        const { component } = mount()

        for (let i = 0; i < 10; i++) component.step(1)
        expect(checkedIndex()).not.toBe(0)

        for (let i = 0; i < 10; i++) component.step(-1)
        expect(checkedIndex()).not.toBe(0)
    })
})
