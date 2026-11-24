export const INTRO_TAB_KEY = 'intro-tab'
export const INTRO_WORD_INTERVAL = 16
export const INTRO_WORDS_PER_TICK = 2

export const introTabs = () => ({
    init() {
        this.restore()
        this.prepare()

        this.onChange = () => {
            this.remember()
            this.play()
        }
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

        this.play()
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
        this.play()
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

    prepare() {
        this.words = new Map()

        if (window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches) return

        const panels = Array.from(this.$root.querySelectorAll('[data-panel]'))
        if (panels.length === 0) return

        panels.forEach((panel) => this.words.set(panel.dataset.panel, this.wrap(panel)))
        this.$root.classList.add('intro-tabs--animated')
    },

    wrap(panel) {
        const container = panel.querySelector('[data-intro-text]')
        if (!container) return []

        const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT)
        const nodes = []
        while (walker.nextNode()) nodes.push(walker.currentNode)

        const words = []

        nodes.forEach((node) => {
            if (node.textContent.trim() === '') return

            const fragment = document.createDocumentFragment()

            node.textContent.split(/(\s+)/).forEach((part) => {
                if (part === '') return

                if (/^\s+$/.test(part)) {
                    fragment.append(part)
                    return
                }

                const word = document.createElement('span')
                word.className = 'intro-tabs__word'
                word.textContent = part
                fragment.append(word)
                words.push(word)
            })

            node.replaceWith(fragment)
        })

        return words
    },

    play() {
        if (!this.words || this.words.size === 0) return

        window.clearTimeout(this.timer)
        this.$root.querySelectorAll('.is-on').forEach((element) => element.classList.remove('is-on'))

        const current = this.tabs().find((tab) => tab.checked)
        if (!current) return

        this.reveal(current)

        const words = this.words.get(current.dataset.tab) ?? []
        const actions = this.$root.querySelector(`[data-panel="${current.dataset.tab}"] .intro-tabs__actions`)
        let index = 0

        const tick = () => {
            for (let count = 0; count < INTRO_WORDS_PER_TICK && index < words.length; count++) {
                words[index++].classList.add('is-on')
            }

            if (index < words.length) {
                this.timer = window.setTimeout(tick, INTRO_WORD_INTERVAL)
                return
            }

            actions?.classList.add('is-on')
        }

        tick()
    },

    reveal(tab) {
        const strip = this.$root.querySelector('[data-intro-tabs]')
        const label = tab.closest('label')
        if (!strip || !label || strip.scrollWidth <= strip.clientWidth) return

        strip.scrollTo?.({
            left: label.offsetLeft - (strip.clientWidth - label.offsetWidth) / 2,
            behavior: 'smooth',
        })
    },

    destroy() {
        window.clearTimeout(this.timer)
        window.removeEventListener('keydown', this.onKey)
        this.$root.removeEventListener('change', this.onChange)
    },
})
