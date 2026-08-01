export const LANGUAGE_SUGGESTION_KEY = 'language-suggestion'

export const languageSuggestion = () => ({
    init() {
        if (!this.consume()) return
        if (this.dismissed()) return

        const wanted = this.preferred()
        if (wanted === this.$root.dataset.language) return

        const option = this.$root.querySelector(`[data-language="${wanted}"]`)
        if (!option) return

        option.hidden = false
        this.$root.hidden = false
    },

    dismiss() {
        this.$root.hidden = true

        try {
            window.localStorage.setItem(LANGUAGE_SUGGESTION_KEY, '1')
        } catch {
            /* empty */
        }
    },

    /**
     * The language of the page this visitor would rather be on: the first of
     * their accepted languages the site actually speaks, and the fallback
     * locale when it speaks none of them.
     */
    preferred() {
        const offered = [
            this.$root.dataset.language,
            ...Array.from(this.$root.querySelectorAll('[data-language]')).map((option) => option.dataset.language),
        ]

        for (const tag of this.accepted()) {
            if (typeof tag !== 'string') continue

            const primary = tag.toLowerCase().split('-')[0]
            if (offered.includes(primary)) return primary
        }

        return this.$root.dataset.fallback
    },

    accepted() {
        const languages = window.navigator.languages

        if (Array.isArray(languages) && languages.length > 0) return languages

        return [window.navigator.language]
    },

    /**
     * Reads the marker the entry redirect leaves behind and clears it in the
     * same breath: the suggestion belongs to that one arrival, not to every
     * later visit to the start page.
     */
    consume() {
        const name = this.$root.dataset.cookie

        const found = document.cookie.split(';').some((pair) => {
            const [key, ...value] = pair.trim().split('=')

            return key === name && value.join('=') !== ''
        })

        if (found) {
            document.cookie = `${name}=; Max-Age=0; path=/`
        }

        return found
    },

    dismissed() {
        try {
            return window.localStorage.getItem(LANGUAGE_SUGGESTION_KEY) !== null
        } catch {
            return false
        }
    },
})
