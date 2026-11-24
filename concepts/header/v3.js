(() => {
    const { D, S } = HV

    HV.add({
        name: 'Der Weg',
        group: 1,
        desc: 'Von der Skizze zur Software als gezeichnete Strecke: ein Punkt fährt zur gewählten Station, die Strecke füllt sich.',
        css: `
.v11 .map{position:relative;height:9.5rem;margin:0 0 1.5rem}
.v11 svg{position:absolute;inset:0;width:100%;height:100%;overflow:visible}
.v11 path{fill:none;stroke-linecap:round}
.v11 .base{stroke:var(--g300);stroke-width:3;stroke-dasharray:2 9}
.v11 .done{stroke:var(--brand);stroke-width:4}
.v11 .end{position:absolute;font-size:.75rem;color:var(--g500);transform:translate(-50%,.9rem);white-space:nowrap}
.v11 .st{position:absolute;transform:translate(-50%,-50%);display:flex;flex-direction:column;align-items:center}
.v11 .st i{width:1.1rem;height:1.1rem;border-radius:50%;background:#fff;border:3px solid var(--g400);transition:.4s}
.v11 .st span{position:absolute;top:1.5rem;display:flex;gap:.5rem;align-items:center;white-space:nowrap;font-size:.875rem;color:var(--g600);padding:.25rem .6rem;border-radius:var(--pill);transition:.3s}
.v11 .st:hover span{color:var(--brand)}
.v11 .st.past i{border-color:var(--brand);background:var(--brand)}
.v11 .st[aria-selected=true] i{border-color:var(--brand);transform:scale(1.25)}
.v11 .st[aria-selected=true] span{color:var(--brand);background:#f3e8ff}
.v11 .dot{position:absolute;width:1.5rem;height:1.5rem;margin:-.75rem;border-radius:50%;background:var(--brand);box-shadow:0 0 0 6px rgba(80,4,114,.18);pointer-events:none}
.v11 .dot::after{content:'';position:absolute;inset:0;border-radius:50%;border:2px solid var(--brand);animation:v11p 1.6s ease-out infinite}
@keyframes v11p{to{transform:scale(2.6);opacity:0}}
.v11 .hv-stack{border-top:1px solid var(--g200);padding-top:1.5rem}
.v11 .hv-pan[data-p="1"] .hv-list{grid-template-columns:repeat(4,minmax(0,1fr));gap:1.25rem;margin-top:1.25rem}
.v11 .hv-pan[data-p="1"] .hv-list li{padding:1rem 0 0;border-top:2px solid var(--brand);font-size:.875rem;line-height:1.55}
.v11 .hv-pan[data-p="1"] .hv-list li::before{content:'';left:0;top:-.4rem;width:.7rem;height:.7rem;border-radius:50%;background:var(--brand)}
.v11 .hv-pan[data-p="1"] .hv-list b{font-weight:600}
@media (max-width:760px){.v11 .hv-pan[data-p="1"] .hv-list{grid-template-columns:1fr 1fr}.v11 .st span span{display:none}}`,
        mount(root, ctx) {
            const at = [0.1, 0.5, 0.9]
            root.innerHTML = `<div class="map"><svg aria-hidden="true"><path class="base"/><path class="done"/></svg><span class="end a">Skizze</span><span class="end b">Software</span><div role="tablist">${S.map((s, i) => `<button type="button" class="st" role="tab" data-go="${i}"><i></i><span>${HV.kbd(i + 1)}<span>${s.cmd}</span></span></button>`).join('')}</div><div class="dot"></div></div>${HV.stack()}`
            const map = root.querySelector('.map')
            const svg = root.querySelector('svg')
            const path = root.querySelector('.done')
            const stations = [...root.querySelectorAll('.st')]
            let len = 1
            const put = (el, f) => {
                const p = path.getPointAtLength(len * f)
                el.style.left = p.x + 'px'
                el.style.top = p.y + 'px'
            }
            const draw = () => {
                const k = map.clientWidth / 1000
                const d = `M${10 * k} 95 C ${120 * k} 95, ${150 * k} 30, ${290 * k} 34 S ${430 * k} 120, ${560 * k} 98 S ${760 * k} 10, ${990 * k} 52`
                svg.setAttribute('viewBox', `0 0 ${map.clientWidth} 150`)
                svg.querySelectorAll('path').forEach((x) => x.setAttribute('d', d))
                len = path.getTotalLength()
                path.style.strokeDasharray = len
                stations.forEach((el, i) => put(el, at[i]))
                put(root.querySelector('.end.a'), 0)
                put(root.querySelector('.end.b'), 1)
            }
            draw()
            ctx.resize(map, draw)
            const dot = root.querySelector('.dot')
            let f = 0
            let from = 0
            let to = at[0]
            let t0 = performance.now()
            const ease = (x) => (x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2)
            ctx.raf((t) => {
                const p = ctx.reduced ? 1 : Math.min(1, (t - t0) / 1100)
                f = from + (to - from) * ease(p)
                put(dot, f)
                path.style.strokeDashoffset = len * (1 - f)
            })
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    stations.forEach((s, k) => s.classList.toggle('past', k < i))
                    from = f
                    to = at[i]
                    t0 = performance.now()
                    HV.show(root, i)
                },
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Bindeglied',
        group: 1,
        desc: 'Das Bild aus dem Text wird sichtbar: Business und Technik als zwei Netze, codebar in der Mitte, Signale laufen hin und her.',
        css: `
.v12 .bg{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,1fr);border:1px solid var(--g200);border-radius:var(--panel);overflow:hidden;background:#fff}
.v12 .txt{padding:1.75rem 2rem 2rem}
.v12 .nv{display:flex;flex-wrap:wrap;gap:.25rem 1.5rem;margin-bottom:1.5rem;border-bottom:1px solid var(--g200)}
.v12 .t{display:flex;gap:.5rem;align-items:center;padding:.5rem 0 .75rem;font-size:.875rem;color:var(--g500);box-shadow:inset 0 -2px 0 transparent;transition:.3s}
.v12 .t:hover{color:var(--g900)}
.v12 .t[aria-selected=true]{color:var(--brand);box-shadow:inset 0 -2px 0 var(--brand)}
.v12 .net{position:relative;min-height:25rem;background:radial-gradient(ellipse at center,#faf5ff,var(--g50) 70%);border-left:1px solid var(--g200)}
.v12 canvas{position:absolute;inset:0;width:100%;height:100%}
.v12 .lb{position:absolute;top:1.25rem;font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;color:var(--g600);pointer-events:none}
.v12 .lb.l{left:1.25rem}
.v12 .lb.r{right:1.25rem;color:var(--brand)}
.v12 .lb.c{top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;text-transform:none;letter-spacing:0;font-weight:600;font-size:.8125rem}
@media (max-width:860px){.v12 .bg{grid-template-columns:minmax(0,1fr)}.v12 .net{min-height:15rem;border-left:0;border-top:1px solid var(--g200);order:-1}.v12 .txt{padding:1.25rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="bg"><div class="txt"><div class="nv" role="tablist">${HV.tabs('t')}</div>${HV.stack()}</div><div class="net"><canvas aria-hidden="true"></canvas><span class="lb l">Business</span><span class="lb r">Technik</span><span class="lb c">codebar</span></div></div>`
            const cv = root.querySelector('canvas')
            const f = ctx.fit(cv)
            const mk = (side) =>
                Array.from({ length: 13 }, () => {
                    const a = Math.random() * 6.283
                    const r = Math.sqrt(Math.random())
                    return { side, bx: Math.cos(a) * r, by: Math.sin(a) * r, ph: Math.random() * 6.283, sp: 0.5 + Math.random(), x: 0, y: 0 }
                })
            const nodes = [...mk(-1), ...mk(1)]
            const pulses = []
            const C = { x: 0, y: 0 }
            let mode = 0
            let mouse = null
            let acc = 0
            let flip = 0
            const pick = (side) => {
                const pool = side ? nodes.filter((n) => n.side === side) : nodes
                return pool[Math.floor(Math.random() * pool.length)]
            }
            const spawn = () => {
                flip++
                if (mode === 0) pulses.push({ a: pick(0), b: C, t: 0 })
                else if (mode === 1) pulses.push({ a: pick(-1), b: C, t: 0, chain: true })
                else pulses.push(flip % 2 ? { a: C, b: pick(0), t: 0 } : { a: pick(0), b: C, t: 0 })
            }
            ctx.on(cv, 'pointermove', (e) => {
                const b = cv.getBoundingClientRect()
                mouse = { x: e.clientX - b.left, y: e.clientY - b.top }
            })
            ctx.on(cv, 'pointerleave', () => (mouse = null))
            ctx.raf((t, dt) => {
                const { g, w, h } = f
                g.clearRect(0, 0, w, h)
                C.x = w / 2
                C.y = h / 2
                nodes.forEach((n) => {
                    let x = C.x + n.side * w * 0.31 + n.bx * w * 0.14 + Math.cos(t * 0.0007 * n.sp + n.ph) * 9
                    let y = C.y + n.by * h * 0.3 + Math.sin(t * 0.0009 * n.sp + n.ph) * 9
                    if (mouse) {
                        const dx = x - mouse.x
                        const dy = y - mouse.y
                        const d = Math.hypot(dx, dy)
                        if (d < 100 && d > 0) {
                            x += (dx / d) * (100 - d) * 0.35
                            y += (dy / d) * (100 - d) * 0.35
                        }
                    }
                    n.x = x
                    n.y = y
                })
                const th = Math.max(70, w * 0.16)
                g.lineWidth = 1
                for (let a = 0; a < nodes.length; a++) {
                    for (let b = a + 1; b < nodes.length; b++) {
                        if (nodes[a].side !== nodes[b].side) continue
                        const d = Math.hypot(nodes[a].x - nodes[b].x, nodes[a].y - nodes[b].y)
                        if (d > th) continue
                        g.strokeStyle = nodes[a].side < 0 ? `rgba(75,85,99,${(1 - d / th) * 0.4})` : `rgba(80,4,114,${(1 - d / th) * 0.4})`
                        g.beginPath()
                        g.moveTo(nodes[a].x, nodes[a].y)
                        g.lineTo(nodes[b].x, nodes[b].y)
                        g.stroke()
                    }
                }
                g.strokeStyle = 'rgba(80,4,114,.09)'
                nodes.forEach((n) => {
                    g.beginPath()
                    g.moveTo(n.x, n.y)
                    g.lineTo(C.x, C.y)
                    g.stroke()
                })
                if (!ctx.reduced) {
                    acc += dt
                    if (acc > 150) {
                        acc = 0
                        spawn()
                    }
                }
                for (let k = pulses.length - 1; k >= 0; k--) {
                    const p = pulses[k]
                    p.t += dt / 900
                    if (p.t >= 1) {
                        pulses.splice(k, 1)
                        if (p.chain) pulses.push({ a: C, b: pick(1), t: 0 })
                        continue
                    }
                    const e = p.t * p.t * (3 - 2 * p.t)
                    const x = p.a.x + (p.b.x - p.a.x) * e
                    const y = p.a.y + (p.b.y - p.a.y) * e
                    g.fillStyle = 'rgba(192,38,211,.9)'
                    g.beginPath()
                    g.arc(x, y, 3, 0, 6.283)
                    g.fill()
                    g.fillStyle = 'rgba(192,38,211,.18)'
                    g.beginPath()
                    g.arc(x, y, 7, 0, 6.283)
                    g.fill()
                }
                nodes.forEach((n) => {
                    g.fillStyle = n.side < 0 ? '#4b5563' : '#500472'
                    g.beginPath()
                    g.arc(n.x, n.y, 4.5, 0, 6.283)
                    g.fill()
                })
                const pr = 38 + Math.sin(t * 0.003) * 4
                g.fillStyle = 'rgba(80,4,114,.12)'
                g.beginPath()
                g.arc(C.x, C.y, pr + 10, 0, 6.283)
                g.fill()
                g.fillStyle = '#500472'
                g.beginPath()
                g.arc(C.x, C.y, 36, 0, 6.283)
                g.fill()
            })
            const nav = ctx.nav({
                change(i) {
                    mode = i
                    HV.sel(root, i)
                    HV.show(root, i)
                },
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Kartenstapel',
        group: 1,
        desc: 'Drei Karten zum Anfassen: die oberste lässt sich wegziehen oder wegwischen, die nächste rückt nach.',
        css: `
.v13 .deck{display:grid;padding:0 0 2.75rem;touch-action:pan-y}
.v13 .card{grid-area:1/1;position:relative;overflow:hidden;min-width:0;border:1px solid var(--g300);border-radius:1.25rem;background:#fff;padding:1.75rem 2rem 2rem;transform-origin:50% 100%;transition:transform .55s cubic-bezier(.3,1.3,.5,1),box-shadow .4s,opacity .4s;user-select:none}
.v13 .card[data-r="0"]{z-index:3;box-shadow:0 30px 50px -30px rgba(80,4,114,.55);cursor:grab}
.v13 .card[data-r="1"]{z-index:2;transform:translateY(1.25rem) scale(.955);background:var(--g50)}
.v13 .card[data-r="2"]{z-index:1;transform:translateY(2.5rem) scale(.91);background:var(--g100)}
.v13 .card:not([data-r="0"])>*{opacity:.15}
.v13 .card.drag{transition:none;cursor:grabbing}
.v13 .card.out{transform:translate(calc(var(--dir,1)*125%),-2rem) rotate(calc(var(--dir,1)*22deg));opacity:0;transition:transform .32s ease-in,opacity .32s}
.v13 .card>*{position:relative;transition:opacity .3s}
.v13 .big{position:absolute!important;right:1rem;top:-2.5rem;font-size:15rem;line-height:1;font-weight:700;color:var(--brand);opacity:.06!important;letter-spacing:-.06em;pointer-events:none}
.v13 .card header{display:flex;align-items:center;gap:.6rem;margin-bottom:1.1rem;font-size:.875rem;color:var(--brand)}
.v13 .card header em{margin-left:auto;font-style:normal;color:var(--g500);font-size:.75rem}
.v13 .pg{display:flex;justify-content:center;gap:.5rem;margin-top:-1.25rem;position:relative;z-index:4}
.v13 .pg button{width:.55rem;height:.55rem;border-radius:1rem;background:var(--g300);transition:.4s}
.v13 .pg button[aria-selected=true]{width:1.75rem;background:var(--brand)}
@media (max-width:640px){.v13 .card{padding:1.25rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="deck">${S.map((s, i) => `<section class="card"><span class="big" aria-hidden="true">${i + 1}</span><header>${HV.kbd(i + 1)}<span>${s.cmd}</span><em>${i + 1} / 3</em></header>${HV.body(i)}</section>`).join('')}</div><div class="pg" role="tablist">${S.map((s, i) => `<button type="button" role="tab" data-go="${i}" aria-label="${s.title}"></button>`).join('')}</div>`
            const cards = [...root.querySelectorAll('.card')]
            let dir = 1
            const rank = (i) =>
                cards.forEach((c, k) => {
                    c.dataset.r = HV.mod(k - i, 3)
                    c.inert = k !== i
                })
            const nav = ctx.nav({
                change(i, prev) {
                    HV.sel(root, i)
                    if (prev < 0 || ctx.reduced) {
                        rank(i)
                        return
                    }
                    const c = cards[prev]
                    c.style.setProperty('--dir', dir)
                    c.style.transform = ''
                    c.classList.add('out')
                    ctx.later(() => {
                        c.classList.remove('out')
                        rank(nav.i)
                    }, 300)
                    dir = -dir
                },
            })
            let drag = null
            ctx.on(root.querySelector('.deck'), 'pointerdown', (e) => {
                const c = e.target.closest('.card')
                if (!c || c.dataset.r !== '0' || e.target.closest('button,a')) return
                drag = { c, x: e.clientX, y: e.clientY, dx: 0 }
                c.classList.add('drag')
                c.setPointerCapture(e.pointerId)
            })
            ctx.on(root, 'pointermove', (e) => {
                if (!drag) return
                drag.dx = e.clientX - drag.x
                const dy = (e.clientY - drag.y) * 0.3
                drag.c.style.transform = `translate(${drag.dx}px,${dy}px) rotate(${drag.dx / 22}deg)`
            })
            const up = () => {
                if (!drag) return
                const { c, dx } = drag
                drag = null
                c.classList.remove('drag')
                if (Math.abs(dx) > 110) {
                    dir = dx > 0 ? 1 : -1
                    nav.step(1)
                } else c.style.transform = ''
            }
            ctx.on(root, 'pointerup', up)
            ctx.on(root, 'pointercancel', up)
            nav.go(0)
        },
    })

    HV.add({
        name: 'Umlaufbahn',
        group: 1,
        desc: 'codebar als Kern, die drei Themen kreisen darum. Das gewählte Thema dreht sich nach vorne und dockt am Text an.',
        css: `
.v14 .o{display:grid;grid-template-columns:24rem minmax(0,1fr);align-items:center;gap:1rem}
.v14 .sys{position:relative;width:24rem;height:24rem;--R:8.75rem;transform:translate(var(--px,0),var(--py,0));transition:transform .3s ease-out}
.v14 .sys>*{position:absolute;left:50%;top:50%}
.v14 .ring{width:calc(var(--R)*2);height:calc(var(--R)*2);margin:calc(var(--R)*-1);border-radius:50%;border:1.5px dashed var(--g300);animation:v14s 60s linear infinite}
.v14 .ring.b{--R:11.5rem;border-style:dotted;animation-duration:90s;animation-direction:reverse}
.v14 .ring.c{--R:5.5rem;animation-duration:30s}
.v14 .ring::after{content:'';position:absolute;top:-.25rem;left:50%;width:.5rem;height:.5rem;margin-left:-.25rem;border-radius:50%;background:#c026d3}
.v14 .ring.b::after{background:var(--g400)}
@keyframes v14s{to{transform:rotate(360deg)}}
.v14 .core{width:6.5rem;height:6.5rem;margin:-3.25rem;border-radius:50%;background:radial-gradient(circle at 35% 30%,#8b2fb5,var(--brand) 60%,var(--brand-strong));color:#fff;display:grid;place-items:center;font-weight:700;box-shadow:0 0 0 .75rem rgba(80,4,114,.08),0 0 3rem rgba(80,4,114,.45);animation:v14c 4s ease-in-out infinite}
@keyframes v14c{50%{box-shadow:0 0 0 1.25rem rgba(80,4,114,.05),0 0 4rem rgba(80,4,114,.55)}}
.v14 .nd{width:0;height:0;transform:rotate(var(--a)) translateX(var(--R)) rotate(calc(var(--a)*-1));transition:transform 1.1s cubic-bezier(.5,1.3,.4,1)}
.v14 .nd button{position:absolute;transform:translate(-50%,-50%);display:flex;align-items:center;gap:.5rem;white-space:nowrap;padding:.45rem .8rem;border:1px solid var(--g300);border-radius:2rem;background:#fff;font-size:.8125rem;color:var(--g700);box-shadow:0 6px 16px -8px rgba(17,24,39,.3);transition:.3s}
.v14 .nd button:hover{border-color:var(--brand);color:var(--brand)}
.v14 .nd button[aria-selected=true]{background:var(--brand);border-color:var(--brand);color:#fff;transform:translate(-50%,-50%) scale(1.1)}
.v14 .beam{width:calc(50% - var(--R));height:2px;margin-top:-1px;left:calc(50% + var(--R))!important;background:linear-gradient(90deg,var(--brand),transparent);transform-origin:0 50%;transform:scaleX(0)}
.v14 .beam.go{animation:v14b 1.1s .6s cubic-bezier(.2,.8,.2,1) forwards}
@keyframes v14b{to{transform:scaleX(1)}}
.v14 .hv-stack{padding:1rem 0 1rem 1.5rem;border-left:2px solid var(--brand)}
@media (max-width:900px){.v14 .o{grid-template-columns:minmax(0,1fr);justify-items:center}.v14 .sys{width:19rem;height:19rem;--R:6.75rem}.v14 .ring.b{--R:9rem}.v14 .ring.c{--R:4.25rem}.v14 .beam{display:none}.v14 .hv-stack{border-left:0;padding:0;justify-self:stretch}.v14 .nd button span{display:none}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="o"><div class="sys"><span class="ring b"></span><span class="ring"></span><span class="ring c"></span><span class="beam"></span><span class="core">codebar</span><div role="tablist">${S.map((s, i) => `<span class="nd" style="position:absolute;left:50%;top:50%"><button type="button" role="tab" data-go="${i}">${HV.kbd(i + 1)}<span>${s.cmd}</span></button></span>`).join('')}</div></div>${HV.stack()}</div>`
            const sys = root.querySelector('.sys')
            const nds = [...root.querySelectorAll('.nd')]
            const beam = root.querySelector('.beam')
            let turn = 0
            const nav = ctx.nav({
                change(i, prev) {
                    if (prev >= 0) turn += HV.mod(i - prev, 3) === 1 ? -120 : 120
                    nds.forEach((n, k) => n.style.setProperty('--a', turn + k * 120 + 'deg'))
                    HV.sel(root, i)
                    HV.show(root, i)
                    beam.classList.remove('go')
                    void beam.offsetWidth
                    beam.classList.add('go')
                },
            })
            ctx.on(root, 'pointermove', (e) => {
                if (ctx.reduced) return
                const b = sys.getBoundingClientRect()
                sys.style.setProperty('--px', ((e.clientX - b.left - b.width / 2) / b.width) * 10 + 'px')
                sys.style.setProperty('--py', ((e.clientY - b.top - b.height / 2) / b.height) * 10 + 'px')
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Laufschrift',
        group: 1,
        desc: 'Drei Zeilen grosse Laufschrift in Gegenrichtung. Ein Klick füllt die Zeile mit Farbe und klappt den Text darunter auf.',
        css: `
.v15 .rows{border-top:1px solid var(--g300)}
.v15 .row{border-bottom:1px solid var(--g300);overflow:hidden}
.v15 .hd{display:flex;align-items:center;gap:1rem;width:100%;height:4.75rem}
.v15 .mq{flex:1;min-width:0;overflow:hidden;-webkit-mask:linear-gradient(90deg,transparent,#000 3rem,#000 calc(100% - 3rem),transparent);mask:linear-gradient(90deg,transparent,#000 3rem,#000 calc(100% - 3rem),transparent)}
.v15 .trk{display:flex;width:max-content;animation:v15m 38s linear infinite;font-size:3.25rem;line-height:4.75rem;font-weight:700;letter-spacing:-.03em;white-space:nowrap;color:transparent;-webkit-text-stroke:1.5px var(--g400);transition:color .5s,-webkit-text-stroke-color .5s}
.v15 .row:nth-child(even) .trk{animation-direction:reverse}
.v15 .trk span{padding-right:1.5rem}
.v15 .trk i{font-style:normal;padding-right:1.5rem;-webkit-text-stroke:0;color:var(--g300);transition:color .5s}
@keyframes v15m{to{transform:translateX(-50%)}}
.v15 .hd:hover .trk{-webkit-text-stroke-color:var(--brand)}
.v15 .row.on .trk{color:var(--brand);-webkit-text-stroke-color:var(--brand)}
.v15 .row.on .trk i{color:#c026d3}
.v15 .hd .hv-kbd{flex:none}
.v15 .fold{display:grid;grid-template-rows:0fr;transition:grid-template-rows .7s cubic-bezier(.6,0,.2,1)}
.v15 .row.on .fold{grid-template-rows:1fr}
.v15 .in{min-height:0;overflow:hidden}
.v15 .pad{padding:1.25rem 0 1.75rem 2.25rem}
@media (max-width:640px){.v15 .trk{font-size:2.25rem}.v15 .pad{padding-left:0}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="rows" role="tablist">${S.map((s, i) => `<div class="row"><button type="button" class="hd" role="tab" data-go="${i}" aria-label="${s.title}">${HV.kbd(i + 1)}<span class="mq" aria-hidden="true"><span class="trk">${`<span>${s.cmd}</span><i>✦</i>`.repeat(12)}</span></span></button><div class="fold"><div class="in"><div class="pad">${HV.body(i)}</div></div></div></div>`).join('')}</div>`
            const rows = [...root.querySelectorAll('.row')]
            const pads = rows.map((r) => r.querySelector('.pad'))
            const even = () => {
                pads.forEach((p) => (p.style.minHeight = ''))
                const h = Math.max(...pads.map((p) => p.offsetHeight))
                pads.forEach((p) => (p.style.minHeight = h + 'px'))
            }
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    rows.forEach((r, k) => {
                        r.classList.toggle('on', k === i)
                        r.querySelector('.in').inert = k !== i
                    })
                    HV.play(pads[i])
                },
            })
            ctx.resize(root, even)
            even()
            nav.go(0)
        },
    })
})()
