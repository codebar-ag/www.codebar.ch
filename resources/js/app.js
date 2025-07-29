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

Alpine.start()
