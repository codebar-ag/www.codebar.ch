const TRIGGERS = ['pointerdown', 'pointermove', 'touchstart', 'keydown', 'scroll', 'focusin']

export const turnstile = () => ({
    loaded: false,

    init() {
        this.load = this.load.bind(this)

        TRIGGERS.forEach((trigger) => window.addEventListener(trigger, this.load, { once: true, passive: true }))
    },

    destroy() {
        TRIGGERS.forEach((trigger) => window.removeEventListener(trigger, this.load))
    },

    load() {
        if (this.loaded) {
            return
        }

        this.loaded = true
        this.destroy()

        const script = document.createElement('script')

        script.src = this.$root.dataset.turnstileSrc
        script.async = true

        document.head.append(script)
    },
})
