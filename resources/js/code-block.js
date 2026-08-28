export const codeBlock = () => ({
    label: '',
    copied: false,

    init() {
        this.label = this.$root.dataset.labelCopy
        this.$refs.button.hidden = false
    },

    get stateClass() {
        return this.copied ? 'is-copied' : ''
    },

    copy() {
        const text = this.$refs.code.innerText

        const written = navigator.clipboard
            ? navigator.clipboard.writeText(text).catch(() => this.writeFallback(text))
            : Promise.resolve(this.writeFallback(text))

        written.then((ok) => {
            if (ok === false) return
            this.confirm()
        })
    },

    writeFallback(text) {
        const field = document.createElement('textarea')
        field.value = text
        field.setAttribute('readonly', '')
        field.style.position = 'fixed'
        field.style.opacity = '0'
        document.body.appendChild(field)
        field.select()

        const ok = document.execCommand('copy')
        field.remove()

        return ok
    },

    confirm() {
        this.copied = true
        this.label = this.$root.dataset.labelCopied
        clearTimeout(this.timeout)
        this.timeout = setTimeout(() => {
            this.copied = false
            this.label = this.$root.dataset.labelCopy
        }, 2000)
    },

    destroy() {
        clearTimeout(this.timeout)
    },
})
