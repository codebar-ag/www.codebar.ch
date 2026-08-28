import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { TOAST_DISMISS_MS, toast } from '../../resources/js/toast.js'

function mount() {
    const component = toast()
    component.$root = document.createElement('div')
    component.init()

    return component
}

beforeEach(() => {
    vi.useFakeTimers()
})

afterEach(() => {
    vi.useRealTimers()
    vi.restoreAllMocks()
})

describe('the status toast', () => {
    it('is visible when it appears', () => {
        expect(mount().visible).toBe(true)
    })

    it('dismisses itself after the timeout', () => {
        const component = mount()

        vi.advanceTimersByTime(TOAST_DISMISS_MS)

        expect(component.visible).toBe(false)
    })

    it('stays visible until the timeout', () => {
        const component = mount()

        vi.advanceTimersByTime(TOAST_DISMISS_MS - 1)

        expect(component.visible).toBe(true)
    })

    it('closes immediately via the close button', () => {
        const component = mount()

        component.close()

        expect(component.visible).toBe(false)
    })

    it('clears its timer on destroy', () => {
        const component = mount()
        const spy = vi.spyOn(globalThis, 'clearTimeout')

        component.destroy()

        expect(spy).toHaveBeenCalled()
    })
})
