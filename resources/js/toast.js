export const TOAST_DISMISS_MS = 6000

export const toast = () => ({
    visible: true,
    timer: null,

    init() {
        this.timer = setTimeout(() => {
            this.visible = false
        }, TOAST_DISMISS_MS)
    },

    close() {
        clearTimeout(this.timer)
        this.visible = false
    },

    destroy() {
        clearTimeout(this.timer)
    },
})
