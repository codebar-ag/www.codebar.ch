(() => {
    const { D, S } = HV

    HV.add({
        name: 'Skizze → Software',
        group: 1,
        desc: 'Derselbe Header zweimal übereinander: links als Handskizze, rechts als fertige Software. Der Regler lässt sich ziehen.',
        css: `
.v16 .cmp{position:relative;border-radius:var(--panel);overflow:hidden;border:1px solid var(--g300);--x:100%;touch-action:pan-y}
.v16 .lay{min-width:0}
.v16 .bar{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;font-size:.75rem}
.v16 .tabs{display:flex;flex-wrap:wrap}
.v16 .tab{display:flex;align-items:center;gap:.6rem;padding:.75rem 1rem;font-size:.875rem}
.v16 .pn{padding:2rem;min-height:20rem}
.v16 .sk{background:linear-gradient(#e7e5e4 1px,transparent 1px) 0 0/100% 1.5rem,linear-gradient(90deg,#e7e5e4 1px,transparent 1px) 0 0/1.5rem 100%,#fffdf7;color:#57534e;filter:url(#v16w)}
.v16 .sk .bar,.v16 .sk .tabs{border-bottom:2px dashed #a8a29e}
.v16 .sk .hv-dots i{background:none;border:2px solid #78716c}
.v16 .sk .tab{border-right:2px dashed #a8a29e}
.v16 .sk .tab[aria-selected=true]{background:repeating-linear-gradient(-45deg,transparent 0 6px,rgba(120,113,108,.18) 6px 8px)}
.v16 .sk .hv-kbd{background:none;border:2px solid #78716c;color:#57534e}
.v16 .sk .hv-list b{color:inherit;text-decoration:underline wavy #a8a29e}
.v16 .sk .hv-btn{background:none;border:2px dashed #78716c;color:#57534e}
.v16 .sw{position:absolute;inset:0;background:#fff;clip-path:inset(0 0 0 var(--x))}
.v16 .sw .bar{background:var(--brand);color:#fff}
.v16 .sw .tabs{background:var(--g100);border-bottom:1px solid var(--g200)}
.v16 .sw .tab{color:var(--g600);box-shadow:inset 0 2px 0 transparent;transition:.25s}
.v16 .sw .tab[aria-selected=true]{background:#fff;color:var(--g900);box-shadow:inset 0 2px 0 var(--brand)}
.v16 .hdl{position:absolute;top:0;bottom:0;left:var(--x);width:2.75rem;margin-left:-1.375rem;cursor:ew-resize;touch-action:none;z-index:5}
.v16 .hdl::before{content:'';position:absolute;left:50%;top:0;bottom:0;width:2px;margin-left:-1px;background:var(--brand);box-shadow:0 0 14px rgba(192,38,211,.8)}
.v16 .hdl i{position:absolute;left:50%;top:50%;width:2.75rem;height:2.75rem;margin:-1.375rem;border-radius:50%;background:var(--brand);color:#fff;display:grid;place-items:center;font-style:normal;box-shadow:0 8px 20px -6px rgba(80,4,114,.7);transition:transform .2s}
.v16 .hdl:hover i{transform:scale(1.1)}
.v16 .tag{position:absolute;bottom:.75rem;z-index:4;font-size:.6875rem;letter-spacing:.08em;text-transform:uppercase;padding:.2rem .6rem;border-radius:2rem;pointer-events:none}
.v16 .tag.a{left:.75rem;background:#57534e;color:#fffdf7}
.v16 .tag.b{right:.75rem;background:var(--brand);color:#fff}
@media (max-width:640px){.v16 .pn{padding:1.25rem}}`,
        mount(root, ctx) {
            const tpl = (k) => `<div class="lay ${k}"><div class="bar">${HV.dots}<span>${D.legal}</span></div><div class="tabs" role="tablist">${HV.tabs('tab')}</div><div class="pn">${HV.stack()}</div></div>`
            root.innerHTML = `<svg width="0" height="0" style="position:absolute" aria-hidden="true"><filter id="v16w" x="-2%" y="-2%" width="104%" height="104%"><feTurbulence type="fractalNoise" baseFrequency=".035" numOctaves="2" seed="7"/><feDisplacementMap in="SourceGraphic" scale="3.5"/></filter></svg><div class="cmp">${tpl('sk')}${tpl('sw')}<span class="tag a">Skizze</span><span class="tag b">Software</span><div class="hdl" role="slider" aria-label="Skizze / Software" aria-valuemin="0" aria-valuemax="100" tabindex="0"><i>⇆</i></div></div>`
            const cmp = root.querySelector('.cmp')
            const hdl = root.querySelector('.hdl')
            let x = 100
            let auto = !ctx.reduced
            let dragging = false
            const set = (v) => {
                x = Math.max(3, Math.min(97, v))
                cmp.style.setProperty('--x', x + '%')
                hdl.setAttribute('aria-valuenow', Math.round(100 - x))
            }
            set(ctx.reduced ? 40 : 97)
            const t0 = performance.now() + 500
            ctx.raf((t) => {
                if (!auto) return
                const p = Math.max(0, Math.min(1, (t - t0) / 1600))
                const e = 1 - Math.pow(1 - p, 4)
                set(97 - 57 * e)
                if (p === 1) auto = false
            })
            ctx.on(hdl, 'pointerdown', (e) => {
                auto = false
                dragging = true
                hdl.setPointerCapture(e.pointerId)
            })
            ctx.on(hdl, 'pointermove', (e) => {
                if (!dragging) return
                const b = cmp.getBoundingClientRect()
                set(((e.clientX - b.left) / b.width) * 100)
            })
            ctx.on(hdl, 'pointerup', () => (dragging = false))
            ctx.on(hdl, 'keydown', (e) => {
                if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return
                e.stopPropagation()
                e.preventDefault()
                auto = false
                set(x + (e.key === 'ArrowLeft' ? -5 : 5))
            })
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    HV.show(root, i)
                },
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Player',
        group: 1,
        desc: 'Die Abschnitte als Titel in einem Player: der Text läuft wie ein Songtext mit, der Balken lässt sich anklicken, am Ende folgt der nächste Titel.',
        css: `
.v17 .pl{display:grid;grid-template-columns:17rem minmax(0,1fr);border:1px solid var(--g200);border-radius:1.25rem;overflow:hidden;background:#fff;box-shadow:0 30px 60px -35px rgba(80,4,114,.5)}
.v17 .left{background:var(--g50);border-right:1px solid var(--g200);padding:1.25rem}
.v17 .cover{position:relative;aspect-ratio:1.5;border-radius:.9rem;background:linear-gradient(135deg,#8b2fb5,var(--brand) 55%,var(--brand-strong));color:#fff;padding:1rem;display:flex;flex-direction:column;justify-content:flex-end;overflow:hidden;margin-bottom:1rem}
.v17 .cover b{font-size:1.05rem;font-weight:600}
.v17 .cover small{opacity:.75;font-size:.75rem}
.v17 .eq{position:absolute;inset:1rem 1rem auto auto;display:flex;align-items:flex-end;gap:3px;height:2.25rem}
.v17 .eq i{width:4px;height:100%;background:#fff;border-radius:2px;transform-origin:50% 100%;animation:v17e 1s ease-in-out infinite alternate}
.v17 .eq i:nth-child(2){animation-duration:.7s}.v17 .eq i:nth-child(3){animation-duration:1.3s}.v17 .eq i:nth-child(4){animation-duration:.85s}.v17 .eq i:nth-child(5){animation-duration:1.1s}
@keyframes v17e{from{transform:scaleY(.15)}to{transform:scaleY(1)}}
.v17 .pl.paused .eq i{animation-play-state:paused}
.v17 .trk{display:grid;grid-template-columns:auto 1fr auto;gap:0 .7rem;width:100%;padding:.55rem .6rem;border-radius:.6rem;align-items:center;font-size:.875rem;color:var(--g800);transition:background .2s}
.v17 .trk:hover{background:var(--g100)}
.v17 .trk small{grid-column:2;color:var(--g500);font-size:.75rem;line-height:1.3}
.v17 .trk em{grid-row:1/3;grid-column:3;font-style:normal;font-size:.75rem;color:var(--g500);font-variant-numeric:tabular-nums}
.v17 .trk .hv-kbd{grid-row:1/3}
.v17 .trk[aria-selected=true]{background:#f3e8ff;color:var(--brand)}
.v17 .right{padding:1.75rem 2rem 1.5rem;display:grid;grid-template-rows:1fr auto;min-width:0}
.v17 .hv-text p,.v17 .hv-list li{font-size:1.0625rem;line-height:1.6}
.v17 .wd{color:var(--g300);transition:color .35s}
.v17 .wd.on{color:var(--g900)}
.v17 .hv-list b .wd.on{color:var(--brand)}
.v17 .hv-list b{font-weight:600}
.v17 .ctl{display:flex;align-items:center;gap:.9rem;margin-top:1.5rem;font-size:.75rem;color:var(--g500);font-variant-numeric:tabular-nums}
.v17 .pp{flex:none;width:2.75rem;height:2.75rem;border-radius:50%;background:var(--brand);color:#fff;display:grid;place-items:center;font-size:.9rem;transition:transform .2s}
.v17 .pp:hover{transform:scale(1.08)}
.v17 .sm{flex:none;width:2rem;height:2rem;border-radius:50%;display:grid;place-items:center;color:var(--g700)}
.v17 .sm:hover{background:var(--g100)}
.v17 .prog{flex:1;height:1.25rem;display:flex;align-items:center;cursor:pointer}
.v17 .prog span{position:relative;display:block;width:100%;height:4px;border-radius:4px;background:var(--g200)}
.v17 .prog i{position:absolute;left:0;top:0;height:100%;border-radius:4px;background:linear-gradient(90deg,var(--brand),#c026d3)}
.v17 .prog i::after{content:'';position:absolute;right:-.4rem;top:50%;width:.8rem;height:.8rem;margin-top:-.4rem;border-radius:50%;background:var(--brand);box-shadow:0 0 0 4px rgba(80,4,114,.15)}
@media (max-width:820px){.v17 .pl{grid-template-columns:minmax(0,1fr)}.v17 .left{border-right:0;border-bottom:1px solid var(--g200)}.v17 .cover{aspect-ratio:3.2}.v17 .right{padding:1.25rem}}`,
        mount(root, ctx) {
            const STEP = 0.21
            const fmt = (s) => `${Math.floor(s / 60)}:${String(Math.floor(s % 60)).padStart(2, '0')}`
            root.innerHTML = `<div class="pl"><div class="left"><div class="cover"><span class="eq" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></span><b></b><small>${D.legal}</small></div><div role="tablist">${S.map((s, i) => `<button type="button" class="trk" role="tab" data-go="${i}">${HV.kbd(i + 1)}<span>${s.title}</span><em></em><small>${s.teaser}</small></button>`).join('')}</div></div><div class="right">${HV.stack()}<div class="ctl"><button type="button" class="sm" data-step="-1" aria-label="${D.prev}">⏮</button><button type="button" class="pp" aria-label="Play / Pause"></button><button type="button" class="sm" data-step="1" aria-label="${D.nextSection}">⏭</button><span class="now">0:00</span><div class="prog"><span><i></i></span></div><span class="tot"></span></div></div></div>`
            const pl = root.querySelector('.pl')
            const W = [...root.querySelectorAll('.hv-pan')].map((p) => HV.words(p.querySelector('.hv-text')))
            const DUR = W.map((w) => w.length * STEP + 0.6)
            root.querySelectorAll('.trk em').forEach((e, i) => (e.textContent = fmt(DUR[i])))
            const pp = root.querySelector('.pp')
            const fill = root.querySelector('.prog i')
            const now = root.querySelector('.now')
            let cur = 0
            let t = 0
            let shown = -1
            let playing = !ctx.reduced
            const ui = () => {
                pp.textContent = playing ? '❚❚' : '▶'
                pl.classList.toggle('paused', !playing)
            }
            const nav = ctx.nav({
                change(i) {
                    cur = i
                    t = ctx.reduced ? DUR[i] : 0
                    shown = -1
                    W.flat().forEach((w) => w.classList.remove('on'))
                    HV.sel(root, i)
                    HV.show(root, i, false)
                    root.querySelector('.cover b').textContent = S[i].title
                    root.querySelector('.tot').textContent = fmt(DUR[i])
                },
            })
            ctx.raf((_, dt) => {
                if (playing) {
                    t += dt / 1000
                    if (t >= DUR[cur]) {
                        if (cur < 2) nav.go(cur + 1)
                        else {
                            t = DUR[cur]
                            playing = false
                            ui()
                        }
                    }
                }
                const n = Math.floor(t / STEP)
                if (n !== shown) {
                    W[cur].forEach((w, k) => w.classList.toggle('on', k < n))
                    shown = n
                }
                fill.style.width = Math.min(100, (t / DUR[cur]) * 100) + '%'
                now.textContent = fmt(t)
            })
            ctx.on(pp, 'click', () => {
                if (!playing && t >= DUR[cur]) t = 0
                playing = !playing
                ui()
            })
            ctx.on(root.querySelector('.prog'), 'click', (e) => {
                const b = e.currentTarget.getBoundingClientRect()
                t = Math.max(0, Math.min(1, (e.clientX - b.left) / b.width)) * DUR[cur]
            })
            ui()
            nav.go(0)
        },
    })

    HV.add({
        name: 'Partikel',
        group: 2,
        desc: 'Rund 1600 Punkte formen «wer», «was», «wie» und fliegen beim Wechsel ins nächste Wort. Der Mauszeiger schiebt sie auseinander.',
        css: `
.v18 .p{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.15fr);gap:2rem;align-items:center}
.v18 .cvw{position:relative;height:24rem;border-radius:var(--panel);background:radial-gradient(ellipse at 50% 60%,#faf5ff,#fff 70%)}
.v18 canvas{position:absolute;inset:0;width:100%;height:100%}
.v18 .nv{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1.5rem}
.v18 .t{display:flex;gap:.5rem;align-items:center;padding:.4rem .8rem;border:1px solid var(--g300);border-radius:2rem;font-size:.875rem;color:var(--g600);transition:.3s}
.v18 .t:hover{border-color:var(--brand);color:var(--brand)}
.v18 .t[aria-selected=true]{background:var(--brand);border-color:var(--brand);color:#fff}
@media (max-width:860px){.v18 .p{grid-template-columns:minmax(0,1fr)}.v18 .cvw{height:13rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="p"><div class="cvw"><canvas aria-hidden="true"></canvas></div><div><div class="nv" role="tablist">${HV.tabs('t')}</div>${HV.stack()}</div></div>`
            const cv = root.querySelector('canvas')
            const N = 1600
            const COL = ['#500472', '#8b2fb5', '#c026d3']
            const ps = Array.from({ length: N }, (_, k) => ({ x: 0, y: 0, vx: 0, vy: 0, tx: 0, ty: 0, c: k % 3, s: 1.2 + Math.random() * 1.4, init: false }))
            let word = 0
            let mouse = null
            let f = null
            const target = () => {
                const { w, h } = f
                const o = document.createElement('canvas')
                o.width = w
                o.height = h
                const g = o.getContext('2d', { willReadFrequently: true })
                const txt = S[word].cmd.split('-')[0]
                let fs = h * 0.72
                g.font = `700 ${fs}px "Alan Sans",system-ui,sans-serif`
                const m = g.measureText(txt).width
                if (m > w * 0.92) fs *= (w * 0.92) / m
                g.font = `700 ${fs}px "Alan Sans",system-ui,sans-serif`
                g.textAlign = 'center'
                g.textBaseline = 'middle'
                g.fillText(txt, w / 2, h / 2)
                const d = g.getImageData(0, 0, w, h).data
                const pts = []
                for (let y = 0; y < h; y += 3) for (let x = 0; x < w; x += 3) if (d[(y * w + x) * 4 + 3] > 128) pts.push([x, y])
                if (!pts.length) return
                for (let k = pts.length - 1; k > 0; k--) {
                    const j = Math.floor(Math.random() * (k + 1))
                    ;[pts[k], pts[j]] = [pts[j], pts[k]]
                }
                ps.forEach((p, k) => {
                    const q = pts[k % pts.length]
                    p.tx = q[0] + Math.random() * 2
                    p.ty = q[1] + Math.random() * 2
                    if (!p.init) {
                        p.x = Math.random() * w
                        p.y = Math.random() * h
                        p.init = true
                    }
                    if (ctx.reduced) {
                        p.x = p.tx
                        p.y = p.ty
                    }
                })
            }
            f = ctx.fit(cv, () => f && target())
            target()
            document.fonts.ready.then(() => ctx.alive && target())
            ctx.on(cv, 'pointermove', (e) => {
                const b = cv.getBoundingClientRect()
                mouse = { x: e.clientX - b.left, y: e.clientY - b.top }
            })
            ctx.on(cv, 'pointerleave', () => (mouse = null))
            ctx.raf(() => {
                const { g, w, h } = f
                g.clearRect(0, 0, w, h)
                for (const p of ps) {
                    p.vx += (p.tx - p.x) * 0.045
                    p.vy += (p.ty - p.y) * 0.045
                    if (mouse) {
                        const dx = p.x - mouse.x
                        const dy = p.y - mouse.y
                        const d2 = dx * dx + dy * dy
                        if (d2 < 6400 && d2 > 0.01) {
                            const d = Math.sqrt(d2)
                            const k = ((80 - d) / 80) * 5
                            p.vx += (dx / d) * k
                            p.vy += (dy / d) * k
                        }
                    }
                    p.vx *= 0.84
                    p.vy *= 0.84
                    p.x += p.vx
                    p.y += p.vy
                }
                for (let c = 0; c < 3; c++) {
                    g.fillStyle = COL[c]
                    for (const p of ps) if (p.c === c) g.fillRect(p.x, p.y, p.s, p.s)
                }
            })
            const nav = ctx.nav({
                change(i) {
                    word = i
                    ps.forEach((p) => {
                        p.vx += (Math.random() - 0.5) * 26
                        p.vy += (Math.random() - 0.5) * 26
                    })
                    target()
                    HV.sel(root, i)
                    HV.show(root, i)
                },
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Prisma',
        group: 2,
        desc: 'Ein dreiseitiges Prisma im Raum, jede Seite ein Abschnitt. Dreht sich beim Wechsel und lässt sich mit der Maus ziehen.',
        css: `
.v19 .nv{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1.25rem}
.v19 .t{display:flex;gap:.5rem;align-items:center;padding:.4rem .8rem;border:1px solid var(--g300);border-radius:2rem;font-size:.875rem;color:var(--g600);transition:.3s}
.v19 .t:hover{border-color:var(--brand);color:var(--brand)}
.v19 .t[aria-selected=true]{background:var(--brand);border-color:var(--brand);color:#fff}
.v19 .scene{perspective:2200px;cursor:grab;touch-action:pan-y;padding-bottom:1.5rem}
.v19 .scene.drag{cursor:grabbing}
.v19 .prism{display:grid;transform-style:preserve-3d;transform:translateZ(calc(var(--z,300px)*-1)) rotateY(0deg)}
.v19 .face{grid-area:1/1;min-width:0;backface-visibility:hidden;transform:rotateY(calc(var(--n)*120deg)) translateZ(var(--z,300px));border:1px solid var(--g300);border-radius:var(--panel);background:#fff;padding:1.75rem 2rem 2rem;box-shadow:0 30px 50px -35px rgba(80,4,114,.6)}
.v19 .face:nth-child(2){background:var(--g50)}
.v19 .face:nth-child(3){background:#faf5ff}
.v19 .face header{display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;font-size:.875rem;color:var(--brand)}
@media (max-width:640px){.v19 .face{padding:1.25rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="nv" role="tablist">${HV.tabs('t')}</div><div class="scene"><div class="prism">${S.map((s, i) => `<section class="face" style="--n:${i}"><header>${HV.kbd(i + 1)}<span>${s.cmd}</span></header>${HV.body(i)}</section>`).join('')}</div></div>`
            const scene = root.querySelector('.scene')
            const prism = root.querySelector('.prism')
            const faces = [...root.querySelectorAll('.face')]
            let ang = 0
            let pend = null
            let drag = null
            const apply = (anim) => {
                prism.style.transition = anim && !ctx.reduced ? 'transform 1s cubic-bezier(.3,1.25,.4,1)' : 'none'
                prism.style.transform = `translateZ(calc(var(--z,300px)*-1)) rotateY(${ang}deg)`
            }
            const size = () => scene.style.setProperty('--z', prism.clientWidth * 0.2887 + 'px')
            const nav = ctx.nav({
                change(i) {
                    let k = pend
                    if (k === null) {
                        const cur = Math.round(-ang / 120)
                        k = [cur - 1, cur, cur + 1, cur + 2].filter((j) => HV.mod(j, 3) === i).sort((a, b) => Math.abs(a - cur) - Math.abs(b - cur))[0]
                    }
                    pend = null
                    ang = -120 * k
                    apply(true)
                    HV.sel(root, i)
                    faces.forEach((fc, n) => (fc.inert = n !== i))
                    HV.play(faces[i])
                },
            })
            ctx.on(scene, 'pointerdown', (e) => {
                if (e.target.closest('button,a')) return
                drag = { x: e.clientX, a: ang }
                scene.classList.add('drag')
                scene.setPointerCapture(e.pointerId)
            })
            ctx.on(scene, 'pointermove', (e) => {
                if (!drag) return
                ang = drag.a + (e.clientX - drag.x) * 0.22
                apply(false)
            })
            const up = () => {
                if (!drag) return
                drag = null
                scene.classList.remove('drag')
                const k = Math.round(-ang / 120)
                const i = HV.mod(k, 3)
                if (i === nav.i) {
                    ang = -120 * k
                    apply(true)
                } else {
                    pend = k
                    nav.go(i)
                }
            }
            ctx.on(scene, 'pointerup', up)
            ctx.on(scene, 'pointercancel', up)
            ctx.resize(prism, size)
            size()
            nav.go(0)
        },
    })

    const TERMS = [
        ['codebar', 'Basel', 'Ideen', 'Skizze', 'Software', 'Business', 'Technik', 'offene Technologien'],
        ['Konzeption', 'Prototypen', 'Softwareentwicklung', 'Laravel', 'DMS/ECM', 'DocuWare', 'Open Source ERP', 'Odoo'],
        ['zuhören', 'verstehen', 'Konzept', 'Prozesse', 'Menschen', 'Umsetzung', 'Betrieb', 'offen'],
    ]

    HV.add({
        name: 'Schwerkraft',
        group: 2,
        desc: 'Die Schlüsselbegriffe jedes Abschnitts fallen in eine Box, stapeln sich und lassen sich werfen. Ein Klick ins Leere wirbelt alles auf.',
        css: `
.v20 .gv{display:grid;grid-template-columns:minmax(0,1.25fr) minmax(0,1fr);gap:2rem;align-items:stretch}
.v20 .nv{display:flex;flex-wrap:wrap;gap:.25rem 1.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--g200)}
.v20 .t{display:flex;gap:.5rem;align-items:center;padding:.5rem 0 .75rem;font-size:.875rem;color:var(--g500);box-shadow:inset 0 -2px 0 transparent;transition:.3s}
.v20 .t:hover{color:var(--g900)}
.v20 .t[aria-selected=true]{color:var(--brand);box-shadow:inset 0 -2px 0 var(--brand)}
.v20 .arena{position:relative;min-height:24rem;border:2px dashed var(--g300);border-top:0;border-radius:0 0 var(--panel) var(--panel);background:linear-gradient(transparent,var(--g50));overflow:hidden;touch-action:none;cursor:crosshair}
.v20 .pill{position:absolute;left:0;top:0;padding:.5rem 1.1rem;border-radius:2rem;font-size:1rem;font-weight:500;white-space:nowrap;user-select:none;cursor:grab;will-change:transform;box-shadow:0 6px 14px -8px rgba(17,24,39,.5)}
.v20 .pill.k0{background:var(--brand);color:#fff}
.v20 .pill.k1{background:#fff;color:var(--brand);border:1.5px solid var(--brand)}
.v20 .pill.k2{background:#f3e8ff;color:var(--brand-strong)}
.v20 .pill.k3{background:var(--g900);color:#fff}
@media (max-width:860px){.v20 .gv{grid-template-columns:minmax(0,1fr)}.v20 .arena{min-height:15rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="gv"><div><div class="nv" role="tablist">${HV.tabs('t')}</div>${HV.stack()}</div><div class="arena" aria-hidden="true"></div></div>`
            const arena = root.querySelector('.arena')
            let bodies = []
            let drag = null
            const drop = (i) => {
                bodies.forEach((b) => (b.gone = true))
                TERMS[i].forEach((term, k) =>
                    ctx.later(() => {
                        const el = document.createElement('span')
                        el.className = 'pill k' + (k % 4)
                        el.textContent = term
                        arena.append(el)
                        const w = el.offsetWidth
                        bodies.push({ el, w, h: el.offsetHeight, x: 10 + Math.random() * Math.max(10, arena.clientWidth - w - 20), y: -60, vx: (Math.random() - 0.5) * 6, vy: 2, r: 0, gone: false })
                    }, 250 + k * 130),
                )
            }
            const walls = (b, W, H) => {
                if (b.x < 0) {
                    b.x = 0
                    b.vx = Math.abs(b.vx) * 0.4
                }
                if (b.x + b.w > W) {
                    b.x = W - b.w
                    b.vx = -Math.abs(b.vx) * 0.4
                }
                if (!b.gone && b.y + b.h > H) {
                    b.y = H - b.h
                    b.vy = -Math.abs(b.vy) * 0.25
                    if (Math.abs(b.vy) < 1) b.vy = 0
                    b.vx *= 0.8
                }
            }
            ctx.raf((_, dt) => {
                const W = arena.clientWidth
                const H = arena.clientHeight
                const k = dt / 16.7
                for (const b of bodies) {
                    if (drag && drag.b === b) continue
                    b.vy += 0.6 * k
                    b.vx *= 0.995
                    b.x += b.vx * k
                    b.y += b.vy * k
                    walls(b, W, H)
                }
                const live = bodies.filter((b) => !b.gone)
                for (let it = 0; it < 6; it++) {
                    for (let a = 0; a < live.length; a++) {
                        for (let c = a + 1; c < live.length; c++) {
                            const A = live[a]
                            const B = live[c]
                            const ox = Math.min(A.x + A.w, B.x + B.w) - Math.max(A.x, B.x)
                            const oy = Math.min(A.y + A.h, B.y + B.h) - Math.max(A.y, B.y)
                            if (ox <= 0 || oy <= 0) continue
                            if (oy < ox) {
                                const up = A.y < B.y ? A : B
                                const dn = up === A ? B : A
                                up.y -= oy * 0.8
                                dn.y += oy * 0.2
                                const rel = up.vy - dn.vy
                                if (rel > 0) {
                                    up.vy -= rel * 0.8
                                    dn.vy += rel * 0.2
                                }
                                up.vx *= 0.94
                            } else {
                                const lf = A.x < B.x ? A : B
                                const rt = lf === A ? B : A
                                lf.x -= ox * 0.5
                                rt.x += ox * 0.5
                                const rel = lf.vx - rt.vx
                                if (rel > 0) {
                                    lf.vx -= rel * 0.6
                                    rt.vx += rel * 0.6
                                }
                            }
                        }
                    }
                    live.forEach((b) => walls(b, W, H))
                }
                bodies = bodies.filter((b) => {
                    if (b.gone && b.y > H + 80) {
                        b.el.remove()
                        return false
                    }
                    b.r += (Math.max(-14, Math.min(14, b.vx * 2)) - b.r) * 0.15
                    b.el.style.transform = `translate(${b.x}px,${b.y}px) rotate(${b.r}deg)`
                    return true
                })
            })
            ctx.on(arena, 'pointerdown', (e) => {
                const rect = arena.getBoundingClientRect()
                const px = e.clientX - rect.left
                const py = e.clientY - rect.top
                const b = bodies.find((x) => x.el === e.target)
                arena.setPointerCapture(e.pointerId)
                if (b) {
                    drag = { b, ox: px - b.x, oy: py - b.y }
                    return
                }
                bodies.forEach((x) => {
                    const dx = x.x + x.w / 2 - px
                    const dy = x.y + x.h / 2 - py
                    const d = Math.hypot(dx, dy) || 1
                    if (d > 320) return
                    const f = (1 - d / 320) * 22
                    x.vx += (dx / d) * f
                    x.vy += (dy / d) * f - 6
                })
            })
            ctx.on(arena, 'pointermove', (e) => {
                if (!drag) return
                const rect = arena.getBoundingClientRect()
                const nx = e.clientX - rect.left - drag.ox
                const ny = e.clientY - rect.top - drag.oy
                drag.b.vx = (nx - drag.b.x) * 0.6
                drag.b.vy = (ny - drag.b.y) * 0.6
                drag.b.x = nx
                drag.b.y = ny
            })
            const up = () => (drag = null)
            ctx.on(arena, 'pointerup', up)
            ctx.on(arena, 'pointercancel', up)
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    HV.show(root, i)
                    drop(i)
                },
            })
            nav.go(0)
        },
    })
})()
