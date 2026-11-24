(() => {
    const { D, S } = HV

    HV.add({
        name: 'Fensterstapel',
        group: 0,
        desc: 'Drei Fenster hintereinander im Raum. Ein Klick holt eines nach vorne, der Stapel neigt sich mit dem Mauszeiger.',
        css: `
.v06 .scene{perspective:1600px;padding:5rem 6.5rem 0 0}
.v06 .stack{display:grid;transform-style:preserve-3d;transform:rotateX(var(--rx,0deg)) rotateY(var(--ry,0deg));transition:transform .25s ease-out}
.v06 .win{grid-area:1/1;min-width:0;border:1px solid var(--g300);border-radius:var(--panel);background:var(--g100);overflow:hidden;transition:transform .75s cubic-bezier(.3,1.4,.5,1),box-shadow .5s,border-color .5s}
.v06 .bar{display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;background:var(--g200);border-bottom:1px solid var(--g300);font-size:.8125rem;color:var(--g700)}
.v06 .bar .hv-kbd{margin-left:auto}
.v06 .bd{padding:1.75rem 2rem 2rem;transition:opacity .4s}
.v06 .win[data-r="0"]{z-index:3;box-shadow:0 30px 60px -25px rgba(80,4,114,.5);border-color:var(--brand)}
.v06 .win[data-r="0"] .bar{color:var(--g900)}
.v06 .win[data-r="1"]{z-index:2;transform:translate3d(3.25rem,-2.5rem,-80px)}
.v06 .win[data-r="2"]{z-index:1;transform:translate3d(6.5rem,-5rem,-160px)}
.v06 .win:not([data-r="0"]){cursor:pointer}
.v06 .win:not([data-r="0"]) .bd{opacity:.25;pointer-events:none}
.v06 .win:not([data-r="0"]):hover{border-color:var(--brand)}
.v06 .win:not([data-r="0"]):hover .bar{background:#f3e8ff;color:var(--brand)}
@media (max-width:640px){.v06 .scene{padding:3.6rem 1.6rem 0 0}.v06 .win[data-r="1"]{transform:translate3d(.8rem,-1.8rem,-40px)}.v06 .win[data-r="2"]{transform:translate3d(1.6rem,-3.6rem,-80px)}.v06 .bd{padding:1.25rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="scene"><div class="stack">${S.map((s, i) => `<section class="win" data-go="${i}"><div class="bar">${HV.dots}<span>${s.cmd}</span>${HV.kbd(i + 1)}</div><div class="bd">${HV.body(i)}</div></section>`).join('')}</div></div>`
            const wins = [...root.querySelectorAll('.win')]
            const stack = root.querySelector('.stack')
            const scene = root.querySelector('.scene')
            const nav = ctx.nav({
                change(i) {
                    wins.forEach((w, k) => {
                        const r = HV.mod(k - i, 3)
                        w.dataset.r = r
                        w.querySelector('.bd').inert = r !== 0
                    })
                    HV.play(wins[i].querySelector('.bd'))
                },
            })
            ctx.on(scene, 'pointermove', (e) => {
                if (ctx.reduced) return
                const b = scene.getBoundingClientRect()
                stack.style.setProperty('--ry', ((e.clientX - b.left) / b.width - 0.5) * 7 + 'deg')
                stack.style.setProperty('--rx', (0.5 - (e.clientY - b.top) / b.height) * 5 + 'deg')
            })
            ctx.on(scene, 'pointerleave', () => {
                stack.style.setProperty('--ry', '0deg')
                stack.style.setProperty('--rx', '0deg')
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Chat',
        group: 0,
        desc: 'Die Vorstellung als Gespräch: Frage antippen, codebar tippt und antwortet in Sprechblasen.',
        css: `
.v07 .c{border:1px solid var(--g300);border-radius:var(--panel);background:#fff;overflow:hidden;box-shadow:0 24px 50px -30px rgba(17,24,39,.35)}
.v07 .hd{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:var(--g100);border-bottom:1px solid var(--g200);font-size:.875rem;color:var(--g900)}
.v07 .av{width:2rem;height:2rem;border-radius:50%;background:var(--brand);color:#fff;display:grid;place-items:center;font-weight:700;position:relative}
.v07 .av::after{content:'';position:absolute;right:-1px;bottom:-1px;width:.6rem;height:.6rem;border-radius:50%;background:#34d399;border:2px solid var(--g100)}
.v07 .hd .hv-dots{margin-left:auto}
.v07 .msgs{height:22rem;overflow:auto;padding:1.25rem;display:flex;flex-direction:column;gap:.5rem;background:radial-gradient(circle at 1px 1px,var(--g200) 1px,transparent 0) 0 0/20px 20px,#fcfcfd}
.v07 .m{max-width:min(42rem,86%);padding:.6rem .95rem;border-radius:1.1rem;background:#fff;border:1px solid var(--g200);align-self:flex-start;border-bottom-left-radius:.3rem;animation:v07in .4s cubic-bezier(.2,1.3,.4,1) both;transform-origin:0 100%}
.v07 .m.u{align-self:flex-end;background:var(--brand);border-color:var(--brand);color:#fff;border-radius:1.1rem;border-bottom-right-radius:.3rem;transform-origin:100% 100%}
.v07 .m b{font-weight:600;color:var(--brand)}
.v07 .m.a{background:none;border:0;padding:.25rem 0}
.v07 .m.a .hv-actions{margin:0}
@keyframes v07in{from{opacity:0;transform:scale(.7) translateY(10px)}}
.v07 .ty{display:inline-flex;gap:.3rem;padding:.3rem 0}
.v07 .ty i{width:.45rem;height:.45rem;border-radius:50%;background:var(--g400);animation:v07d 1s infinite}
.v07 .ty i:nth-child(2){animation-delay:.15s}
.v07 .ty i:nth-child(3){animation-delay:.3s}
@keyframes v07d{0%,60%,100%{transform:none;opacity:.5}30%{transform:translateY(-5px);opacity:1}}
.v07 .quick{display:flex;flex-wrap:wrap;gap:.5rem;padding:.75rem 1rem;border-top:1px solid var(--g200);background:var(--g50)}
.v07 .quick button{display:flex;align-items:center;gap:.5rem;padding:.4rem .9rem;border:1px solid var(--g300);border-radius:2rem;background:#fff;font-size:.875rem;color:var(--g800);transition:.2s}
.v07 .quick button:hover{border-color:var(--brand);color:var(--brand);transform:translateY(-2px)}
.v07 .quick button[aria-selected=true]{border-color:var(--brand);background:#f3e8ff;color:var(--brand)}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="c"><div class="hd"><span class="av" aria-hidden="true">c</span><span>${D.legal}</span>${HV.dots}</div><div class="msgs" aria-live="polite"><div class="m">${D.legend}</div></div><div class="quick" role="tablist">${S.map((s, i) => `<button type="button" role="tab" data-go="${i}">${HV.kbd(i + 1)}<span>${s.title}</span></button>`).join('')}</div></div>`
            const msgs = root.querySelector('.msgs')
            let gen = 0
            const add = (html, cls = '') => {
                const m = document.createElement('div')
                m.className = 'm ' + cls
                m.innerHTML = html
                msgs.append(m)
                msgs.scrollTo({ top: msgs.scrollHeight, behavior: ctx.reduced ? 'auto' : 'smooth' })
                return m
            }
            const nav = ctx.nav({
                async change(i) {
                    const g = ++gen
                    const ok = () => g === gen
                    HV.sel(root, i)
                    msgs.querySelectorAll('.t').forEach((n) => n.remove())
                    add(S[i].title, 'u')
                    await ctx.wait(350)
                    if (!ok()) return
                    const t = add('<span class="ty"><i></i><i></i><i></i></span>', 't')
                    await ctx.wait(ctx.reduced ? 0 : 900)
                    t.remove()
                    if (!ok()) return
                    add(S[i].text)
                    for (const [b, tx] of S[i].items || []) {
                        await ctx.wait(ctx.reduced ? 0 : 420)
                        if (!ok()) return
                        add(`<b>${b}</b> – ${tx}`)
                    }
                    await ctx.wait(ctx.reduced ? 0 : 420)
                    if (ok()) add(HV.actions(i), 'a')
                },
            })
            ctx.later(() => nav.go(0), 500)
        },
    })

    HV.add({
        name: 'Kacheln',
        group: 0,
        desc: 'Wie ein Tiling-Fenstermanager: drei Flächen nebeneinander, die aktive dehnt sich aus, die anderen bleiben als Streifen sichtbar.',
        css: `
.v08 .wm{border:1px solid var(--g300);border-radius:var(--panel);background:var(--g200);overflow:hidden}
.v08 .top{display:flex;align-items:center;gap:.75rem;padding:.6rem 1rem;font-size:.75rem;color:var(--g700)}
.v08 .ws{margin-left:auto;display:flex;gap:.3rem}
.v08 .ws button{border:1px solid var(--g400);background:var(--g50);transition:.2s}
.v08 .ws button[aria-selected=true]{background:var(--brand);border-color:var(--brand);color:#fff}
.v08 .panes{display:flex;gap:.5rem;padding:0 .5rem .5rem}
.v08 .pane{position:relative;flex:0 0 3.75rem;min-width:0;overflow:hidden;border:1px solid var(--g300);border-radius:.5rem;background:var(--g100);cursor:pointer;transition:flex-basis .65s cubic-bezier(.6,0,.2,1),flex-grow .65s cubic-bezier(.6,0,.2,1),border-color .3s,background .3s}
.v08 .pane:hover{border-color:var(--brand);background:#faf5ff}
.v08 .pane.on{flex:1 1 0;cursor:auto;border-color:var(--brand);background:#fff;box-shadow:0 0 0 3px rgba(80,4,114,.1)}
.v08 .cap{position:absolute;inset:0 auto 0 0;width:3.75rem;display:flex;flex-direction:column;align-items:center;gap:.75rem;padding:1rem 0;font-size:.875rem;color:var(--g600);transition:opacity .3s}
.v08 .cap span{writing-mode:vertical-rl;white-space:nowrap}
.v08 .pane.on .cap{opacity:0;pointer-events:none}
.v08 .inner{width:var(--w,40rem);padding:1.5rem 1.75rem 1.75rem;opacity:0;transition:opacity .25s}
.v08 .pane.on .inner{opacity:1;transition:opacity .45s .3s}
.v08 .inner header{display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;font-size:.8125rem;color:var(--brand)}
@media (max-width:760px){.v08 .panes{flex-direction:column}.v08 .pane,.v08 .pane.on{flex:none}.v08 .pane:not(.on){height:3rem}.v08 .pane:not(.on) .inner{display:none}.v08 .cap{inset:0;width:auto;flex-direction:row;padding:0 1rem}.v08 .cap span{writing-mode:horizontal-tb}.v08 .inner{width:auto;padding:1.25rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="wm"><div class="top">${HV.dots}<span>${D.legal}</span><span class="ws" role="tablist">${S.map((s, i) => `<button type="button" class="hv-kbd" role="tab" data-go="${i}" aria-label="${s.title}">${i + 1}</button>`).join('')}</span></div><div class="panes">${S.map((s, i) => `<section class="pane" data-go="${i}"><div class="cap" aria-hidden="true">${HV.kbd(i + 1)}<span>${s.cmd}</span></div><div class="inner"><header>${HV.kbd(i + 1)}<span>${s.cmd}</span></header>${HV.body(i)}</div></section>`).join('')}</div></div>`
            const panes = root.querySelector('.panes')
            const list = [...root.querySelectorAll('.pane')]
            const size = () => {
                const closed = parseFloat(getComputedStyle(document.documentElement).fontSize) * 3.75
                panes.style.setProperty('--w', Math.max(240, panes.clientWidth - 16 - 16 - 2 * closed - 2) + 'px')
            }
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    list.forEach((p, k) => {
                        p.classList.toggle('on', k === i)
                        p.querySelector('.inner').inert = k !== i
                    })
                    HV.play(list[i].querySelector('.inner'))
                },
            })
            ctx.resize(panes, size)
            size()
            nav.go(0)
        },
    })

    const icons = [
        `<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect class="d1" x="6" y="9" width="36" height="28" rx="3" pathLength="100"/><path class="d2" d="M6 17h36M12 24h12M12 30h8" pathLength="100"/><path class="pt" d="m30 24 9 4-4 1.5L33.5 34z" fill="currentColor"/></svg>`,
        `<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path class="bl" d="m17 14-10 10 10 10"/><path class="br" d="m31 14 10 10-10 10"/><path class="sl" d="m27 10-6 28"/></svg>`,
        `<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect class="p3" x="12" y="6" width="22" height="28" rx="2" fill="#fff"/><rect class="p2" x="14" y="10" width="22" height="28" rx="2" fill="#fff"/><g class="p1"><rect x="16" y="14" width="22" height="28" rx="2" fill="#fff"/><path d="M21 22h12M21 28h12M21 34h7"/></g></svg>`,
        `<svg viewBox="0 0 48 48" fill="currentColor"><rect class="m m1" x="7" y="7" width="15" height="15" rx="3"/><rect class="m m2" x="26" y="7" width="15" height="15" rx="3"/><rect class="m m3" x="26" y="26" width="15" height="15" rx="3"/><rect class="m m4" x="7" y="26" width="15" height="15" rx="3"/></svg>`,
    ]

    HV.add({
        name: 'Bento',
        group: 1,
        desc: 'Alles auf einen Blick, ohne Klick: ein Kachelraster mit animierten Symbolen und einem Lichtkegel, der dem Mauszeiger folgt.',
        css: `
.v09 .g{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:.75rem}
.v09 .tile{position:relative;overflow:hidden;border:1px solid var(--g200);border-radius:var(--panel);background:var(--g50);padding:1.4rem 1.5rem;animation:hvUp .7s cubic-bezier(.2,.8,.2,1) both;animation-delay:calc(var(--i)*80ms);transition:transform .3s,border-color .3s,box-shadow .3s}
.v09 .tile::after{content:'';position:absolute;inset:0;background:radial-gradient(340px circle at var(--mx,-999px) var(--my,-999px),rgba(80,4,114,.13),transparent 65%);pointer-events:none}
.v09 .tile:hover{transform:translateY(-3px);border-color:#c9a3dd;box-shadow:0 18px 30px -22px rgba(80,4,114,.5)}
.v09 .tile>*{position:relative;z-index:1}
.v09 .lb{display:flex;align-items:center;gap:.5rem;font-size:.75rem;color:var(--g500);margin-bottom:.75rem}
.v09 .who{grid-column:span 5;background:var(--brand);border-color:var(--brand);color:#fff;font-size:1.0625rem;line-height:1.6}
.v09 .who::after{background:radial-gradient(340px circle at var(--mx,-999px) var(--my,-999px),rgba(255,255,255,.2),transparent 65%)}
.v09 .who .lb{color:#e9d5ff}
.v09 .who .hv-kbd{background:transparent;border-color:#c9a3dd;color:#fff}
.v09 .how{grid-column:span 7;background:#fff}
.v09 .what{grid-column:1/-1;border:0;background:none;padding:.5rem .25rem 0;overflow:visible}
.v09 .what:hover{transform:none;box-shadow:none}
.v09 .what::after{display:none}
.v09 .what .lb{margin:0}
.v09 .what p{color:var(--g900);font-weight:500}
.v09 .sv{grid-column:span 3;font-size:.875rem;line-height:1.55}
.v09 .sv svg{width:2.75rem;height:2.75rem;color:var(--brand);margin-bottom:.6rem;display:block}
.v09 .sv b{display:block;font-weight:600;color:var(--g900);font-size:1rem;margin-bottom:.2rem}
.v09 .d1,.v09 .d2{stroke-dasharray:100;animation:v09d 5s ease-in-out infinite}
.v09 .d2{animation-delay:.5s}
@keyframes v09d{0%{stroke-dashoffset:100}35%,85%{stroke-dashoffset:0}100%{stroke-dashoffset:100}}
.v09 .pt{animation:v09p 5s ease-in-out infinite}
@keyframes v09p{0%,30%{transform:translate(8px,8px);opacity:0}45%{transform:none;opacity:1}55%{transform:scale(.9)}85%{opacity:1}100%{opacity:0}}
.v09 .bl{animation:v09l 2.4s ease-in-out infinite}
.v09 .br{animation:v09l 2.4s ease-in-out infinite reverse}
@keyframes v09l{50%{transform:translateX(-3px)}}
.v09 .sl{animation:v03b 1.2s steps(1) infinite}
@keyframes v03b{50%{opacity:.2}}
.v09 .p1{animation:v09s 4s cubic-bezier(.6,0,.3,1) infinite}
.v09 .p2{animation:v09s 4s cubic-bezier(.6,0,.3,1) infinite .15s}
@keyframes v09s{0%,60%,100%{transform:none}30%{transform:translate(7px,-5px) rotate(6deg)}}
.v09 .m{transform-box:fill-box;transform-origin:center;animation:v09m 3.2s ease-in-out infinite;opacity:.3}
.v09 .m2{animation-delay:.4s}.v09 .m3{animation-delay:.8s}.v09 .m4{animation-delay:1.2s}
@keyframes v09m{0%,60%,100%{opacity:.3;transform:scale(.9)}20%{opacity:1;transform:scale(1.05)}}
@media (max-width:900px){.v09 .who,.v09 .how{grid-column:1/-1}.v09 .sv{grid-column:span 6}}
@media (max-width:560px){.v09 .sv{grid-column:1/-1}}`,
        mount(root, ctx) {
            const lb = (i) => `<div class="lb">${HV.kbd(i + 1)}<span>${S[i].cmd}</span></div>`
            root.innerHTML = `<div class="g"><section class="tile who" style="--i:0"><h2 class="sr">${S[0].title}</h2>${lb(0)}<p>${S[0].text}</p></section><section class="tile how" style="--i:1"><h2 class="sr">${S[2].title}</h2>${lb(2)}<p>${S[2].text}</p>${HV.actions(2)}</section><div class="tile what" style="--i:2"><h2 class="sr">${S[1].title}</h2>${lb(1)}<p>${S[1].text}</p></div>${S[1].items.map(([b, t], k) => `<section class="tile sv" style="--i:${3 + k}">${icons[k]}<b>${b}</b><span>${t.charAt(0).toUpperCase() + t.slice(1)}</span></section>`).join('')}</div>`
            const tiles = [...root.querySelectorAll('.tile')]
            ctx.on(root, 'pointermove', (e) => {
                tiles.forEach((t) => {
                    const b = t.getBoundingClientRect()
                    t.style.setProperty('--mx', e.clientX - b.left + 'px')
                    t.style.setProperty('--my', e.clientY - b.top + 'px')
                })
            })
            ctx.on(root, 'pointerleave', () => tiles.forEach((t) => t.style.setProperty('--mx', '-999px')))
        },
    })

    HV.add({
        name: 'Editorial',
        group: 1,
        desc: 'Kein Rahmen, nur Typografie: grosse Ziffer als Zählwerk, grosser Lauftext, der Wort für Wort scharf wird.',
        css: `
.v10 .ed{display:grid;grid-template-columns:11rem minmax(0,1fr);gap:0 2.5rem;padding-top:.5rem}
.v10 .num{height:9rem;overflow:hidden;font-size:9rem;line-height:1;font-weight:700;letter-spacing:-.06em;color:transparent;-webkit-text-stroke:2px var(--brand)}
.v10 .roll{display:grid;transition:transform .9s cubic-bezier(.7,0,.2,1)}
.v10 .roll span{height:9rem}
.v10 .hv-pan h3{margin:0 0 1rem;font-size:2.75rem;line-height:1.1;letter-spacing:-.03em;font-weight:700;color:var(--g900)}
.v10 .hv-text p{font-size:1.3rem;line-height:1.5;font-weight:300;color:var(--g900);max-width:52ch}
.v10 .hv-list{grid-template-columns:1fr 1fr;gap:.75rem 2.5rem;counter-reset:n;font-size:.9375rem}
.v10 .hv-list li{padding-left:2.25rem;counter-increment:n}
.v10 .hv-list li::before{content:'0' counter(n);left:0;font-size:.75rem;color:var(--brand);top:.2em;font-weight:600}
.v10 .hv-list b{font-weight:600}
.v10 .wd{opacity:0;filter:blur(8px);transition:opacity .5s,filter .5s}
.v10 .wd.on{opacity:1;filter:none}
.v10 .hv-actions{animation:none!important}
.v10 .rail{grid-column:1/-1;display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:2rem}
.v10 .rail button{position:relative;padding-top:.8rem;border-top:2px solid var(--g200);display:flex;gap:.6rem;align-items:center;font-size:.875rem;color:var(--g500);transition:color .3s}
.v10 .rail button::before{content:'';position:absolute;left:0;top:-2px;height:2px;width:100%;background:var(--brand);transform:scaleX(0);transform-origin:0 0;transition:transform .9s cubic-bezier(.7,0,.2,1)}
.v10 .rail button[aria-selected=true]{color:var(--g900)}
.v10 .rail button[aria-selected=true]::before{transform:scaleX(1)}
.v10 .rail button:hover{color:var(--brand)}
@media (max-width:760px){.v10 .ed{grid-template-columns:minmax(0,1fr)}.v10 .num{height:5rem;font-size:5rem}.v10 .roll span{height:5rem}.v10 .hv-pan h3{font-size:2rem}.v10 .hv-text p{font-size:1.1rem}.v10 .hv-list{grid-template-columns:1fr}.v10 .rail span{display:none}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="ed"><div class="num" aria-hidden="true"><div class="roll"><span>01</span><span>02</span><span>03</span></div></div><div class="hv-stack">${S.map((s, i) => `<div class="hv-pan" data-p="${i}"><h3>${s.title}</h3>${HV.text(i)}${HV.actions(i)}</div>`).join('')}</div><div class="rail" role="tablist">${HV.tabs('r')}</div></div>`
            const roll = root.querySelector('.roll')
            const words = [...root.querySelectorAll('.hv-pan')].map((p) => HV.words(p.querySelector('.hv-text')))
            let gen = 0
            const nav = ctx.nav({
                change(i) {
                    const g = ++gen
                    HV.sel(root, i)
                    roll.style.transform = `translateY(${(-100 / 3) * i}%)`
                    words.flat().forEach((w) => w.classList.remove('on'))
                    HV.show(root, i, false)
                    ctx.later(() => ctx.stream(words[i], 18, () => g === gen), 120)
                },
            })
            nav.go(0)
        },
    })
})()
