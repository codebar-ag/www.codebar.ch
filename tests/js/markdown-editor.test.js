import { afterEach, describe, expect, it, vi } from 'vitest'

import { markdownEditor, renderMarkdown } from '../../resources/js/markdown-editor.js'

function mount(value = '') {
    document.body.innerHTML = `
        <div class="editor">
            <textarea id="input">${value}</textarea>
        </div>
    `

    const input = document.querySelector('#input')

    const component = markdownEditor()
    component.$root = document.querySelector('.editor')
    component.$refs = { input }

    return { component, input }
}

afterEach(() => {
    document.body.innerHTML = ''
    vi.restoreAllMocks()
})

describe('the toolbar', () => {
    it('wraps the selection in bold markers', () => {
        const { component, input } = mount('hallo welt')
        input.setSelectionRange(0, 5)

        component.bold()

        expect(input.value).toBe('**hallo** welt')
    })

    it('wraps the selection in italic markers', () => {
        const { component, input } = mount('hallo welt')
        input.setSelectionRange(6, 10)

        component.italic()

        expect(input.value).toBe('hallo *welt*')
    })

    it('keeps the selection selected after wrapping', () => {
        const { component, input } = mount('hallo welt')
        input.setSelectionRange(0, 5)

        component.bold()

        expect(input.value.slice(input.selectionStart, input.selectionEnd)).toBe('hallo')
    })

    it('prefixes each selected line as a list item', () => {
        const { component, input } = mount('eins\nzwei')
        input.setSelectionRange(0, input.value.length)

        component.bulletList()

        expect(input.value).toBe('- eins\n- zwei')
    })

    it('numbers each selected line as an ordered list', () => {
        const { component, input } = mount('eins\nzwei\ndrei')
        input.setSelectionRange(0, input.value.length)

        component.orderedList()

        expect(input.value).toBe('1. eins\n2. zwei\n3. drei')
    })

    it('starts a list item when nothing is selected', () => {
        const { component, input } = mount('')
        input.setSelectionRange(0, 0)

        component.bulletList()

        expect(input.value).toBe('- ')
    })

    it('inserts a link skeleton around the selection', () => {
        const { component, input } = mount('codebar')
        input.setSelectionRange(0, 7)

        component.link()

        expect(input.value).toBe('[codebar](https://)')
    })

    it('notifies the form so save-on-edit picks the change up', () => {
        const { component, input } = mount('text')
        const seen = vi.fn()
        document.querySelector('.editor').addEventListener('input', seen)
        input.setSelectionRange(0, 4)

        component.bold()

        expect(seen).toHaveBeenCalledTimes(1)
    })
})

describe('the live preview', () => {
    it('renders paragraphs, emphasis, lists and links', () => {
        const html = renderMarkdown('Hallo **Welt** und *du*\n\n- eins\n- zwei\n\n1. a\n2. b\n\n[codebar](https://codebar.ch)')

        expect(html).toBe(
            '<p>Hallo <strong>Welt</strong> und <em>du</em></p>'
            + '<ul><li>eins</li><li>zwei</li></ul>'
            + '<ol><li>a</li><li>b</li></ol>'
            + '<p><a href="https://codebar.ch" rel="noopener noreferrer" target="_blank">codebar</a></p>',
        )
    })

    it('escapes html and neutralises unsafe links', () => {
        expect(renderMarkdown('<script>x</script> [go](javascript:alert)'))
            .toBe('<p>&lt;script&gt;x&lt;/script&gt; <a href="#" rel="noopener noreferrer" target="_blank">go</a></p>')
    })

    it('updates the preview from the textarea', () => {
        const { component } = mount('**fett**')

        component.init()

        expect(component.preview).toBe('<p><strong>fett</strong></p>')
        expect(component.hasPreview).toBe(true)
    })
})
