export const INTRO_TAB_KEY = 'intro-tab'

export const introTabs = () => ({
    init() {
        this.restore()

        this.onChange = () => this.remember()
        this.$root.addEventListener('change', this.onChange)

        this.onKey = (event) => {
            if (event.metaKey || event.ctrlKey || event.altKey) return

            const target = event.target
            if (target.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName)) return

            const shortcut = this.tabs().find((tab) => tab.dataset.shortcut === event.key)
            if (shortcut) {
                event.preventDefault()
                this.select(shortcut)
                return
            }

            const direction = event.key === 'ArrowRight' ? 1 : event.key === 'ArrowLeft' ? -1 : 0
            if (direction === 0 || !this.inView()) return

            event.preventDefault()
            this.step(direction)
        }

        window.addEventListener('keydown', this.onKey)
    },

    tabs() {
        return Array.from(this.$root.querySelectorAll('input[data-tab]'))
    },

    shortcuts() {
        return this.tabs().filter((tab) => tab.dataset.shortcut)
    },

    select(tab) {
        tab.checked = true
        this.remember()
    },

    step(direction) {
        const tabs = this.shortcuts()
        const current = tabs.findIndex((tab) => tab.checked)

        if (current === -1) {
            this.select(direction > 0 ? tabs[0] : tabs[tabs.length - 1])
            return
        }

        this.select(tabs[(current + direction + tabs.length) % tabs.length])
    },

    inView() {
        const box = this.$root.getBoundingClientRect()

        return box.bottom > 0 && box.top < window.innerHeight
    },

    restore() {
        const stored = this.read()
        if (stored === null) return

        const tab = this.tabs().find((candidate) => candidate.dataset.tab === stored)
        if (tab) tab.checked = true
    },

    remember() {
        const current = this.tabs().find((tab) => tab.checked)
        if (!current) return

        try {
            window.sessionStorage.setItem(INTRO_TAB_KEY, current.dataset.tab)
        } catch {
            /* empty */
        }
    },

    read() {
        try {
            return window.sessionStorage.getItem(INTRO_TAB_KEY)
        } catch {
            return null
        }
    },

    destroy() {
        window.removeEventListener('keydown', this.onKey)
        this.$root.removeEventListener('change', this.onChange)
    },
})
