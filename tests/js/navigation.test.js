import { afterEach, describe, expect, it } from 'vitest'

import { navigation } from '../../resources/js/navigation.js'

function mount() {
    const component = navigation()

    return { component }
}

afterEach(() => {
    document.body.innerHTML = ''
})

describe('the mobile menu', () => {
    it('starts closed', () => {
        const { component } = mount()

        expect(component.open).toBe(false)
        expect(component.aria_expanded).toBe('false')
    })

    it('opens and closes again on toggle', () => {
        const { component } = mount()

        component.toggle()
        expect(component.open).toBe(true)
        expect(component.aria_expanded).toBe('true')

        component.toggle()
        expect(component.open).toBe(false)
        expect(component.aria_expanded).toBe('false')
    })

    it('closes no matter how often close is called', () => {
        const { component } = mount()

        component.toggle()
        component.close()
        component.close()

        expect(component.open).toBe(false)
        expect(component.aria_expanded).toBe('false')
    })
})
