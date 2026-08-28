import '../css/kiosk.css'

function initDeck() {
    const deck = document.getElementById('deck')
    if (!deck) return

    const slides = Array.from(deck.querySelectorAll('.slide'))
    const fill = document.getElementById('fill')
    const ticks = document.getElementById('ticks')
    const beam = document.getElementById('beam')
    let i = 0
    let timer = null
    let paused = false
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches

    const durs = slides.map((s) => parseInt(s.getAttribute('data-dur'), 10) || 14000)
    const total = durs.reduce((a, b) => a + b, 0)
    const marks = []
    let acc = 0
    for (const d of durs) {
        marks.push(acc / total)
        acc += d
    }
    marks.push(1)
    for (let m = 1; m < marks.length - 1; m++) {
        const b = document.createElement('b')
        b.style.left = `${marks[m] * 100}%`
        ticks.appendChild(b)
    }

    let startedAt = 0
    let remaining = 0

    function scaleNow() {
        const t = getComputedStyle(fill).transform
        if (!t || t === 'none') return marks[i]
        const m = t.match(/matrix\(([^,]+)/)
        return m ? parseFloat(m[1]) : marks[i]
    }

    function runBar(from, to, ms) {
        fill.style.transition = 'none'
        fill.style.transform = `scaleX(${from})`
        void fill.offsetWidth
        if (reduce) {
            fill.style.transform = `scaleX(${to})`
            return
        }
        fill.style.transition = `transform ${ms}ms linear`
        fill.style.transform = `scaleX(${to})`
    }

    function freezeBar() {
        const at = scaleNow()
        fill.style.transition = 'none'
        fill.style.transform = `scaleX(${at})`
    }

    function enter() {
        document.body.classList.toggle('on-cover', i === 0)
        slides[i].scrollTop = 0
        void slides[i].offsetWidth
        slides[i].classList.add('on')
        remaining = durs[i]
        startedAt = Date.now()
        runBar(marks[i], marks[i + 1], durs[i])
        clearTimeout(timer)
        if (!paused) timer = setTimeout(() => show(i + 1, 1), durs[i])
    }

    function show(n, dir) {
        const from = slides[i]
        i = (n + slides.length) % slides.length
        if (!reduce) {
            beam.classList.remove('sweep')
            void beam.offsetWidth
            beam.style.animationDirection = dir < 0 ? 'reverse' : 'normal'
            beam.classList.add('sweep')
        }
        from.classList.remove('on')
        if (from !== slides[i]) slides[i].classList.remove('on')
        enter()
    }

    function setPaused(v) {
        if (v === paused) return
        paused = v
        document.body.classList.toggle('is-paused', paused)
        if (paused) {
            clearTimeout(timer)
            remaining = Math.max(0, durs[i] - (Date.now() - startedAt))
            freezeBar()
        } else {
            runBar(scaleNow(), marks[i + 1], remaining)
            startedAt = Date.now() - (durs[i] - remaining)
            timer = setTimeout(() => show(i + 1, 1), remaining)
        }
    }

    document.getElementById('next').addEventListener('click', () => show(i + 1, 1))
    document.getElementById('prev').addEventListener('click', () => show(i - 1, -1))
    document.getElementById('home').addEventListener('click', () => show(0, -1))
    document.getElementById('play').addEventListener('click', () => setPaused(!paused))

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key === 'PageDown') show(i + 1, 1)
        else if (e.key === 'ArrowLeft' || e.key === 'PageUp') show(i - 1, -1)
        else if (e.key === 'Home') show(0, -1)
        else if (e.key === ' ') {
            e.preventDefault()
            setPaused(!paused)
        }
    })

    document.body.classList.add('on-cover')
    enter()

    let lock = null
    function keepAwake() {
        if (!('wakeLock' in navigator)) return
        navigator.wakeLock
            .request('screen')
            .then((l) => {
                lock = l
                l.addEventListener('release', () => {
                    lock = null
                })
            })
            .catch(() => {})
    }
    keepAwake()
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible' && !lock) keepAwake()
    })

    const cv = document.getElementById('field')
    const cx = cv.getContext('2d')
    let strips = []
    let W = 0
    let H = 0
    let dpr = 1
    let grad = null

    function build() {
        dpr = Math.min(window.devicePixelRatio || 1, 2)
        W = cv.clientWidth
        H = cv.clientHeight
        cv.width = Math.round(W * dpr)
        cv.height = Math.round(H * dpr)
        cx.setTransform(dpr, 0, 0, dpr, 0, 0)
        strips = []
        let x = -W * 0.4
        while (x < W * 1.4) {
            const w = 2 + Math.random() * 26
            strips.push({ x, w, a: 0.012 + Math.random() * 0.035, v: 0.06 + Math.random() * 0.22 })
            x += w + 6 + Math.random() * 46
        }
        grad = cx.createLinearGradient(0, 0, W, 0)
        grad.addColorStop(0, 'rgb(192,38,211)')
        grad.addColorStop(0.5, 'rgb(80,4,114)')
        grad.addColorStop(1, 'rgb(37,99,235)')
    }

    function draw() {
        cx.clearRect(0, 0, W, H)
        cx.save()
        cx.translate(W / 2, H / 2)
        cx.transform(1, 0, Math.tan((-3 * Math.PI) / 180), 1, 0, 0)
        cx.translate(-W / 2, -H / 2)
        for (const s of strips) {
            cx.globalAlpha = s.a
            cx.fillStyle = grad
            cx.fillRect(s.x, -H * 0.2, s.w, H * 1.4)
            if (!reduce && !paused) s.x += s.v
            if (s.x > W * 1.4) s.x = -W * 0.4 - s.w
        }
        cx.restore()
        cx.globalAlpha = 1
        requestAnimationFrame(draw)
    }

    build()
    window.addEventListener('resize', build)
    requestAnimationFrame(draw)
}

initDeck()
