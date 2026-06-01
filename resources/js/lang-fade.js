/*
 * Language switcher: out-phase fade + thumb-flip.
 *
 * On submit:
 *   1. Flip the toggle's data-lang-active attribute so the thumb visibly slides
 *      to the new side while the page fades.
 *   2. Write a sessionStorage flag for the in-phase fade-in (read by the inline
 *      <head> script in layouts/app.blade.php).
 *   3. Add `is-fading-out` to <html>; <main> fades to opacity 0.
 *   4. Submit the form when <main>'s transition ends, with a hard timeout
 *      fallback in case the event never fires.
 */

const STORAGE_KEY = 'langFadeIn'
const FALLBACK_DURATION_MS = 700

export function initLangFade() {
    const forms = document.querySelectorAll('form[action$="language/update"]')
    forms.forEach((form) => {
        form.addEventListener('submit', (event) => onSubmit(event, form))
    })
}

function onSubmit(event, form) {
    if (event.defaultPrevented) return
    event.preventDefault()

    const toggle = form.querySelector('[data-lang-active]')
    if (toggle) {
        toggle.dataset.langActive = toggle.dataset.langActive === 'en' ? 'de' : 'en'
    }

    try {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify({ t: Date.now() }))
    } catch (_) {
        // sessionStorage disabled — proceed without in-phase fade
    }

    document.documentElement.classList.add('is-fading-out')

    let submitted = false
    const submit = () => {
        if (submitted) return
        submitted = true
        form.submit()
    }

    // Wait for <main>'s opacity transition specifically — other transitions
    // (the toggle thumb) may end first and we don't want to submit early.
    const main = document.querySelector('main')
    if (main) {
        const onMainEnd = (e) => {
            if (e.target === main && e.propertyName === 'opacity') {
                main.removeEventListener('transitionend', onMainEnd)
                submit()
            }
        }
        main.addEventListener('transitionend', onMainEnd)
    }
    setTimeout(submit, FALLBACK_DURATION_MS)
}
