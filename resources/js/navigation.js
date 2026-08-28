export const navigation = () => ({
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
})
