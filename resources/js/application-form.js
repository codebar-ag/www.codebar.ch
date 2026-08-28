export const AUTOSAVE_DEBOUNCE_MS = 800

export const applicationForm = () => ({
    savedAt: '',
    failed: false,
    dirty: false,
    timer: null,

    get hasSaved() {
        return this.savedAt !== '' && !this.failed
    },

    get hasFailed() {
        return this.failed
    },

    init() {
        this.onInput = (event) => {
            if (event.target.type === 'file') return

            this.dirty = true
            this.schedule()
        }

        this.onChange = (event) => {
            if (event.target.type === 'file') {
                if (event.target.files.length) this.save(true)

                return
            }

            this.dirty = true
            this.save()
        }

        this.onPageHide = () => this.flush()

        this.$root.addEventListener('input', this.onInput)
        this.$root.addEventListener('change', this.onChange)
        window.addEventListener('pagehide', this.onPageHide)
    },

    schedule() {
        clearTimeout(this.timer)
        this.timer = setTimeout(() => this.save(), AUTOSAVE_DEBOUNCE_MS)
    },

    payload(withFiles = false) {
        const body = new FormData(this.$root)
        if (!withFiles) body.delete('documents[]')
        body.delete('action')
        body.set('_method', 'PATCH')

        return body
    },

    async save(withFiles = false) {
        clearTimeout(this.timer)

        try {
            const response = await fetch(this.$root.dataset.autosaveUrl, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                body: this.payload(withFiles),
            })

            if (!response.ok) throw new Error(`Autosave failed with ${response.status}`)

            const data = await response.json()
            this.savedAt = data.saved_at
            this.failed = false
            this.dirty = false

            if (withFiles) this.reloadPage()
        } catch {
            this.failed = true
        }
    },

    flush() {
        if (!this.dirty || !navigator.sendBeacon) return

        navigator.sendBeacon(this.$root.dataset.autosaveUrl, this.payload())
        this.dirty = false
    },

    reloadPage() {
        window.location.reload()
    },

    destroy() {
        clearTimeout(this.timer)
        this.$root.removeEventListener('input', this.onInput)
        this.$root.removeEventListener('change', this.onChange)
        window.removeEventListener('pagehide', this.onPageHide)
    },
})
