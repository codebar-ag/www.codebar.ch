import '../css/app.css'

import Alpine from '@alpinejs/csp'
import focus from '@alpinejs/focus'

import { introTabs } from './intro-tabs'
import { languageSuggestion } from './language-suggestion'

window.Alpine = Alpine
Alpine.plugin(focus)

Alpine.data('autoSubmit', () => ({
    submit() {
        this.$root.submit()
    },
}))

Alpine.data('combobox', () => ({
    isOpen: false,
    hasValue: false,
    activeIndex: -1,

    init() {
        this.hasValue = this.$refs.input.value !== ''
    },

    get aria_expanded() {
        return this.isOpen ? 'true' : 'false'
    },

    open() {
        this.isOpen = true
        this.filter()
    },

    close() {
        this.isOpen = false
        this.setActive(-1)
    },

    filter() {
        const query = this.$refs.input.value.toLowerCase()
        this.hasValue = query !== ''
        Array.from(this.$refs.list.children).forEach((item) => {
            item.style.display = item.dataset.value.toLowerCase().includes(query) ? '' : 'none'
        })
        this.setActive(-1)
    },

    visibleOptions() {
        return Array.from(this.$refs.list.children).filter((item) => item.style.display !== 'none')
    },

    setActive(index) {
        const options = this.visibleOptions()
        this.activeIndex = index

        Array.from(this.$refs.list.children).forEach((item) => {
            item.classList.remove('bg-gray-100')
            item.setAttribute('aria-selected', 'false')
        })

        const active = index >= 0 ? options[index] : null
        if (active) {
            active.classList.add('bg-gray-100')
            active.setAttribute('aria-selected', 'true')
            active.scrollIntoView({ block: 'nearest' })
            this.$refs.input.setAttribute('aria-activedescendant', active.id)
        } else {
            this.$refs.input.removeAttribute('aria-activedescendant')
        }
    },

    highlightNext() {
        if (!this.isOpen) {
            this.open()
            return
        }
        const count = this.visibleOptions().length
        if (count === 0) return
        this.setActive(this.activeIndex < count - 1 ? this.activeIndex + 1 : 0)
    },

    highlightPrevious() {
        if (!this.isOpen) {
            this.open()
            return
        }
        const count = this.visibleOptions().length
        if (count === 0) return
        this.setActive(this.activeIndex > 0 ? this.activeIndex - 1 : count - 1)
    },

    highlightFirst() {
        if (this.isOpen && this.visibleOptions().length > 0) this.setActive(0)
    },

    highlightLast() {
        const count = this.visibleOptions().length
        if (this.isOpen && count > 0) this.setActive(count - 1)
    },

    selectActive() {
        const active = this.activeIndex >= 0 ? this.visibleOptions()[this.activeIndex] : null
        if (active) {
            this.choose(active)
        } else {
            this.$root.closest('form').submit()
        }
    },

    select(event) {
        this.choose(event.currentTarget)
    },

    choose(option) {
        this.$refs.input.value = option.dataset.value
        this.close()
        this.$root.closest('form').submit()
    },

    clear() {
        this.$refs.input.value = ''
        this.close()
        this.$root.closest('form').submit()
    },
}))

Alpine.data('introTabs', introTabs)

Alpine.data('languageSuggestion', languageSuggestion)

Alpine.data('navigation', () => ({
    open: false,
    toggle() {
        this.open = !this.open
    },

    close() {
        this.open = false
    },

    get aria_expanded() {
        return this.open ? 'true' : 'false'
    },
}))

// Thin progress bar at the top of a long article. Width is written straight to the
// style attribute because the CSP build of Alpine cannot evaluate inline expressions.
Alpine.data('readingProgress', () => ({
    init() {
        this.update()
        this.onScroll = () => this.update()
        window.addEventListener('scroll', this.onScroll, { passive: true })
        window.addEventListener('resize', this.onScroll, { passive: true })
    },

    destroy() {
        window.removeEventListener('scroll', this.onScroll)
        window.removeEventListener('resize', this.onScroll)
    },

    update() {
        const article = document.getElementById('article-body')
        if (!article) return

        const start = article.offsetTop
        const distance = article.offsetHeight - window.innerHeight
        const progress = distance <= 0 ? 1 : (window.scrollY - start) / distance

        this.$refs.bar.style.width = `${Math.min(100, Math.max(0, progress * 100))}%`
    },
}))

// Marks the table-of-contents entry belonging to the section currently on screen.
Alpine.data('tableOfContents', () => ({
    init() {
        const headings = Array.from(document.querySelectorAll('#article-body h2[id], #article-body h3[id]'))
        if (headings.length === 0) return

        this.observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return
                    this.$root.querySelectorAll('a[data-anchor]').forEach((link) => {
                        link.setAttribute('aria-current', link.dataset.anchor === entry.target.id ? 'true' : 'false')
                    })
                })
            },
            { rootMargin: '-80px 0px -70% 0px' },
        )

        headings.forEach((heading) => this.observer.observe(heading))
    },

    destroy() {
        this.observer?.disconnect()
    },
}))

// Click-to-load video: nothing is requested from the video host until asked for.
Alpine.data('videoEmbed', () => ({
    loaded: false,
    embedSrc: '',

    load() {
        this.embedSrc = `${this.$root.dataset.src}${this.$root.dataset.src.includes('?') ? '&' : '?'}autoplay=1`
        this.loaded = true
    },
}))

// Copy button on a code block. The button is server-rendered as hidden and only
// revealed here, so a reader without JavaScript never sees a control that cannot work.
Alpine.data('codeBlock', () => ({
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

        // navigator.clipboard exists only in a secure context, and even there it
        // rejects when the document is not focused — the hidden-textarea route is
        // what keeps the button working over plain http and in older Safari.
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
}))

Alpine.start()
