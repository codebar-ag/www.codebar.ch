export const readingProgress = () => ({
    init() {
        this.update()
        this.onScroll = () => this.update()
        window.addEventListener('scroll', this.onScroll, { passive: true })
        window.addEventListener('resize', this.onScroll, { passive: true })
    },

    destroy() {
        window.removeEventListener('scroll', this.onScroll)
        window.removeEventListener('resize', this.onScroll)
    },

    update() {
        const article = document.getElementById('article-body')
        if (!article) return

        const start = article.offsetTop
        const distance = article.offsetHeight - window.innerHeight
        const progress = distance <= 0 ? 1 : (window.scrollY - start) / distance

        this.$refs.bar.style.width = `${Math.min(100, Math.max(0, progress * 100))}%`
    },
})
