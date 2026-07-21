import '../css/app.css'

import Alpine from '@alpinejs/csp'

window.Alpine = Alpine

Alpine.data('autoSubmit', () => ({
    submit() {
        this.$root.submit()
    },
}))

Alpine.data('combobox', () => ({
    isOpen: false,
    hasValue: false,

    init() {
        this.hasValue = this.$refs.input.value !== ''
    },

    open() {
        this.isOpen = true
        this.filter()
    },

    close() {
        this.isOpen = false
    },

    filter() {
        const query = this.$refs.input.value.toLowerCase()
        this.hasValue = query !== ''
        Array.from(this.$refs.list.children).forEach((item) => {
            item.style.display = item.dataset.value.toLowerCase().includes(query) ? '' : 'none'
        })
    },

    select(event) {
        this.$refs.input.value = event.currentTarget.dataset.value
        this.isOpen = false
        this.$root.closest('form').submit()
    },

    clear() {
        this.$refs.input.value = ''
        this.isOpen = false
        this.$root.closest('form').submit()
    },
}))

Alpine.data('navigation', () => ({
    open: false,
    toggle() {
        this.open = !this.open
    },

    get icon_rotate() {
        return this.open ? 'rotate-180' : 'rotate-0'
    },
}))

Alpine.start()
