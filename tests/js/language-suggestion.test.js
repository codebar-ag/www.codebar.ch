import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'

import { LANGUAGE_SUGGESTION_KEY, languageSuggestion } from '../../resources/js/language-suggestion.js'

const COOKIE = 'entry_redirect'

function mount({ language = 'de', fallback = 'en', alternates = ['en'] } = {}) {
    document.body.innerHTML = `
        <div id="suggestion"
             data-cookie="${COOKIE}"
             data-language="${language}"
             data-fallback="${fallback}"
             hidden>
            ${alternates.map((tag) => `
                <div data-language="${tag}" hidden>
                    <a href="/${tag}-ch">switch</a>
                    <button type="button">dismiss</button>
                </div>
            `).join('')}
        </div>
    `

    const root = document.querySelector('#suggestion')

    const component = languageSuggestion()
    component.$root = root
    component.init()

    return { component, root }
}

function visible() {
    const root = document.querySelector('#suggestion')
    if (!root || root.hidden) return null

    const shown = Array.from(root.querySelectorAll('[data-language]')).filter((option) => !option.hidden)

    return shown.map((option) => option.dataset.language)
}

function setEntryCookie(value = '1') {
    document.cookie = `${COOKIE}=${value}; path=/`
}

function speaks(...languages) {
    vi.spyOn(window.navigator, 'languages', 'get').mockReturnValue(languages)
    vi.spyOn(window.navigator, 'language', 'get').mockReturnValue(languages[0])
}

function clearCookies() {
    document.cookie.split(';').forEach((pair) => {
        const name = pair.trim().split('=')[0]
        if (name) document.cookie = `${name}=; Max-Age=0; path=/`
    })
}

beforeEach(() => {
    clearCookies()
    window.localStorage.clear()
})

afterEach(() => {
    document.body.innerHTML = ''
    vi.restoreAllMocks()
})

describe('only after an arrival through the domain root', () => {
    it('stays hidden without the entry cookie, however foreign the browser', () => {
        speaks('en-US')

        mount()

        expect(visible()).toBeNull()
    })

    it('shows the suggestion when the entry cookie is there', () => {
        setEntryCookie()
        speaks('en-US')

        mount()

        expect(visible()).toEqual(['en'])
    })

    it('clears the cookie so a later visit to the start page stays quiet', () => {
        setEntryCookie()
        speaks('en-US')

        mount()
        expect(document.cookie).not.toContain(COOKIE)

        document.body.innerHTML = ''
        mount()

        expect(visible()).toBeNull()
    })

    it('clears the cookie even when it decides against suggesting anything', () => {
        setEntryCookie()
        speaks('de-CH')

        mount()

        expect(document.cookie).not.toContain(COOKIE)
    })

    it('ignores a cookie whose name merely ends in the one it looks for', () => {
        document.cookie = `not_${COOKIE}=1; path=/`
        speaks('en-US')

        mount()

        expect(visible()).toBeNull()
    })

    it('ignores an emptied cookie', () => {
        setEntryCookie('')
        speaks('en-US')

        mount()

        expect(visible()).toBeNull()
    })

    it('finds the cookie among others', () => {
        document.cookie = 'first=a; path=/'
        setEntryCookie()
        document.cookie = 'last=z; path=/'
        speaks('en-US')

        mount()

        expect(visible()).toEqual(['en'])
    })
})

describe('choosing the language to suggest', () => {
    beforeEach(() => {
        setEntryCookie()
    })

    it('says nothing to a German browser, which already has the page it wants', () => {
        speaks('de-CH', 'de')

        mount()

        expect(visible()).toBeNull()
    })

    it('suggests English to an English browser', () => {
        speaks('en-GB')

        mount()

        expect(visible()).toEqual(['en'])
    })

    it('reads the region off the tag, whatever its case', () => {
        speaks('EN-us')

        mount()

        expect(visible()).toEqual(['en'])
    })

    it('falls back to English for a language the site does not speak', () => {
        speaks('fr-CH')

        mount()

        expect(visible()).toEqual(['en'])
    })

    it('honours the browser order and stays put when German outranks English', () => {
        speaks('de-CH', 'en-US')

        mount()

        expect(visible()).toBeNull()
    })

    it('honours the browser order and suggests English when English outranks German', () => {
        speaks('en-US', 'de-CH')

        mount()

        expect(visible()).toEqual(['en'])
    })

    it('skips languages the site does not speak to reach one it does', () => {
        speaks('fr-CH', 'it-CH', 'de-CH')

        mount()

        expect(visible()).toBeNull()
    })

    it('falls back to the plain tag when the browser exposes no list', () => {
        vi.spyOn(window.navigator, 'languages', 'get').mockReturnValue(undefined)
        vi.spyOn(window.navigator, 'language', 'get').mockReturnValue('en')

        mount()

        expect(visible()).toEqual(['en'])
    })

    it('falls back to the plain tag when the list is empty', () => {
        vi.spyOn(window.navigator, 'languages', 'get').mockReturnValue([])
        vi.spyOn(window.navigator, 'language', 'get').mockReturnValue('de-CH')

        mount()

        expect(visible()).toBeNull()
    })

    it('falls back to English when the browser exposes no language at all', () => {
        vi.spyOn(window.navigator, 'languages', 'get').mockReturnValue([])
        vi.spyOn(window.navigator, 'language', 'get').mockReturnValue(undefined)

        expect(() => mount()).not.toThrow()

        expect(visible()).toEqual(['en'])
    })

    it('suggests nothing on the fallback page itself to a browser it cannot place', () => {
        speaks('fr-CH')

        mount({ language: 'en', alternates: ['de'] })

        expect(visible()).toBeNull()
    })

    it('suggests German on the English page to a German browser', () => {
        speaks('de-CH')

        mount({ language: 'en', alternates: ['de'] })

        expect(visible()).toEqual(['de'])
    })

    it('reveals only the matching language when several are offered', () => {
        speaks('fr-FR')

        mount({ language: 'de', alternates: ['en', 'fr'] })

        expect(visible()).toEqual(['fr'])
    })
})

describe('dismissing', () => {
    it('hides the suggestion and remembers the choice', () => {
        setEntryCookie()
        speaks('en-US')

        const { component, root } = mount()
        component.dismiss()

        expect(root.hidden).toBe(true)
        expect(window.localStorage.getItem(LANGUAGE_SUGGESTION_KEY)).toBe('1')
    })

    it('stays away on the next arrival through the domain root', () => {
        window.localStorage.setItem(LANGUAGE_SUGGESTION_KEY, '1')
        setEntryCookie()
        speaks('en-US')

        mount()

        expect(visible()).toBeNull()
    })

    it('still consumes the cookie once dismissed', () => {
        window.localStorage.setItem(LANGUAGE_SUGGESTION_KEY, '1')
        setEntryCookie()
        speaks('en-US')

        mount()

        expect(document.cookie).not.toContain(COOKIE)
    })

    it('keeps working when storage is blocked', () => {
        vi.spyOn(window.localStorage.__proto__, 'getItem').mockImplementation(() => {
            throw new Error('access denied')
        })
        vi.spyOn(window.localStorage.__proto__, 'setItem').mockImplementation(() => {
            throw new Error('access denied')
        })
        setEntryCookie()
        speaks('en-US')

        let mounted
        expect(() => {
            mounted = mount()
        }).not.toThrow()

        expect(visible()).toEqual(['en'])
        expect(() => mounted.component.dismiss()).not.toThrow()
        expect(mounted.root.hidden).toBe(true)
    })
})

describe('markup it cannot act on', () => {
    it('stays hidden when the page offers no alternate at all', () => {
        setEntryCookie()
        speaks('en-US')

        mount({ alternates: [] })

        expect(visible()).toBeNull()
    })

    it('stays hidden when the fallback language has no link on the page', () => {
        setEntryCookie()
        speaks('fr-CH')

        mount({ language: 'de', fallback: 'it', alternates: ['en'] })

        expect(visible()).toBeNull()
    })
})
