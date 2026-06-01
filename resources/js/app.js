import '../css/app.css'

import '@fontsource-variable/geist-mono/wght.css'

import Alpine from '@alpinejs/csp'
import { initLangFade } from './lang-fade'

window.Alpine = Alpine

Alpine.data('navigation', () => ({
    openDrawer: false,

    toggleDrawer() {
        this.openDrawer = !this.openDrawer
    },

    closeDrawer() {
        this.openDrawer = false
    },

    closeAll() {
        this.closeDrawer()
    },
}))

Alpine.data('gallery', () => ({
    images: [],
    hasMany: false,
    isOpen: false,
    activeIndex: 0,
    activeImageSrc: '',
    activeImageAlt: '',
    onKeydown: null,

    init() {
        try {
            this.images = JSON.parse(this.$el.dataset.images || '[]')
        } catch (e) {
            this.images = []
        }
        this.hasMany = this.images.length > 1
        this.onKeydown = (event) => {
            if (event.key === 'Escape') this.close()
            else if (event.key === 'ArrowRight') this.next()
            else if (event.key === 'ArrowLeft') this.prev()
        }
    },

    show(index) {
        const total = this.images.length
        if (total === 0) return
        this.activeIndex = ((index % total) + total) % total
        const image = this.images[this.activeIndex] || {}
        this.activeImageSrc = image.image || ''
        this.activeImageAlt = image.alt || ''
    },

    open(index) {
        this.show(index)
        this.isOpen = true
        document.body.style.overflow = 'hidden'
        document.addEventListener('keydown', this.onKeydown)
    },

    next() {
        this.show(this.activeIndex + 1)
    },

    prev() {
        this.show(this.activeIndex - 1)
    },

    close() {
        this.isOpen = false
        document.body.style.overflow = ''
        document.removeEventListener('keydown', this.onKeydown)
    },
}))

Alpine.start()
initLangFade()

const mapEls = document.querySelectorAll('[data-leaflet-map]')
if (mapEls.length > 0) {
    Promise.all([
        import('leaflet'),
        import('leaflet/dist/images/marker-icon.png'),
        import('leaflet/dist/images/marker-icon-2x.png'),
        import('leaflet/dist/images/marker-shadow.png'),
    ]).then(([{ default: L }, iconUrl, iconRetinaUrl, shadowUrl]) => {
        L.Icon.Default.mergeOptions({
            iconUrl: iconUrl.default,
            iconRetinaUrl: iconRetinaUrl.default,
            shadowUrl: shadowUrl.default,
        })

        mapEls.forEach((mapEl) => {
            const lat = parseFloat(mapEl.dataset.lat)
            const lng = parseFloat(mapEl.dataset.lng)
            if (!Number.isFinite(lat) || !Number.isFinite(lng)) return

            const map = L.map(mapEl, { scrollWheelZoom: false }).setView([lat, lng], 16)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                subdomains: ['a', 'b', 'c', 'd'],
                maxZoom: 20,
                attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors © <a href="https://carto.com/attributions" target="_blank" rel="noopener">CARTO</a>',
            }).addTo(map)
            L.marker([lat, lng]).addTo(map).bindPopup(mapEl.dataset.label || '')
        })
    })
}
