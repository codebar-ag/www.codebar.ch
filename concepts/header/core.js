(() => {
    const D = {
        title: 'Schön, bist du da',
        legal: 'codebar Solutions AG',
        legend: 'Was möchtest du über uns wissen?',
        prev: 'Vorheriger Abschnitt',
        nextSection: 'Nächster Abschnitt',
        next: 'weiter: ',
        expertise: 'schau dir unsere Expertise an',
        cta: 'Zur Übersicht',
        sections: [
            {
                cmd: 'wer-wir-sind',
                title: 'Wer wir sind',
                teaser: 'Wer wir sind und wie wir denken.',
                text: 'Wir sind codebar – ein kleines Team aus der Region Basel. Wir erwecken Ideen zum Leben: von der ersten Skizze bis zur Software im täglichen Einsatz. Dabei verstehen wir uns als Bindeglied zwischen Business und Technik – wir hören zu, denken wirtschaftlich mit und bauen auf offene Technologien.',
            },
            {
                cmd: 'was-wir-machen',
                title: 'Was wir machen',
                teaser: 'Vier Bereiche, ein Weg.',
                text: 'Unsere Arbeit deckt den ganzen Weg einer digitalen Lösung ab:',
                items: [
                    ['Konzeption', 'wir schärfen deine Anforderungen und machen Ideen mit klickbaren Prototypen früh greifbar.'],
                    ['Softwareentwicklung', 'Portale, Schnittstellen und Automatisierungen, entwickelt mit offenen Technologien wie Laravel.'],
                    ['DMS/ECM', 'als DocuWare Silver &amp; Cloud Partner begleiten wir dich ins papierlose Büro und automatisieren deine Prozesse.'],
                    ['Open Source ERP', 'Odoo setzen wir selbst ein und begleiten dich als Odoo-Partner Schritt für Schritt bei der Einführung.'],
                ],
            },
            {
                cmd: 'wie-wir-arbeiten',
                title: 'Wie wir arbeiten',
                teaser: 'Zuhören, ehrlich rechnen, umsetzen.',
                text: 'Am Anfang hören wir dir zu. Denn eine gute Lösung beginnt damit, das Problem zu verstehen – und ehrlich zu prüfen, ob sie sich für dich rechnet. Danach entwickeln wir gemeinsam ein Konzept, das sich nach deinen Prozessen richtet und nach den Menschen, die täglich damit arbeiten. Liegt die Umsetzung in unserer Expertise, begleiten wir dich bis in den Betrieb. Wenn nicht, sagen wir es offen – und freuen uns, wenn andere unsere Pläne verwirklichen.',
            },
        ],
    }
    const S = D.sections
    const HV = (window.HV = { D, S, list: [] })

    HV.add = (v) => HV.list.push(v)
    HV.dots = '<span class="hv-dots" aria-hidden="true"><i></i><i></i><i></i></span>'
    HV.kbd = (n) => `<kbd class="hv-kbd" aria-hidden="true">${n}</kbd>`
    HV.mod = (n, m) => ((n % m) + m) % m

    HV.text = (i) => {
        const s = S[i]
        let h = `<p>${s.text}</p>`
        if (s.items) h += `<ul class="hv-list">${s.items.map(([b, t]) => `<li><b>${b}</b> – ${t}</li>`).join('')}</ul>`
        return `<div class="hv-text">${h}</div>`
    }

    HV.actions = (i) =>
        i < S.length - 1
            ? `<p class="hv-actions"><button type="button" class="hv-btn" data-go="${i + 1}"><span aria-hidden="true">→</span>${D.next}${S[i + 1].cmd}</button></p>`
            : `<p class="hv-actions"><a class="hv-btn hv-btn--solid" href="#"><span aria-hidden="true">→</span>${D.expertise}</a><a class="hv-btn" href="#"><span aria-hidden="true">→</span>${D.cta}</a></p>`

    HV.body = (i) => `<h2 class="sr">${S[i].title}</h2>${HV.text(i)}${HV.actions(i)}`

    HV.stack = () => `<div class="hv-stack">${S.map((s, i) => `<div class="hv-pan" data-p="${i}">${HV.body(i)}</div>`).join('')}</div>`

    HV.tabs = (cls) =>
        S.map((s, i) => `<button type="button" class="${cls}" role="tab" data-go="${i}">${HV.kbd(i + 1)}<span>${s.cmd}</span></button>`).join('')

    HV.sel = (scope, i) =>
        scope.querySelectorAll('[role="tab"][data-go]').forEach((b) => b.setAttribute('aria-selected', String(+b.dataset.go === i)))

    HV.play = (el) => {
        el.querySelectorAll('h2,p,li').forEach((n, k) => n.style.setProperty('--i', k))
        el.classList.remove('hv-in')
        void el.offsetWidth
        el.classList.add('hv-in')
    }

    HV.show = (scope, i, play = true) =>
        scope.querySelectorAll('.hv-pan').forEach((p) => {
            const on = +p.dataset.p === i
            p.classList.toggle('on', on)
            p.inert = !on
            if (on && play) HV.play(p)
        })

    HV.words = (el) => {
        const out = []
        const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT)
        const nodes = []
        while (walker.nextNode()) nodes.push(walker.currentNode)
        nodes.forEach((n) => {
            const f = document.createDocumentFragment()
            n.textContent.split(/(\s+)/).forEach((part) => {
                if (!part) return
                if (/^\s+$/.test(part)) {
                    f.append(part)
                    return
                }
                const s = document.createElement('span')
                s.className = 'wd'
                s.textContent = part
                f.append(s)
                out.push(s)
            })
            n.replaceWith(f)
        })
        return out
    }

    HV.context = (root) => {
        const offs = []
        let alive = true
        const ctx = {
            root,
            reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            get alive() {
                return alive
            },
            on(el, type, fn, opts) {
                el.addEventListener(type, fn, opts)
                offs.push(() => el.removeEventListener(type, fn, opts))
            },
            raf(fn) {
                let id
                let last = performance.now()
                const loop = (t) => {
                    if (!alive) return
                    fn(t, Math.min(50, t - last))
                    last = t
                    id = requestAnimationFrame(loop)
                }
                id = requestAnimationFrame(loop)
                offs.push(() => cancelAnimationFrame(id))
            },
            later(fn, ms) {
                const id = setTimeout(() => alive && fn(), ms)
                offs.push(() => clearTimeout(id))
            },
            every(fn, ms) {
                const id = setInterval(fn, ms)
                offs.push(() => clearInterval(id))
            },
            wait(ms) {
                return new Promise((r) => ctx.later(r, ms))
            },
            resize(el, fn) {
                const ro = new ResizeObserver(() => alive && fn())
                ro.observe(el)
                offs.push(() => ro.disconnect())
            },
            async type(el, text, speed = 30, ok = () => true) {
                if (ctx.reduced) {
                    el.textContent = text
                    return
                }
                for (let k = 1; k <= text.length; k++) {
                    if (!alive || !ok()) return
                    el.textContent = text.slice(0, k)
                    await ctx.wait(speed)
                }
            },
            stream(spans, per = 14, ok = () => true) {
                return new Promise((res) => {
                    if (ctx.reduced) {
                        spans.forEach((s) => s.classList.add('on'))
                        res()
                        return
                    }
                    let k = 0
                    const tick = () => {
                        if (!alive || !ok()) return res()
                        const n = Math.max(1, Math.round(16 / per))
                        for (let j = 0; j < n && k < spans.length; j++) spans[k++].classList.add('on')
                        if (k < spans.length) ctx.later(tick, Math.max(per, 16))
                        else res()
                    }
                    tick()
                })
            },
            fit(cv, cb) {
                const o = { g: cv.getContext('2d'), w: 0, h: 0 }
                const apply = () => {
                    const b = cv.getBoundingClientRect()
                    const d = Math.min(2, window.devicePixelRatio || 1)
                    o.w = Math.max(1, Math.floor(b.width))
                    o.h = Math.max(1, Math.floor(b.height))
                    cv.width = Math.round(o.w * d)
                    cv.height = Math.round(o.h * d)
                    o.g.setTransform(d, 0, 0, d, 0, 0)
                    if (cb) cb(o)
                }
                ctx.resize(cv, apply)
                apply()
                return o
            },
            nav({ count = 3, change }) {
                let i = -1
                const api = {
                    get i() {
                        return i
                    },
                    go(n) {
                        n = HV.mod(n, count)
                        if (n === i) return
                        const p = i
                        i = n
                        change(n, p)
                    },
                    step(d) {
                        api.go(i + d)
                    },
                }
                ctx.on(root, 'click', (e) => {
                    const s = e.target.closest('[data-step]')
                    if (s && root.contains(s)) {
                        api.step(+s.dataset.step)
                        return
                    }
                    const t = e.target.closest('[data-go]')
                    if (t && root.contains(t)) api.go(+t.dataset.go)
                })
                ctx.on(window, 'keydown', (e) => {
                    if (e.metaKey || e.ctrlKey || e.altKey) return
                    const tg = e.target
                    if (tg.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(tg.tagName)) return
                    if (e.key >= '1' && e.key <= String(count)) {
                        e.preventDefault()
                        api.go(+e.key - 1)
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault()
                        api.step(1)
                    } else if (e.key === 'ArrowLeft') {
                        e.preventDefault()
                        api.step(-1)
                    }
                })
                return api
            },
            dispose() {
                alive = false
                offs.forEach((f) => f())
            },
        }
        return ctx
    }

    HV.boot = () => {
        const stage = document.getElementById('stage')
        const style = document.getElementById('vstyle')
        const chips = document.getElementById('chips')
        const h1 = document.getElementById('h1')
        const groups = ['Im Fenster', 'Neu gedacht', 'Verrückt']
        let ctx = null
        let current = 0

        chips.innerHTML = groups
            .map(
                (g, gi) =>
                    `<span><em>${g}</em>${HV.list
                        .map((v, k) => (v.group === gi ? `<button type="button" data-v="${k + 1}" title="${v.name}">${k + 1}</button>` : ''))
                        .join('')}</span>`,
            )
            .join('')

        const show = (n) => {
            n = Math.min(HV.list.length, Math.max(1, n || 1))
            if (ctx) ctx.dispose()
            const v = HV.list[n - 1]
            current = n
            stage.innerHTML = ''
            stage.removeAttribute('style')
            stage.className = 'v' + String(n).padStart(2, '0')
            style.textContent = v.css || ''
            h1.hidden = v.h1 === false
            ctx = HV.context(stage)
            v.mount(stage, ctx)
            chips.querySelectorAll('button').forEach((b) => b.setAttribute('aria-current', String(+b.dataset.v === n)))
            document.getElementById('vname').textContent = `${n} · ${v.name}`
            document.getElementById('vdesc').textContent = v.desc
            if (location.hash !== '#' + n) history.replaceState(null, '', '#' + n)
        }

        chips.addEventListener('click', (e) => {
            const b = e.target.closest('button[data-v]')
            if (b) show(+b.dataset.v)
        })
        document.addEventListener('click', (e) => {
            if (e.target.closest('a[href="#"]')) e.preventDefault()
        })
        window.addEventListener('keydown', (e) => {
            if (e.metaKey || e.ctrlKey || e.altKey) return
            if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return
            if (e.key === 'n' || e.key === 'N') show(current === HV.list.length ? 1 : current + 1)
            if (e.key === 'p' || e.key === 'P') show(current === 1 ? HV.list.length : current - 1)
        })
        window.addEventListener('hashchange', () => show(parseInt(location.hash.slice(1), 10)))
        show(parseInt(location.hash.slice(1), 10))
    }
})()
