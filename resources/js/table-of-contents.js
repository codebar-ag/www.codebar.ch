export const tableOfContents = () => ({
    init() {
        const headings = Array.from(document.querySelectorAll('#article-body h2[id], #article-body h3[id]'))
        if (headings.length === 0) return

        this.observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return
                    this.$root.querySelectorAll('a[data-anchor]').forEach((link) => {
                        link.setAttribute('aria-current', link.dataset.anchor === entry.target.id ? 'true' : 'false')
                    })
                })
            },
            { rootMargin: '-80px 0px -70% 0px' },
        )

        headings.forEach((heading) => this.observer.observe(heading))
    },

    destroy() {
        this.observer?.disconnect()
    },
})
