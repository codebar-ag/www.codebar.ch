import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { combobox } from '../../resources/js/combobox.js'

const OPTIONS = ['Berlin', 'Bern', 'Zürich']

function mount(inputValue = '') {
    document.body.innerHTML = `
        <form>
            <div class="combobox">
                <input type="text" name="city" value="${inputValue}">
                <ul>
                    ${OPTIONS.map((value, index) => `
                        <li id="option-${index}" data-value="${value}">${value}</li>
                    `).join('')}
                </ul>
            </div>
        </form>
    `

    const root = document.querySelector('.combobox')
    const input = root.querySelector('input')
    const list = root.querySelector('ul')

    const component = combobox()
    component.$root = root
    component.$refs = { input, list }
    component.init()

    return { component, root, input, list }
}

function visibleValues(list) {
    return Array.from(list.children)
        .filter((item) => item.style.display !== 'none')
        .map((item) => item.dataset.value)
}

function activeValue(list) {
    const active = list.querySelector('[aria-selected="true"]')
    return active ? active.dataset.value : null
}

beforeEach(() => {
    Element.prototype.scrollIntoView = vi.fn()
    vi.spyOn(window.HTMLFormElement.prototype, 'submit').mockImplementation(() => {})
})

afterEach(() => {
    delete Element.prototype.scrollIntoView
    document.body.innerHTML = ''
    vi.restoreAllMocks()
})

describe('opening and closing', () => {
    it('starts closed with nothing highlighted', () => {
        const { component } = mount()

        expect(component.isOpen).toBe(false)
        expect(component.activeIndex).toBe(-1)
        expect(component.aria_expanded).toBe('false')
    })

    it('reports the list as expanded while open', () => {
        const { component } = mount()

        component.open()

        expect(component.isOpen).toBe(true)
        expect(component.aria_expanded).toBe('true')
    })

    it('drops the highlight when the list closes', () => {
        const { component, input, list } = mount()

        component.open()
        component.highlightNext()
        expect(component.activeIndex).toBe(0)

        component.close()

        expect(component.isOpen).toBe(false)
        expect(component.activeIndex).toBe(-1)
        expect(activeValue(list)).toBeNull()
        expect(input.hasAttribute('aria-activedescendant')).toBe(false)
    })

    it('knows on init whether the field already holds a value', () => {
        expect(mount().component.hasValue).toBe(false)
        expect(mount('Bern').component.hasValue).toBe(true)
    })
})

describe('filtering', () => {
    it('shows every option for an empty query', () => {
        const { component, list } = mount()

        component.open()

        expect(visibleValues(list)).toEqual(OPTIONS)
    })

    it('hides options that do not match, ignoring case', () => {
        const { component, input, list } = mount()

        input.value = 'ber'
        component.filter()

        expect(visibleValues(list)).toEqual(['Berlin', 'Bern'])
        expect(component.hasValue).toBe(true)
    })

    it('resets the highlight when the query changes', () => {
        const { component, input } = mount()

        component.open()
        component.highlightNext()
        expect(component.activeIndex).toBe(0)

        input.value = 'z'
        component.filter()

        expect(component.activeIndex).toBe(-1)
    })
})

describe('keyboard highlighting', () => {
    it('opens the list instead of moving when it is closed', () => {
        const { component } = mount()

        component.highlightNext()

        expect(component.isOpen).toBe(true)
        expect(component.activeIndex).toBe(-1)
    })

    it('steps down through the options and wraps to the top', () => {
        const { component, list } = mount()

        component.open()

        component.highlightNext()
        expect(activeValue(list)).toBe('Berlin')

        component.highlightNext()
        component.highlightNext()
        expect(activeValue(list)).toBe('Zürich')

        component.highlightNext()
        expect(activeValue(list)).toBe('Berlin')
    })

    it('steps up through the options and wraps to the bottom', () => {
        const { component, list } = mount()

        component.open()

        component.highlightPrevious()
        expect(activeValue(list)).toBe('Zürich')

        component.highlightPrevious()
        expect(activeValue(list)).toBe('Bern')
    })

    it('only walks through the options left visible by the filter', () => {
        const { component, input, list } = mount()

        input.value = 'ber'
        component.open()

        component.highlightNext()
        component.highlightNext()
        expect(activeValue(list)).toBe('Bern')

        component.highlightNext()
        expect(activeValue(list)).toBe('Berlin')
    })

    it('jumps to the first and last visible option', () => {
        const { component, list } = mount()

        component.open()

        component.highlightLast()
        expect(activeValue(list)).toBe('Zürich')

        component.highlightFirst()
        expect(activeValue(list)).toBe('Berlin')
    })

    it('points the input at the highlighted option for screen readers', () => {
        const { component, input } = mount()

        component.open()
        component.highlightNext()

        expect(input.getAttribute('aria-activedescendant')).toBe('option-0')
    })

    it('stays put when no option is visible', () => {
        const { component, input } = mount()

        input.value = 'xyz'
        component.open()

        component.highlightNext()

        expect(component.activeIndex).toBe(-1)
    })
})

describe('choosing an option', () => {
    it('writes the highlighted option into the field and submits', () => {
        const { component, input } = mount()

        component.open()
        component.highlightNext()
        component.selectActive()

        expect(input.value).toBe('Berlin')
        expect(component.isOpen).toBe(false)
        expect(window.HTMLFormElement.prototype.submit).toHaveBeenCalledTimes(1)
    })

    it('submits the typed text as-is when nothing is highlighted', () => {
        const { component, input } = mount()

        input.value = 'Ber'
        component.open()
        component.selectActive()

        expect(input.value).toBe('Ber')
        expect(window.HTMLFormElement.prototype.submit).toHaveBeenCalledTimes(1)
    })

    it('takes the value of a clicked option', () => {
        const { component, input, list } = mount()

        component.open()
        component.select({ currentTarget: list.children[1] })

        expect(input.value).toBe('Bern')
        expect(component.isOpen).toBe(false)
        expect(window.HTMLFormElement.prototype.submit).toHaveBeenCalledTimes(1)
    })

    it('empties the field and submits when cleared', () => {
        const { component, input } = mount('Bern')

        component.clear()

        expect(input.value).toBe('')
        expect(component.isOpen).toBe(false)
        expect(window.HTMLFormElement.prototype.submit).toHaveBeenCalledTimes(1)
    })
})
