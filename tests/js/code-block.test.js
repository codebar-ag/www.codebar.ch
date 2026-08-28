import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { codeBlock } from '../../resources/js/code-block.js'

const mounted = []

function mount() {
    document.body.innerHTML = `
        <div class="code-block" data-label-copy="Kopieren" data-label-copied="Kopiert!">
            <pre><code>console.log('hi')</code></pre>
            <button type="button" hidden>Kopieren</button>
        </div>
    `

    const root = document.querySelector('.code-block')
    const code = root.querySelector('code')
    const button = root.querySelector('button')

    Object.defineProperty(code, 'innerText', { value: "console.log('hi')", configurable: true })

    const component = codeBlock()
    component.$root = root
    component.$refs = { code, button }
    component.init()
    mounted.push(component)

    return { component, root, code, button }
}

beforeEach(() => {
    vi.useFakeTimers()
})

afterEach(() => {
    mounted.splice(0).forEach((component) => component.destroy())
    document.body.innerHTML = ''
    vi.useRealTimers()
    vi.unstubAllGlobals()
    vi.restoreAllMocks()
})

describe('revealing the button', () => {
    it('shows the server-hidden button once the script runs', () => {
        const { button } = mount()

        expect(button.hidden).toBe(false)
    })

    it('starts with the copy label and a neutral state', () => {
        const { component } = mount()

        expect(component.label).toBe('Kopieren')
        expect(component.copied).toBe(false)
        expect(component.stateClass).toBe('')
    })
})

describe('copying via the clipboard API', () => {
    it('writes the code text to the clipboard and confirms', async () => {
        const writeText = vi.fn().mockResolvedValue(undefined)
        vi.stubGlobal('navigator', { clipboard: { writeText } })
        const { component } = mount()

        component.copy()
        await vi.advanceTimersByTimeAsync(0)

        expect(writeText).toHaveBeenCalledWith("console.log('hi')")
        expect(component.copied).toBe(true)
        expect(component.label).toBe('Kopiert!')
        expect(component.stateClass).toBe('is-copied')
    })

    it('returns to the copy label after two seconds', async () => {
        vi.stubGlobal('navigator', { clipboard: { writeText: vi.fn().mockResolvedValue(undefined) } })
        const { component } = mount()

        component.copy()
        await vi.advanceTimersByTimeAsync(0)
        expect(component.copied).toBe(true)

        await vi.advanceTimersByTimeAsync(2000)

        expect(component.copied).toBe(false)
        expect(component.label).toBe('Kopieren')
        expect(component.stateClass).toBe('')
    })

    it('keeps one confirmation window when copy is pressed twice', async () => {
        vi.stubGlobal('navigator', { clipboard: { writeText: vi.fn().mockResolvedValue(undefined) } })
        const { component } = mount()

        component.copy()
        await vi.advanceTimersByTimeAsync(1500)
        component.copy()
        await vi.advanceTimersByTimeAsync(1500)

        expect(component.copied).toBe(true)

        await vi.advanceTimersByTimeAsync(500)

        expect(component.copied).toBe(false)
    })

    it('falls back to execCommand when the clipboard API rejects', async () => {
        vi.stubGlobal('navigator', { clipboard: { writeText: vi.fn().mockRejectedValue(new Error('denied')) } })
        document.execCommand = vi.fn().mockReturnValue(true)
        const { component } = mount()

        component.copy()
        await vi.advanceTimersByTimeAsync(0)

        expect(document.execCommand).toHaveBeenCalledWith('copy')
        expect(component.copied).toBe(true)

        delete document.execCommand
    })
})

describe('copying without the clipboard API', () => {
    it('copies through a hidden textarea and confirms', async () => {
        vi.stubGlobal('navigator', {})
        document.execCommand = vi.fn().mockReturnValue(true)
        const { component } = mount()

        component.copy()
        await vi.advanceTimersByTimeAsync(0)

        expect(document.execCommand).toHaveBeenCalledWith('copy')
        expect(component.copied).toBe(true)
        expect(document.querySelector('textarea')).toBeNull()

        delete document.execCommand
    })

    it('stays quiet when the fallback copy fails', async () => {
        vi.stubGlobal('navigator', {})
        document.execCommand = vi.fn().mockReturnValue(false)
        const { component } = mount()

        component.copy()
        await vi.advanceTimersByTimeAsync(0)

        expect(component.copied).toBe(false)
        expect(component.label).toBe('Kopieren')

        delete document.execCommand
    })
})

describe('teardown', () => {
    it('cancels the pending label reset on destroy', async () => {
        vi.stubGlobal('navigator', { clipboard: { writeText: vi.fn().mockResolvedValue(undefined) } })
        const { component } = mount()

        component.copy()
        await vi.advanceTimersByTimeAsync(0)
        component.destroy()

        await vi.advanceTimersByTimeAsync(2000)

        expect(component.copied).toBe(true)
    })
})
