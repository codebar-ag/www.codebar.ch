(() => {
    const { D, S } = HV

    HV.add({
        name: 'Entschlüsselt',
        group: 2,
        desc: 'Dunkle Fläche mit einem Zeichenfeld, das auf den Mauszeiger reagiert. Der Text setzt sich beim Wechsel aus Zufallszeichen zusammen.',
        css: `
.v21 .x{position:relative;border-radius:var(--panel);overflow:hidden;background:#0f0a14;color:#e9e2f2;font-family:var(--mono);font-size:.8125rem;line-height:1.75}
.v21 canvas{position:absolute;inset:0;width:100%;height:100%}
.v21 .in{position:relative;padding:1.5rem 1.75rem 1.75rem;max-width:50rem;background:linear-gradient(90deg,#0f0a14 72%,transparent)}
.v21 .nv{display:flex;flex-wrap:wrap;gap:.5rem 1.25rem;margin-bottom:1.5rem}
.v21 .t{display:flex;gap:.5rem;align-items:center;color:#8f7fa3;transition:.3s}
.v21 .t:hover{color:#fff}
.v21 .t[aria-selected=true]{color:#d9a8ff;text-shadow:0 0 14px rgba(217,168,255,.8)}
.v21 .t .hv-kbd{background:transparent;border-color:currentColor;color:inherit}
.v21 .hv-pan{transition:none}
.v21 .hv-list li::before{content:'>';color:#d9a8ff;left:0}
.v21 .hv-list b{color:#d9a8ff;font-weight:700}
.v21 .hv-btn{background:transparent;border-color:#6b4a85;color:#e9e2f2;font-family:var(--mono);font-size:.8125rem}
.v21 .hv-btn:hover,.v21 .hv-btn--solid{background:#d9a8ff;border-color:#d9a8ff;color:#0f0a14}
@media (max-width:640px){.v21 .in{padding:1.25rem;background:rgba(15,10,20,.88)}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="x"><canvas aria-hidden="true"></canvas><div class="in"><div class="nv" role="tablist">${HV.tabs('t')}</div>${HV.stack()}</div></div>`
            const cv = root.querySelector('canvas')
            const box = root.querySelector('.x')
            const GL = '01{}<>=;:+*#$%&@codebar'
            const SC = '!<>_[]{}=+*^?#01'
            const CW = 14
            const CH = 20
            let grid = []
            let cols = 0
            let rows = 0
            let mouse = { x: -999, y: -999 }
            const f = ctx.fit(cv, (o) => {
                cols = Math.ceil(o.w / CW)
                rows = Math.ceil(o.h / CH)
                grid = Array.from({ length: cols * rows }, () => GL[Math.floor(Math.random() * GL.length)])
            })
            ctx.on(box, 'pointermove', (e) => {
                const b = box.getBoundingClientRect()
                mouse = { x: e.clientX - b.left, y: e.clientY - b.top }
            })
            let frame = 0
            ctx.raf((t) => {
                if (frame++ % 2 && !ctx.reduced) return
                const { g, w, h } = f
                g.clearRect(0, 0, w, h)
                g.font = '13px ui-monospace,Menlo,monospace'
                g.textBaseline = 'top'
                for (let k = 0; k < 14; k++) grid[Math.floor(Math.random() * grid.length)] = GL[Math.floor(Math.random() * GL.length)]
                const mx = mouse.x < 0 ? w * 0.82 : mouse.x
                const my = mouse.y < 0 ? h * 0.5 : mouse.y
                for (let r = 0; r < rows; r++) {
                    for (let c = 0; c < cols; c++) {
                        const x = c * CW
                        const y = r * CH
                        const d = Math.hypot(x - mx, y - my)
                        const wave = Math.pow(Math.max(0, Math.sin(d * 0.035 - t * 0.0035)), 5)
                        const a = 0.07 + wave * 0.75 * Math.max(0, 1 - d / 520)
                        g.fillStyle = `rgba(201,140,255,${a})`
                        g.fillText(grid[r * cols + c], x, y)
                    }
                }
            })
            let gen = 0
            const scramble = (el, ms, ok) => {
                const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT)
                const nodes = []
                while (walker.nextNode()) nodes.push({ n: walker.currentNode, text: walker.currentNode.textContent })
                const total = nodes.reduce((s, o) => s + o.text.length, 0)
                const t0 = performance.now()
                const restore = () => nodes.forEach((o) => (o.n.textContent = o.text))
                const tick = () => {
                    const p = Math.min(1, (performance.now() - t0) / ms)
                    if (!ctx.alive || !ok() || p >= 1) {
                        restore()
                        return
                    }
                    const done = p * total
                    let acc = 0
                    nodes.forEach((o) => {
                        const L = o.text.length
                        const d = Math.max(0, Math.min(L, Math.floor(done - acc)))
                        acc += L
                        let s = o.text.slice(0, d)
                        for (let k = d; k < L; k++) {
                            const ch = o.text[k]
                            if (ch === ' ') s += ' '
                            else s += k - d < 14 ? SC[Math.floor(Math.random() * SC.length)] : ' '
                        }
                        o.n.textContent = s
                    })
                    requestAnimationFrame(tick)
                }
                tick()
            }
            const nav = ctx.nav({
                change(i) {
                    const g = ++gen
                    HV.sel(root, i)
                    HV.show(root, i, false)
                    if (!ctx.reduced) scramble(root.querySelector(`.hv-pan[data-p="${i}"]`), 1100, () => g === gen)
                },
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Strichcode',
        group: 2,
        desc: 'codebar wörtlich: jeder Abschnitt hat seinen Strichcode. Beim Wechsel formen sich die Balken um, ein Scanner fährt drüber und gibt den Text frei.',
        css: `
.v22 .code{position:relative;padding:0 .25rem}
.v22 .bars{display:flex;height:6.5rem;align-items:flex-start}
.v22 .bars i{flex:var(--w) 1 0;height:86%;transition:flex-grow .8s cubic-bezier(.6,0,.2,1);transition-delay:calc(var(--k)*7ms)}
.v22 .bars i:nth-child(odd){background:var(--g900)}
.v22 .bars i.gd{height:100%}
.v22 .dg{display:flex;justify-content:space-between;padding:0 6%;margin-top:-.75rem;font-family:var(--mono);font-size:.875rem;color:var(--g900)}
.v22 .dg span{background:#fff;padding:0 .2rem;min-width:1ch;text-align:center}
.v22 .laser{position:absolute;top:-.5rem;bottom:-.25rem;left:0;width:3px;background:#e11d74;box-shadow:0 0 12px 3px rgba(225,29,116,.7),0 0 40px 10px rgba(225,29,116,.3);opacity:0;pointer-events:none}
.v22 .laser.go{animation:v22l .95s cubic-bezier(.5,0,.3,1) .35s both}
@keyframes v22l{0%{left:0;opacity:0}8%{opacity:1}92%{opacity:1}100%{left:100%;opacity:0}}
.v22 .nv{display:flex;flex-wrap:wrap;gap:.5rem;margin:1.25rem 0 1.5rem}
.v22 .t{display:flex;gap:.5rem;align-items:center;padding:.4rem .8rem;border:1px solid var(--g300);border-radius:var(--pill);font-family:var(--mono);font-size:.8125rem;color:var(--g600);transition:.3s}
.v22 .t:hover{border-color:var(--g900);color:var(--g900)}
.v22 .t[aria-selected=true]{background:var(--g900);border-color:var(--g900);color:#fff}
.v22 .hv-pan.on{animation:v22w .95s cubic-bezier(.5,0,.3,1) .35s both}
@keyframes v22w{from{clip-path:inset(0 100% 0 0)}to{clip-path:inset(0 0 0 0)}}`,
        mount(root, ctx) {
            const N = 95
            root.innerHTML = `<div class="code" aria-hidden="true"><div class="bars">${Array.from({ length: N }, (_, k) => `<i style="--k:${k};--w:1"${k < 3 || k > N - 4 || (k > 45 && k < 50) ? ' class="gd"' : ''}></i>`).join('')}</div><div class="dg"></div><span class="laser"></span></div><div class="nv" role="tablist">${HV.tabs('t')}</div>${HV.stack()}`
            const bars = [...root.querySelectorAll('.bars i')]
            const dg = root.querySelector('.dg')
            const laser = root.querySelector('.laser')
            const encode = (txt) => {
                let h = 2166136261
                return bars.map((_, k) => {
                    h ^= txt.charCodeAt(k % txt.length) + k * 31
                    h = Math.imul(h, 16777619) >>> 0
                    return 1 + (h % 4)
                })
            }
            const nav = ctx.nav({
                change(i) {
                    encode(S[i].cmd).forEach((w, k) => bars[k].style.setProperty('--w', bars[k].classList.contains('gd') ? 1 : w))
                    dg.innerHTML = [...S[i].cmd].map((c) => `<span>${c}</span>`).join('')
                    laser.classList.remove('go')
                    void laser.offsetWidth
                    laser.classList.add('go')
                    HV.sel(root, i)
                    HV.show(root, i, false)
                },
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Flüssig',
        group: 2,
        desc: 'Farbige Tropfen fliessen ineinander und folgen dem Mauszeiger, darüber liegt eine Glasfläche mit dem Text. Jeder Abschnitt hat seine Farbwelt.',
        css: `
.v23 .lq{position:relative;border-radius:1.5rem;overflow:hidden;background:#faf5ff;padding:1.75rem;isolation:isolate}
.v23 .blobs{position:absolute;inset:0;filter:url(#v23g);z-index:-1}
.v23 .blobs i{position:absolute;left:0;top:0;width:var(--s);height:var(--s);margin:calc(var(--s)/-2);border-radius:50%;background:var(--brand);transition:background 1.2s;will-change:transform}
.v23 .lq[data-s="0"] i:nth-child(3n+1){background:#c026d3}
.v23 .lq[data-s="0"] i:nth-child(3n+2){background:#f472b6}
.v23 .lq[data-s="1"] i:nth-child(3n+1){background:#6366f1}
.v23 .lq[data-s="1"] i:nth-child(3n+2){background:#22d3ee}
.v23 .lq[data-s="2"] i:nth-child(3n+1){background:#f59e0b}
.v23 .lq[data-s="2"] i:nth-child(3n+2){background:#f43f5e}
.v23 .glass{background:rgba(255,255,255,.68);-webkit-backdrop-filter:blur(22px) saturate(1.5);backdrop-filter:blur(22px) saturate(1.5);border:1px solid rgba(255,255,255,.8);border-radius:1rem;padding:1.5rem 1.75rem 1.75rem;box-shadow:0 20px 50px -25px rgba(58,3,84,.5);max-width:52rem}
.v23 .nv{position:relative;display:inline-flex;flex-wrap:wrap;padding:.25rem;border-radius:2rem;background:rgba(255,255,255,.7);margin-bottom:1.25rem}
.v23 .ind{position:absolute;left:0;top:0;border-radius:2rem;background:var(--brand);transition:transform .55s cubic-bezier(.3,1.4,.5,1),width .55s cubic-bezier(.3,1.4,.5,1)}
.v23 .t{position:relative;display:flex;gap:.5rem;align-items:center;padding:.4rem .9rem;font-size:.875rem;color:var(--g700);transition:color .3s}
.v23 .t[aria-selected=true]{color:#fff}
.v23 .hv-text{color:var(--g900)}
@media (max-width:640px){.v23 .lq{padding:.75rem}.v23 .glass{padding:1.25rem}}`,
        mount(root, ctx) {
            const B = [
                [300, 0.2, 0.3],
                [240, 0.75, 0.25],
                [280, 0.85, 0.75],
                [220, 0.35, 0.8],
                [200, 0.55, 0.5],
                [260, 0.1, 0.7],
                [180, 0.6, 0.1],
            ]
            root.innerHTML = `<svg width="0" height="0" style="position:absolute" aria-hidden="true"><filter id="v23g" x="-40%" y="-40%" width="180%" height="180%"><feGaussianBlur in="SourceGraphic" stdDeviation="22"/><feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 26 -11"/></filter></svg><div class="lq" data-s="0"><div class="blobs" aria-hidden="true">${B.map((b) => `<i style="--s:${b[0]}px"></i>`).join('')}<i style="--s:170px"></i></div><div class="glass"><div class="nv" role="tablist"><span class="ind"></span>${HV.tabs('t')}</div>${HV.stack()}</div></div>`
            const lq = root.querySelector('.lq')
            const blobs = [...root.querySelectorAll('.blobs i')]
            const ind = root.querySelector('.ind')
            let mouse = null
            let fx = 0
            let fy = 0
            ctx.on(lq, 'pointermove', (e) => {
                const b = lq.getBoundingClientRect()
                mouse = { x: e.clientX - b.left, y: e.clientY - b.top }
            })
            ctx.raf((t) => {
                const w = lq.clientWidth
                const h = lq.clientHeight
                const s = ctx.reduced ? 0 : t * 0.00035
                B.forEach((b, k) => {
                    const x = (b[1] + Math.sin(s * (1 + k * 0.23) + k * 1.7) * 0.18) * w
                    const y = (b[2] + Math.cos(s * (1.3 + k * 0.17) + k * 2.3) * 0.28) * h
                    blobs[k].style.transform = `translate(${x}px,${y}px)`
                })
                const tx = mouse ? mouse.x : w * 0.8
                const ty = mouse ? mouse.y : h * 0.5
                fx += (tx - fx) * 0.08
                fy += (ty - fy) * 0.08
                blobs[B.length].style.transform = `translate(${fx}px,${fy}px)`
            })
            const place = () => {
                const b = root.querySelector('.t[aria-selected="true"]')
                if (!b) return
                ind.style.width = b.offsetWidth + 'px'
                ind.style.height = b.offsetHeight + 'px'
                ind.style.transform = `translate(${b.offsetLeft}px,${b.offsetTop}px)`
            }
            const nav = ctx.nav({
                change(i) {
                    lq.dataset.s = i
                    HV.sel(root, i)
                    HV.show(root, i)
                    place()
                },
            })
            ctx.resize(lq, place)
            nav.go(0)
        },
    })

    HV.add({
        name: 'Zeichenfläche',
        group: 2,
        desc: 'Eine Arbeitsfläche wie im Design-Tool: drei Rahmen auf einem Raster, die Kamera fährt und zoomt zum gewählten, drei Cursor arbeiten mit.',
        css: `
.v24 .vp{position:relative;height:31rem;border:1px solid var(--g300);border-radius:var(--panel);overflow:hidden;background-color:var(--g100);background-image:radial-gradient(circle,var(--g300) 1.2px,transparent 1.3px);cursor:grab;touch-action:pan-y}
.v24 .vp.drag{cursor:grabbing}
.v24 .vp.anim,.v24 .vp.anim .world{transition:transform 1s cubic-bezier(.7,0,.2,1),background-position 1s cubic-bezier(.7,0,.2,1),background-size 1s cubic-bezier(.7,0,.2,1)}
.v24 .world{position:absolute;left:0;top:0;transform-origin:0 0;will-change:transform}
.v24 .fr{position:absolute;width:min(38rem,82vw);cursor:pointer}
.v24 .fl{position:absolute;left:0;bottom:100%;display:flex;gap:.5rem;align-items:center;padding-bottom:.4rem;font-size:.8125rem;color:var(--g500);white-space:nowrap}
.v24 .fb{background:#fff;border:1px solid var(--g300);border-radius:.5rem;padding:1.5rem 1.75rem 1.75rem;transition:box-shadow .4s,outline-color .4s;outline:2px solid transparent;outline-offset:0}
.v24 .fr.on{cursor:auto}
.v24 .fr.on .fb{outline-color:var(--brand);box-shadow:0 30px 60px -35px rgba(80,4,114,.6)}
.v24 .fr.on .fl{color:var(--brand)}
.v24 svg.ln{position:absolute;left:0;top:0;overflow:visible;pointer-events:none}
.v24 svg.ln path{fill:none;stroke:var(--g400);stroke-width:2;stroke-dasharray:6 8;animation:v24d 1.2s linear infinite}
@keyframes v24d{to{stroke-dashoffset:-14}}
.v24 .cur{position:absolute;left:0;top:0;pointer-events:none;will-change:transform;z-index:3}
.v24 .cur svg{display:block;width:18px;height:18px;fill:var(--c);stroke:#fff;stroke-width:1.5}
.v24 .cur span{position:absolute;left:14px;top:16px;padding:.1rem .5rem;border-radius:.3rem .8rem .8rem .8rem;background:var(--c);color:#fff;font-size:.75rem;white-space:nowrap}
.v24 .bar{position:absolute;left:50%;bottom:1rem;transform:translateX(-50%);display:flex;align-items:center;gap:.35rem;padding:.4rem .5rem;border-radius:.75rem;background:#fff;border:1px solid var(--g300);box-shadow:0 12px 30px -14px rgba(17,24,39,.45);z-index:5;cursor:auto}
.v24 .bar button{display:flex;gap:.4rem;align-items:center;padding:.35rem .6rem;border-radius:.45rem;font-size:.8125rem;color:var(--g700);transition:.2s}
.v24 .bar button:hover{background:var(--g100)}
.v24 .bar button[aria-selected=true]{background:var(--brand);color:#fff}
.v24 .bar button[aria-selected=true] .hv-kbd{background:transparent;border-color:#c9a3dd;color:#fff}
.v24 .bar em{font-style:normal;font-size:.75rem;color:var(--g500);min-width:3rem;text-align:right;padding-right:.3rem;font-variant-numeric:tabular-nums}
.v24 .bar hr{width:1px;height:1.4rem;border:0;background:var(--g200);margin:0 .2rem}
@media (max-width:640px){.v24 .bar button span{display:none}}`,
        mount(root, ctx) {
            const P = [
                [0, 0],
                [900, 300],
                [120, 900],
            ]
            const arrow = '<svg viewBox="0 0 18 18"><path d="M2 2l5.5 13 2-5.2 5.2-2z"/></svg>'
            root.innerHTML = `<div class="vp"><div class="world"><svg class="ln" width="1" height="1" aria-hidden="true"><path d="M610 200 C 800 200, 800 420, 890 430"/><path d="M900 700 C 700 760, 520 800, 480 880"/></svg>${S.map((s, i) => `<section class="fr" data-f="${i}" style="left:${P[i][0]}px;top:${P[i][1]}px"><div class="fl">${HV.kbd(i + 1)}<span>${s.cmd}</span></div><div class="fb">${HV.body(i)}</div></section>`).join('')}<span class="cur" style="--c:#0d9488">${arrow}<span>Business</span></span><span class="cur" style="--c:#ea580c">${arrow}<span>Technik</span></span><span class="cur" style="--c:#500472">${arrow}<span>codebar</span></span></div><div class="bar" role="tablist">${HV.tabs('tb')}<hr><button type="button" class="fit" aria-label="100 %">⤢</button><em></em></div></div>`
            const vp = root.querySelector('.vp')
            const world = root.querySelector('.world')
            const frames = [...root.querySelectorAll('.fr')]
            const curs = [...root.querySelectorAll('.cur')]
            const zoom = root.querySelector('.bar em')
            const cam = { x: 0, y: 0, s: 1 }
            const apply = (anim) => {
                vp.classList.toggle('anim', anim && !ctx.reduced)
                world.style.transform = `translate(${cam.x}px,${cam.y}px) scale(${cam.s})`
                vp.style.backgroundPosition = `${cam.x}px ${cam.y}px`
                vp.style.backgroundSize = `${24 * cam.s}px ${24 * cam.s}px`
                zoom.textContent = Math.round(cam.s * 100) + ' %'
            }
            const look = (x, y, w, h, anim = true) => {
                const vw = vp.clientWidth
                const vh = vp.clientHeight - 56
                cam.s = Math.min(1, (vw - 64) / w, (vh - 72) / h)
                cam.x = vw / 2 - (x + w / 2) * cam.s
                cam.y = vh / 2 - (y + h / 2) * cam.s + 14
                apply(anim)
            }
            const focus = (i, anim) => {
                const fr = frames[i]
                look(fr.offsetLeft, fr.offsetTop, fr.offsetWidth, fr.offsetHeight, anim)
            }
            const overview = () => {
                const x1 = Math.max(...frames.map((fr) => fr.offsetLeft + fr.offsetWidth))
                const y1 = Math.max(...frames.map((fr) => fr.offsetTop + fr.offsetHeight))
                look(0, -30, x1, y1 + 30)
            }
            const nav = ctx.nav({
                change(i, prev) {
                    HV.sel(root, i)
                    frames.forEach((fr, k) => {
                        fr.classList.toggle('on', k === i)
                        fr.querySelector('.fb').inert = k !== i
                    })
                    focus(i, prev >= 0)
                },
            })
            let drag = null
            ctx.on(vp, 'pointerdown', (e) => {
                if (e.target.closest('.bar,.fr.on')) return
                drag = { x: e.clientX, y: e.clientY, cx: cam.x, cy: cam.y, moved: false, target: e.target }
            })
            ctx.on(window, 'pointermove', (e) => {
                if (!drag) return
                const dx = e.clientX - drag.x
                const dy = e.clientY - drag.y
                if (!drag.moved && Math.hypot(dx, dy) < 5) return
                drag.moved = true
                vp.classList.add('drag')
                cam.x = drag.cx + dx
                cam.y = drag.cy + dy
                apply(false)
            })
            ctx.on(window, 'pointerup', () => {
                if (!drag) return
                const d = drag
                drag = null
                vp.classList.remove('drag')
                if (d.moved) return
                const fr = d.target.closest('.fr')
                if (!fr) return
                const i = +fr.dataset.f
                if (i === nav.i) focus(i, true)
                else nav.go(i)
            })
            ctx.on(root.querySelector('.fit'), 'click', overview)
            ctx.on(root.querySelector('.bar'), 'click', (e) => {
                const b = e.target.closest('[data-go]')
                if (b && +b.dataset.go === nav.i) focus(nav.i, true)
            })
            const cp = curs.map(() => ({ x: 0, y: 0 }))
            ctx.raf((t) => {
                const fr = frames[Math.max(0, nav.i)]
                const cx = fr.offsetLeft + fr.offsetWidth / 2
                const cy = fr.offsetTop + fr.offsetHeight / 2
                curs.forEach((c, k) => {
                    const s = ctx.reduced ? k * 2 : t * 0.0006 * (1 + k * 0.3) + k * 2.1
                    const tx = cx + Math.sin(s) * fr.offsetWidth * 0.52 + Math.sin(s * 2.3) * 30
                    const ty = cy + Math.cos(s * 1.4) * fr.offsetHeight * 0.5 + Math.cos(s * 3.1) * 20
                    cp[k].x += (tx - cp[k].x) * 0.05
                    cp[k].y += (ty - cp[k].y) * 0.05
                    c.style.transform = `translate(${cp[k].x}px,${cp[k].y}px) scale(${1 / cam.s})`
                })
            })
            ctx.resize(vp, () => focus(Math.max(0, nav.i), false))
            nav.go(0)
        },
    })

    HV.add({
        name: 'Anzeigetafel',
        group: 2,
        desc: 'Eine Klapptafel wie am Bahnhof: der Titel blättert Buchstabe für Buchstabe um, darunter leuchtet die gewählte Zeile.',
        css: `
.v25 .bd{border-radius:var(--panel);background:#15131a;color:#f5f0fa;padding:1.25rem 1.5rem 1.1rem;box-shadow:0 30px 60px -35px rgba(17,24,39,.8),inset 0 1px 0 rgba(255,255,255,.08)}
.v25 .hd{display:flex;justify-content:space-between;font-family:var(--mono);font-size:.75rem;color:#9a8fa8;margin-bottom:.9rem}
.v25 .big{display:grid;grid-template-columns:repeat(16,minmax(0,1fr));gap:.3rem;perspective:600px}
.v25 .cell{position:relative;aspect-ratio:.72;border-radius:.3rem;background:linear-gradient(#2d2836 0 49%,#0c0b10 49% 51%,#252130 51%);display:grid;place-items:center;font-family:var(--mono);font-weight:700;font-size:clamp(.9rem,3.1vw,2.4rem);line-height:1;color:#fff;overflow:hidden}
.v25 .cell b{display:block}
.v25 .cell.f b{animation:v25f .09s ease-in}
@keyframes v25f{from{transform:rotateX(-90deg);opacity:.2}}
.v25 .opts{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:.5rem;margin-top:1rem}
.v25 .op{display:grid;grid-template-columns:auto 1fr;gap:0 .7rem;align-items:center;padding:.6rem .8rem;border-radius:.4rem;background:#1e1b25;color:#b9aec7;font-family:var(--mono);font-size:.8125rem;transition:.3s}
.v25 .op::before{content:'';grid-row:1/3;width:.6rem;height:.6rem;border-radius:50%;background:#3d3748;transition:.3s}
.v25 .op small{grid-column:2;font-family:'Alan Sans',sans-serif;font-size:.75rem;color:#7d728c;line-height:1.3}
.v25 .op:hover{background:#28242f;color:#fff}
.v25 .op[aria-selected=true]{color:#ffd166;background:#28242f}
.v25 .op[aria-selected=true]::before{background:#ffd166;box-shadow:0 0 12px 2px rgba(255,209,102,.7)}
.v25 .op .hv-kbd{display:none}
.v25 .hv-stack{padding:1.75rem .25rem 0}
@media (max-width:760px){.v25 .opts{grid-template-columns:1fr}.v25 .big{gap:.15rem}.v25 .bd{padding:1rem}}`,
        mount(root, ctx) {
            const ABC = ' ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            root.innerHTML = `<div class="bd"><div class="hd"><span>${D.legal}</span><span class="clk"></span></div><div class="big" aria-hidden="true">${'<span class="cell" data-c=" "><b>&nbsp;</b></span>'.repeat(16)}</div><div class="opts" role="tablist">${S.map((s, i) => `<button type="button" class="op" role="tab" data-go="${i}">${HV.kbd(i + 1)}<span>${s.cmd}</span><small>${s.teaser}</small></button>`).join('')}</div></div>${HV.stack()}`
            const cells = [...root.querySelectorAll('.cell')]
            const clk = root.querySelector('.clk')
            const tick = () => (clk.textContent = new Date().toLocaleTimeString('de-CH'))
            tick()
            ctx.every(tick, 1000)
            let gen = 0
            const flap = (txt) => {
                const g = ++gen
                cells.forEach((c, j) => {
                    const target = ABC.includes(txt[j]) ? txt[j] : ' '
                    const put = (ch) => {
                        c.dataset.c = ch
                        c.firstChild.textContent = ch === ' ' ? ' ' : ch
                    }
                    if (ctx.reduced) {
                        put(target)
                        return
                    }
                    const step = () => {
                        if (g !== gen || c.dataset.c === target) return
                        put(ABC[(ABC.indexOf(c.dataset.c) + 1) % ABC.length])
                        c.classList.remove('f')
                        void c.offsetWidth
                        c.classList.add('f')
                        ctx.later(step, 42)
                    }
                    ctx.later(step, j * 40)
                })
            }
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    HV.show(root, i)
                    flap(S[i].title.toUpperCase().padEnd(16, ' '))
                },
            })
            nav.go(0)
        },
    })
    HV.add({
        name: 'Terminal v2',
        group: 0,
        desc: 'Variante 2 mit Tabs oben. Fenster, Befehlszeile, Buttons und Eingabe stehen fest; es bewegt sich nur der Text, der einläuft.',
        css: `
.v26 .t{border-radius:var(--panel);background:#17111f;color:#e9e2f2;font-family:var(--mono);font-size:.8125rem;line-height:1.7;overflow:hidden;box-shadow:0 30px 60px -30px rgba(80,4,114,.6)}
.v26 .bar{display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;background:#221a2d;color:#a99bb8;font-size:.75rem}
.v26 .tabs{display:flex;flex-wrap:wrap;background:#221a2d;border-top:1px solid #3d3050;border-bottom:1px solid #3d3050}
.v26 .tab{display:flex;align-items:center;gap:.6rem;padding:.7rem 1rem;border-right:1px solid #3d3050;color:#a99bb8;box-shadow:inset 0 2px 0 transparent;transition:color .2s,background .2s,box-shadow .2s}
.v26 .tab:hover{color:#fff}
.v26 .tab[aria-selected=true]{background:#17111f;color:#fff;box-shadow:inset 0 2px 0 #c08cf0}
.v26 .tab .hv-kbd{background:#2f2540;border-color:#57456f;color:#e9e2f2}
.v26 .arrows{margin-left:auto;display:flex;align-items:center;gap:.4rem;padding:0 1rem}
.v26 .arrows button{background:#2f2540;border:1px solid #57456f;color:#e9e2f2}
.v26 .arrows button:hover{border-color:#c08cf0}
.v26 .scr{display:grid;gap:.9rem;padding:1.25rem 1.5rem 1.4rem;cursor:text}
.v26 .pr{color:#c08cf0;white-space:nowrap}
.v26 .ln{display:flex;gap:.6rem;height:1.7em;overflow:hidden;white-space:nowrap}
.v26 .c::after{content:'';display:inline-block;width:.55em;height:1.05em;margin-left:2px;background:#c08cf0;vertical-align:text-bottom;animation:v26b 1s steps(1) infinite}
@keyframes v26b{50%{opacity:0}}
.v26 .hv-pan{display:flex;flex-direction:column;transition:none}
.v26 .hv-pan.on{transition:none}
.v26 .wd{opacity:0}
.v26 .wd.on{opacity:1}
.v26 .hv-list li::before{content:'▸';color:#c08cf0;left:0}
.v26 .hv-list b{color:#f0b3ff;font-weight:700}
.v26 .hv-actions{margin-top:auto;padding-top:1.5rem;opacity:0;transition:opacity .3s}
.v26 .hv-actions.on{opacity:1}
.v26 .hv-btn{background:transparent;border-color:#6b4a85;color:#e9e2f2;font-family:var(--mono);font-size:.8125rem}
.v26 .hv-btn:hover,.v26 .hv-btn--solid{background:#c08cf0;border-color:#c08cf0;color:#17111f}
.v26 .err{color:#fca5a5}
.v26 .in{display:flex;gap:.6rem;align-items:center;padding-top:.9rem;border-top:1px solid #2f2540}
.v26 .in input{flex:1;min-width:0;background:none;border:0;outline:0;color:inherit;font:inherit;caret-color:#c08cf0}
.v26 .in input::placeholder{color:#6f6280}
@media (max-width:640px){.v26 .scr{padding:1rem}.v26 .arrows{display:none}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="t"><div class="bar">${HV.dots}<span>${D.legal} — zsh</span></div><div class="tabs" role="tablist">${HV.tabs('tab')}<span class="arrows"><button type="button" class="hv-kbd" data-step="-1" aria-label="${D.prev}">←</button><button type="button" class="hv-kbd" data-step="1" aria-label="${D.nextSection}">→</button></span></div><div class="scr"><div class="ln"><span class="pr">codebar ~ %</span><span class="c"></span></div><div class="hv-stack" aria-live="polite">${S.map((s, i) => `<div class="hv-pan" data-p="${i}">${HV.body(i)}</div>`).join('')}<div class="hv-pan err" data-p="9"></div></div><form class="in"><span class="pr">codebar ~ %</span><input aria-label="${D.legend}" placeholder="${S.map((s) => s.cmd).join(' | ')}" autocomplete="off" autocapitalize="off" spellcheck="false"></form></div></div>`
            const scr = root.querySelector('.scr')
            const cmd = root.querySelector('.c')
            const input = root.querySelector('input')
            const err = root.querySelector('.err')
            const pans = [...root.querySelectorAll('.hv-pan:not(.err)')]
            const W = pans.map((p) => HV.words(p.querySelector('.hv-text')))
            const acts = pans.map((p) => p.querySelector('.hv-actions'))
            let gen = 0
            const reset = () => {
                W.flat().forEach((w) => w.classList.remove('on'))
                acts.forEach((a) => a.classList.remove('on'))
            }
            const run = async (i) => {
                const g = ++gen
                const ok = () => g === gen
                HV.sel(root, i)
                reset()
                HV.show(root, i, false)
                cmd.textContent = ''
                await ctx.type(cmd, S[i].cmd, 34, ok)
                if (!ok()) return
                await ctx.stream(W[i], 9, ok)
                if (ok()) acts[i].classList.add('on')
            }
            const nav = ctx.nav({ change: run })
            ctx.on(root.querySelector('.tabs'), 'click', (e) => {
                const t = e.target.closest('.tab')
                if (t && +t.dataset.go === nav.i && err.classList.contains('on')) run(nav.i)
            })
            ctx.on(scr, 'click', (e) => {
                if (!e.target.closest('button,a')) input.focus({ preventScroll: true })
            })
            ctx.on(input, 'keydown', (e) => {
                if (e.key !== 'Tab' || !input.value) return
                const hit = S.find((s) => s.cmd.startsWith(input.value.trim().toLowerCase()))
                if (hit) {
                    e.preventDefault()
                    input.value = hit.cmd
                }
            })
            ctx.on(root.querySelector('form'), 'submit', (e) => {
                e.preventDefault()
                const v = input.value.trim().toLowerCase()
                input.value = ''
                if (!v) return
                const k = S.findIndex((s, n) => s.cmd === v || s.cmd.startsWith(v) || String(n + 1) === v)
                if (k >= 0) {
                    if (nav.i === k) run(k)
                    else nav.go(k)
                    return
                }
                gen++
                reset()
                cmd.textContent = v
                err.textContent = `zsh: command not found: ${v}`
                HV.show(root, 9, false)
            })
            nav.go(0)
        },
    })
})()
