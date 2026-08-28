export const videoEmbed = () => ({
    loaded: false,
    embedSrc: '',

    load() {
        this.embedSrc = `${this.$root.dataset.src}${this.$root.dataset.src.includes('?') ? '&' : '?'}autoplay=1`
        this.loaded = true
    },
})
