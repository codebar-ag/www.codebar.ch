import '../css/app.css'

import Alpine from '@alpinejs/csp'

window.Alpine = Alpine

Alpine.data('navigation', () => ({
    openDrawer: false,
    activePanel: null,
    sections: {
        work: true,
        company: false,
        resources: false,
    },

    toggleDrawer() {
        this.openDrawer = !this.openDrawer
        if (this.openDrawer) {
            this.closePanel()
        }
    },

    closeDrawer() {
        this.openDrawer = false
    },

    openPanel(key) {
        this.activePanel = key
    },

    closePanel() {
        this.activePanel = null
    },

    togglePanel(key) {
        this.activePanel = this.activePanel === key ? null : key
    },

    isPanel(key) {
        return this.activePanel === key
    },

    toggleSection(key) {
        this.sections[key] = !this.sections[key]
    },

    isSectionOpen(key) {
        return this.sections[key] ?? false
    },

    closeAll() {
        this.closeDrawer()
        this.closePanel()
    },

    get menuIconRotation() {
        return this.openDrawer ? 'rotate-180' : 'rotate-0'
    },
}))

Alpine.start()
