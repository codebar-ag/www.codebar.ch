export const combobox = () => ({
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
})
