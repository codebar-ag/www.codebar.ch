import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { AUTOSAVE_DEBOUNCE_MS, applicationForm } from '../../resources/js/application-form.js'

const mounted = []

function mount() {
    document.body.innerHTML = `
        <form data-autosave-url="/stellen/praktikum/bewerbung/1?signature=abc">
            <input type="hidden" name="_token" value="csrf-token">
            <input type="text" name="first_name" value="Mina">
            <textarea name="about">Hallo</textarea>
            <input type="file" name="documents[]">
            <button type="submit" name="action" value="save">Save</button>
        </form>
    `

    const root = document.querySelector('form')

    const component = applicationForm()
    component.$root = root
    component.reloadPage = vi.fn()
    component.init()
    mounted.push(component)

    return { component, root }
}

function type(root, selector, value) {
    const field = root.querySelector(selector)
    field.value = value
    field.dispatchEvent(new Event('input', { bubbles: true }))
}

function commit(root, selector) {
    root.querySelector(selector).dispatchEvent(new Event('change', { bubbles: true }))
}

beforeEach(() => {
    vi.useFakeTimers()
    vi.stubGlobal('fetch', vi.fn().mockResolvedValue({
        ok: true,
        json: () => Promise.resolve({ saved_at: '14:32:05', uploaded: 0 }),
    }))
})

afterEach(() => {
    mounted.splice(0).forEach((component) => component.destroy())
    document.body.innerHTML = ''
    vi.useRealTimers()
    vi.restoreAllMocks()
})

describe('save on edit', () => {
    it('sends one debounced request after typing stops', async () => {
        const { root } = mount()

        type(root, '[name=first_name]', 'M')
        type(root, '[name=first_name]', 'Mi')
        type(root, '[name=first_name]', 'Mina')

        expect(fetch).not.toHaveBeenCalled()

        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        expect(fetch).toHaveBeenCalledTimes(1)

        const [url, options] = fetch.mock.calls[0]
        expect(url).toBe('/stellen/praktikum/bewerbung/1?signature=abc')
        expect(options.method).toBe('POST')
        expect(options.body.get('_method')).toBe('PATCH')
        expect(options.body.get('first_name')).toBe('Mina')
        expect(options.body.get('_token')).toBe('csrf-token')
    })

    it('saves immediately when a field is committed', async () => {
        const { root } = mount()

        commit(root, '[name=first_name]')
        await vi.advanceTimersByTimeAsync(0)

        expect(fetch).toHaveBeenCalledTimes(1)
    })

    it('keeps files and the action out of the text autosave payload', async () => {
        const { root } = mount()

        type(root, '[name=about]', 'Neuer Text')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        const body = fetch.mock.calls[0][1].body
        expect(body.has('documents[]')).toBe(false)
        expect(body.has('action')).toBe(false)
    })

    it('uploads selected files immediately and reloads to show them', async () => {
        const { component, root } = mount()

        const fileInput = root.querySelector('[name="documents[]"]')
        Object.defineProperty(fileInput, 'files', { value: [new File(['pdf'], 'cv.pdf')] })
        fileInput.dispatchEvent(new Event('change', { bubbles: true }))
        await vi.advanceTimersByTimeAsync(0)

        expect(fetch).toHaveBeenCalledTimes(1)
        expect(fetch.mock.calls[0][1].body.has('documents[]')).toBe(true)
        expect(component.reloadPage).toHaveBeenCalledTimes(1)
    })

    it('does nothing when the file selection is cancelled', async () => {
        const { root } = mount()

        commit(root, '[name="documents[]"]')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        expect(fetch).not.toHaveBeenCalled()
    })

    it('shows the saved time on success', async () => {
        const { component, root } = mount()

        type(root, '[name=about]', 'Neuer Text')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        expect(component.savedAt).toBe('14:32:05')
        expect(component.hasSaved).toBe(true)
        expect(component.hasFailed).toBe(false)
    })

    it('flags a failed autosave instead of losing it silently', async () => {
        fetch.mockResolvedValue({ ok: false, status: 500 })
        const { component, root } = mount()

        type(root, '[name=about]', 'Neuer Text')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        expect(component.hasFailed).toBe(true)
        expect(component.hasSaved).toBe(false)
    })

    it('recovers once a later autosave succeeds', async () => {
        fetch.mockResolvedValueOnce({ ok: false, status: 500 })
        const { component, root } = mount()

        type(root, '[name=about]', 'Erster Versuch')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)
        expect(component.hasFailed).toBe(true)

        type(root, '[name=about]', 'Zweiter Versuch')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        expect(component.hasFailed).toBe(false)
        expect(component.hasSaved).toBe(true)
    })

    it('flushes unsaved changes via beacon when the page is left', async () => {
        const beacon = vi.fn().mockReturnValue(true)
        vi.stubGlobal('navigator', { sendBeacon: beacon })
        const { root } = mount()

        type(root, '[name=about]', 'Ungespeichert')
        window.dispatchEvent(new Event('pagehide'))

        expect(beacon).toHaveBeenCalledTimes(1)
        expect(beacon.mock.calls[0][0]).toBe('/stellen/praktikum/bewerbung/1?signature=abc')
    })

    it('sends no beacon when everything is already saved', async () => {
        const beacon = vi.fn().mockReturnValue(true)
        vi.stubGlobal('navigator', { sendBeacon: beacon })
        const { root } = mount()

        type(root, '[name=about]', 'Text')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        window.dispatchEvent(new Event('pagehide'))

        expect(beacon).not.toHaveBeenCalled()
    })

    it('stops autosaving after destroy', async () => {
        const { component, root } = mount()

        component.destroy()

        type(root, '[name=about]', 'Zu spät')
        commit(root, '[name=about]')
        await vi.advanceTimersByTimeAsync(AUTOSAVE_DEBOUNCE_MS)

        expect(fetch).not.toHaveBeenCalled()
    })
})
