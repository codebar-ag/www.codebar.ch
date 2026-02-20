import '../css/app.css'

import Alpine from '@alpinejs/csp'

window.Alpine = Alpine

Alpine.data('navigation', () => ({
    open: false,
    toggle() {
        this.open = !this.open
    },

    get icon_rotate() {
        return this.open ? 'rotate-180' : 'rotate-0'
    },
}))

Alpine.data('repositorySearch', (items) => ({
    search: new URLSearchParams(window.location.search).get('search') || '',
    items: items,

    get filteredItems() {
        if (!this.search) return this.items
        const term = this.search.toLowerCase()
        return this.items.filter(item =>
            item.title.toLowerCase().includes(term) ||
            item.teaser.toLowerCase().includes(term) ||
            (item.language || '').toLowerCase().includes(term) ||
            item.tags.some(tag => tag.toLowerCase().includes(term))
        )
    },

    get hasResults() {
        return this.filteredItems.length > 0
    },

    updateUrl() {
        const url = new URL(window.location)
        if (this.search) {
            url.searchParams.set('search', this.search)
        } else {
            url.searchParams.delete('search')
        }
        history.replaceState({}, '', url)
    },
}))

Alpine.start()
