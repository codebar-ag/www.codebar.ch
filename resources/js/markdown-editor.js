const escapeHtml = (text) => text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')

const safeHref = (href) => (/^https?:\/\//i.test(href) ? href : '#')

const inline = (text) => escapeHtml(text)
    .replace(/\[([^\]]+)\]\(([^)\s]+)\)/g, (_, label, href) => `<a href="${safeHref(href)}" rel="noopener noreferrer" target="_blank">${label}</a>`)
    .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
    .replace(/(^|[^*])\*([^*]+)\*/g, '$1<em>$2</em>')

export const renderMarkdown = (markdown) => {
    const blocks = []
    let list = null

    const closeList = () => {
        if (!list) return

        blocks.push(`<${list.tag}>${list.items.map((item) => `<li>${item}</li>`).join('')}</${list.tag}>`)
        list = null
    }

    markdown.replace(/\r\n?/g, '\n').split(/\n{2,}/).forEach((chunk) => {
        chunk.split('\n').forEach((line) => {
            const bullet = line.match(/^\s*[-*]\s+(.*)$/)
            const ordered = line.match(/^\s*\d+[.)]\s+(.*)$/)

            if (bullet || ordered) {
                const tag = bullet ? 'ul' : 'ol'
                if (list && list.tag !== tag) closeList()
                list ??= { tag, items: [] }
                list.items.push(inline((bullet ?? ordered)[1]))

                return
            }

            closeList()
            if (line.trim() !== '') blocks.push(`<p>${inline(line)}</p>`)
        })

        closeList()
    })

    return blocks.join('')
}

export const markdownEditor = () => ({
    preview: '',

    init() {
        this.render()
    },

    get isEmpty() {
        return this.preview === ''
    },

    get hasPreview() {
        return this.preview !== ''
    },

    render() {
        this.preview = renderMarkdown(this.$refs.input?.value ?? '')
    },

    bold() {
        this.wrapSelection('**', '**')
    },

    italic() {
        this.wrapSelection('*', '*')
    },

    bulletList() {
        this.prefixLines(() => '- ')
    },

    orderedList() {
        this.prefixLines((index) => `${index + 1}. `)
    },

    link() {
        this.wrapSelection('[', '](https://)')
    },

    wrapSelection(before, after) {
        const input = this.$refs.input
        const start = input.selectionStart
        const end = input.selectionEnd
        const selected = input.value.slice(start, end)

        input.value = input.value.slice(0, start) + before + selected + after + input.value.slice(end)
        input.focus()
        input.setSelectionRange(start + before.length, start + before.length + selected.length)

        this.notify(input)
    },

    prefixLines(prefix) {
        const input = this.$refs.input
        const start = input.selectionStart
        const end = input.selectionEnd
        const selected = input.value.slice(start, end)
        const prefixed = selected === ''
            ? prefix(0)
            : selected.split('\n').map((line, index) => prefix(index) + line).join('\n')

        input.value = input.value.slice(0, start) + prefixed + input.value.slice(end)
        input.focus()
        input.setSelectionRange(start, start + prefixed.length)

        this.notify(input)
    },

    notify(input) {
        input.dispatchEvent(new Event('input', { bubbles: true }))
        this.render()
    },
})
