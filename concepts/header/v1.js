(() => {
    const { D, S } = HV

    HV.add({
        name: 'Fenster, poliert',
        group: 0,
        desc: 'Das heutige Fenster, aber mit gleitendem Tab-Marker, Lichtkante am Mauszeiger und gestaffeltem Einblenden.',
        css: `
.v01 .w{position:relative;border:1px solid var(--g300);border-radius:var(--panel);background:var(--g100);overflow:hidden;box-shadow:0 1px 2px rgba(17,24,39,.04),0 18px 40px -24px rgba(80,4,114,.35)}
.v01 .glow{position:absolute;inset:0;border-radius:inherit;padding:2px;background:radial-gradient(320px circle at var(--mx,50%) var(--my,-30%),var(--brand),transparent 70%);-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none;opacity:0;transition:opacity .4s;z-index:3}
.v01 .w:hover .glow{opacity:1}
.v01 .bar{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:var(--g200);border-bottom:1px solid var(--g300);font-size:.75rem;color:var(--g700)}
.v01 .tabs{position:relative;display:flex;flex-wrap:wrap;background:var(--g200);border-bottom:1px solid var(--g300)}
.v01 .ind{position:absolute;top:0;left:0;background:var(--g100);box-shadow:inset 0 2px 0 var(--brand);transition:transform .5s cubic-bezier(.3,1.35,.5,1),width .5s cubic-bezier(.3,1.35,.5,1)}
.v01 .tab{position:relative;display:flex;align-items:center;gap:.6rem;padding:.75rem 1rem;border-right:1px solid var(--g300);font-size:.875rem;color:var(--g600);transition:color .2s}
.v01 .tab:hover,.v01 .tab[aria-selected=true]{color:var(--g900)}
.v01 .arrows{margin-left:auto;display:flex;align-items:center;gap:.4rem;padding:0 1rem}
.v01 .arrows button{border:1px solid var(--g400);background:var(--g50)}
.v01 .arrows button:hover{background:#fff}
.v01 .panels{padding:2rem;min-height:20rem}
@media (max-width:640px){.v01 .panels{padding:1.25rem}.v01 .arrows{display:none}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="w"><div class="glow"></div><div class="bar">${HV.dots}<span>${D.legal}</span></div><div class="tabs" role="tablist"><span class="ind"></span>${HV.tabs('tab')}<span class="arrows"><button type="button" class="hv-kbd" data-step="-1" aria-label="${D.prev}">←</button><button type="button" class="hv-kbd" data-step="1" aria-label="${D.nextSection}">→</button></span></div><div class="panels">${HV.stack()}</div></div>`
            const w = root.querySelector('.w')
            const ind = root.querySelector('.ind')
            const place = () => {
                const t = root.querySelector('.tab[aria-selected="true"]')
                if (!t) return
                ind.style.width = t.offsetWidth + 'px'
                ind.style.height = t.offsetHeight + 'px'
                ind.style.transform = `translate(${t.offsetLeft}px,${t.offsetTop}px)`
            }
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    HV.show(root, i)
                    place()
                },
            })
            ctx.on(w, 'pointermove', (e) => {
                const b = w.getBoundingClientRect()
                w.style.setProperty('--mx', e.clientX - b.left + 'px')
                w.style.setProperty('--my', e.clientY - b.top + 'px')
            })
            ctx.resize(w, place)
            nav.go(0)
        },
    })

    HV.add({
        name: 'Terminal',
        group: 0,
        desc: 'Ein echtes Terminal: Befehle werden getippt, die Antwort läuft ein. Eigene Eingabe mit Tab-Vervollständigung funktioniert.',
        css: `
.v02 .t{border-radius:var(--panel);background:#17111f;color:#e9e2f2;font-family:var(--mono);font-size:.8125rem;line-height:1.7;overflow:hidden;box-shadow:0 30px 60px -30px rgba(80,4,114,.6)}
.v02 .bar{display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;background:#221a2d;color:#a99bb8;font-size:.75rem}
.v02 .scr{height:27rem;overflow:auto;padding:1.25rem 1.5rem;cursor:text}
.v02 .pr{color:#c08cf0;white-space:nowrap}
.v02 .ln{display:flex;gap:.6rem}
.v02 .res{margin:.75rem 0 1rem}
.v02 .wd{opacity:0}
.v02 .wd.on{opacity:1}
.v02 .hv-list li::before{content:'▸';color:#c08cf0;left:0}
.v02 .hv-list b{color:#f0b3ff;font-weight:700}
.v02 .hv-actions{opacity:0;transition:opacity .3s}
.v02 .hv-actions.on{opacity:1}
.v02 .hv-btn{background:transparent;border-color:#6b4a85;color:#e9e2f2;font-family:var(--mono);font-size:.8125rem}
.v02 .hv-btn:hover,.v02 .hv-btn--solid{background:#c08cf0;border-color:#c08cf0;color:#17111f}
.v02 .in{display:flex;gap:.6rem;align-items:center}
.v02 .in input{flex:1;min-width:0;background:none;border:0;outline:0;color:inherit;font:inherit;caret-color:#c08cf0}
.v02 .in input::placeholder{color:#6f6280}
.v02 .cmds{display:flex;flex-wrap:wrap;gap:.5rem;padding:.75rem 1rem;background:#221a2d}
.v02 .cmds button{display:flex;align-items:center;gap:.5rem;padding:.3rem .7rem;border:1px solid #3d3050;border-radius:var(--pill);color:#cdbfe0;transition:.2s}
.v02 .cmds button:hover,.v02 .cmds button[aria-selected=true]{border-color:#c08cf0;color:#fff}
.v02 .cmds .hv-kbd{background:#2f2540;border-color:#57456f;color:#e9e2f2}
.v02 .err{color:#fca5a5}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="t"><div class="bar">${HV.dots}<span>${D.legal} — zsh</span></div><div class="scr"><div class="out" aria-live="polite"></div><form class="in"><span class="pr">codebar ~ %</span><input aria-label="${D.legend}" placeholder="${S.map((s) => s.cmd).join(' | ')}" autocomplete="off" autocapitalize="off" spellcheck="false"></form></div><div class="cmds" role="tablist">${HV.tabs('c')}</div></div>`
            const out = root.querySelector('.out')
            const scr = root.querySelector('.scr')
            const input = root.querySelector('input')
            let gen = 0
            const run = async (i) => {
                const g = ++gen
                const ok = () => g === gen
                HV.sel(root, i)
                out.innerHTML = `<div class="ln"><span class="pr">codebar ~ %</span><span class="c"></span></div>`
                await ctx.type(out.querySelector('.c'), S[i].cmd, 34, ok)
                if (!ok()) return
                const res = document.createElement('div')
                res.className = 'res'
                res.innerHTML = HV.body(i)
                const words = HV.words(res.querySelector('.hv-text'))
                out.append(res)
                await ctx.stream(words, 9, ok)
                if (ok()) res.querySelector('.hv-actions').classList.add('on')
            }
            const nav = ctx.nav({ change: run })
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
                const k = S.findIndex((s) => s.cmd === v || s.cmd.startsWith(v) || String(S.indexOf(s) + 1) === v)
                if (k >= 0) {
                    if (nav.i === k) run(k)
                    else nav.go(k)
                    return
                }
                gen++
                const line = document.createElement('div')
                line.innerHTML = `<div class="ln"><span class="pr">codebar ~ %</span><span></span></div><div class="err"></div>`
                line.querySelector('.ln span:last-child').textContent = v
                line.querySelector('.err').textContent = `zsh: command not found: ${v}`
                out.append(line)
                scr.scrollTop = scr.scrollHeight
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Editor',
        group: 0,
        desc: 'Die drei Abschnitte als Markdown-Dateien in einem Code-Editor: Dateibaum, Zeilennummern, Minimap, Text wird geschrieben.',
        css: `
.v03 .e{display:grid;grid-template-columns:13.5rem minmax(0,1fr);border:1px solid var(--g300);border-radius:var(--panel);overflow:hidden;background:#fff;box-shadow:0 24px 50px -30px rgba(17,24,39,.35)}
.v03 .top{grid-column:1/-1;display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;background:var(--g200);border-bottom:1px solid var(--g300);font-size:.75rem;color:var(--g700)}
.v03 .side{background:var(--g50);border-right:1px solid var(--g200);padding:.9rem .5rem;font-size:.8125rem}
.v03 .side small{display:block;padding:0 .6rem .5rem;font-size:.6875rem;letter-spacing:.08em;text-transform:uppercase;color:var(--g500)}
.v03 .side .dir{padding:.2rem .6rem;color:var(--g700);font-weight:500}
.v03 .file{display:flex;width:100%;align-items:center;gap:.5rem;padding:.35rem .6rem .35rem 1.4rem;border-radius:var(--pill);color:var(--g600);font-family:var(--mono);font-size:.75rem}
.v03 .file:hover{background:var(--g100)}
.v03 .file[aria-selected=true]{background:#f3e8ff;color:var(--brand)}
.v03 .file .hv-kbd{margin-left:auto}
.v03 .file i{width:.7rem;height:.85rem;border:1.5px solid currentColor;border-radius:2px;opacity:.7}
.v03 .main{min-width:0;display:grid;grid-template-rows:auto 1fr}
.v03 .et{display:flex;background:var(--g100);border-bottom:1px solid var(--g200);font-family:var(--mono);font-size:.75rem}
.v03 .et span{display:flex;align-items:center;gap:.5rem;padding:.55rem 1rem;background:#fff;border-right:1px solid var(--g200);box-shadow:inset 0 2px 0 var(--brand);color:var(--g800)}
.v03 .et span::after{content:'';width:.45rem;height:.45rem;border-radius:50%;background:var(--brand)}
.v03 .ed{position:relative;display:grid;grid-template-columns:minmax(0,1fr) 5rem;min-height:21rem}
.v03 .code{padding:1rem 1rem 1rem 0;counter-reset:ln;font-family:var(--mono);font-size:.8125rem;line-height:1.75;min-width:0}
.v03 .l{position:relative;padding-left:3.5rem;counter-increment:ln}
.v03 .l::before{content:counter(ln);position:absolute;left:0;width:2.5rem;text-align:right;color:var(--g400)}
.v03 .l.cur{background:var(--g50)}
.v03 .mk{color:var(--g400)}
.v03 .hd{font-weight:700;color:var(--brand)}
.v03 .l b{color:var(--brand);font-weight:700}
.v03 .wd{opacity:0}
.v03 .wd.on{opacity:1}
.v03 .caret{display:inline-block;width:2px;height:1.1em;margin-left:1px;background:var(--brand);vertical-align:text-bottom;animation:v03b 1s steps(1) infinite}
@keyframes v03b{50%{opacity:0}}
.v03 .act{padding-top:.5rem;padding-bottom:.25rem}
.v03 .act .hv-actions{margin-top:0}
.v03 .mini{border-left:1px solid var(--g100);padding:.75rem .5rem;font-family:var(--mono);font-size:2.4px;line-height:4.2px;color:var(--g400);overflow:hidden;user-select:none}
.v03 .mini .hd,.v03 .mini b{color:var(--brand)}
.v03 .mini .act{display:none}
.v03 .st{grid-column:1/-1;display:flex;gap:1.25rem;padding:.3rem 1rem;background:var(--brand);color:#fff;font-size:.6875rem}
.v03 .st span:last-child{margin-left:auto}
@media (max-width:760px){.v03 .e{grid-template-columns:minmax(0,1fr)}.v03 .side{border-right:0;border-bottom:1px solid var(--g200)}.v03 .ed{grid-template-columns:minmax(0,1fr)}.v03 .mini{display:none}.v03 .l{padding-left:2.5rem}.v03 .l::before{width:1.75rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="e"><div class="top">${HV.dots}<span>${D.legal}</span></div><div class="side"><small>Explorer</small><div class="dir">▾ codebar</div><div role="tablist">${S.map((s, i) => `<button type="button" class="file" role="tab" data-go="${i}"><i></i><span>${s.cmd}.md</span>${HV.kbd(i + 1)}</button>`).join('')}</div></div><div class="main"><div class="et"><span></span></div><div class="ed"><div class="code"></div><div class="mini" aria-hidden="true"></div></div></div><div class="st"><span>⎇ main</span><span>Markdown</span><span>UTF-8</span><span class="pos"></span></div></div>`
            const code = root.querySelector('.code')
            const mini = root.querySelector('.mini')
            const lines = (i) => {
                const s = S[i]
                const L = [`<span class="mk"># </span><span class="hd">${s.title}</span>`, '', s.text, '']
                if (s.items) {
                    s.items.forEach(([b, t]) => L.push(`<span class="mk">- **</span><b>${b}</b><span class="mk">**</span> – ${t}`))
                    L.push('')
                }
                return L.map((x) => `<div class="l"><span class="tx">${x || '&nbsp;'}</span></div>`).join('') + `<div class="l act">${HV.actions(i)}</div>`
            }
            let gen = 0
            const nav = ctx.nav({
                async change(i) {
                    const g = ++gen
                    HV.sel(root, i)
                    root.querySelector('.et span').textContent = S[i].cmd + '.md'
                    code.innerHTML = lines(i)
                    mini.innerHTML = code.innerHTML
                    const rows = [...code.querySelectorAll('.l')]
                    root.querySelector('.pos').textContent = `Ln ${rows.length}, Col 1`
                    const words = [...code.querySelectorAll('.tx')].flatMap((t) => HV.words(t))
                    await ctx.stream(words, 12, () => g === gen)
                    if (g !== gen) return
                    const last = rows[rows.length - 3] || rows[0]
                    last.classList.add('cur')
                    last.querySelector('.tx').insertAdjacentHTML('beforeend', '<span class="caret"></span>')
                },
            })
            nav.go(0)
        },
    })

    HV.add({
        name: 'Befehlspalette',
        group: 0,
        desc: 'Eine Befehlspalette über einer angedeuteten Anwendung: tippen filtert, Pfeiltasten wählen, rechts erscheint die Vorschau.',
        css: `
.v04 .w{position:relative;border:1px solid var(--g300);border-radius:var(--panel);background:var(--g100);overflow:hidden}
.v04 .bar{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:var(--g200);border-bottom:1px solid var(--g300);font-size:.75rem;color:var(--g700)}
.v04 .bg{position:absolute;inset:2.7rem 0 0;display:grid;grid-template-columns:11rem 1fr;gap:1.5rem;padding:1.5rem;filter:blur(3px);opacity:.7}
.v04 .bg div{display:grid;gap:.8rem;align-content:start}
.v04 .bg i{height:.75rem;border-radius:1rem;background:linear-gradient(90deg,var(--g200),var(--g300),var(--g200));background-size:200% 100%;animation:v04s 3s linear infinite}
.v04 .bg i:nth-child(3n){width:60%}
.v04 .bg i:nth-child(4n){width:80%}
@keyframes v04s{to{background-position:-200% 0}}
.v04 .pal{position:relative;margin:2rem auto;width:min(58rem,calc(100% - 3rem));background:#fff;border:1px solid var(--g300);border-radius:var(--panel);box-shadow:0 30px 70px -20px rgba(80,4,114,.45),0 0 0 6px rgba(80,4,114,.06);animation:v04p .5s cubic-bezier(.2,1.2,.3,1) both;overflow:hidden}
@keyframes v04p{from{opacity:0;transform:translateY(-12px) scale(.96)}}
.v04 .q{display:flex;align-items:center;gap:.75rem;padding:.9rem 1.1rem;border-bottom:1px solid var(--g200)}
.v04 .q svg{flex:none;color:var(--g500)}
.v04 .q input{flex:1;min-width:0;border:0;outline:0;font:inherit;font-size:1rem;color:var(--g900);background:none}
.v04 .cols{display:grid;grid-template-columns:17rem minmax(0,1fr)}
.v04 .res{position:relative;padding:.5rem;border-right:1px solid var(--g200);background:var(--g50)}
.v04 .hl{position:absolute;left:.5rem;right:.5rem;top:0;border-radius:var(--pill);background:var(--brand);transition:transform .35s cubic-bezier(.3,1.3,.5,1),height .35s,opacity .2s}
.v04 .r{position:relative;display:grid;grid-template-columns:auto 1fr auto;gap:.1rem .7rem;width:100%;padding:.6rem .7rem;align-items:center;color:var(--g800);transition:color .2s}
.v04 .r[hidden]{display:none}
.v04 .r small{grid-column:2;color:var(--g500);font-size:.75rem;line-height:1.35;transition:color .2s}
.v04 .r em{grid-row:1/3;grid-column:3;font-style:normal;opacity:0;font-size:.75rem}
.v04 .r[aria-selected=true]{color:#fff}
.v04 .r[aria-selected=true] small{color:#e9d5ff}
.v04 .r[aria-selected=true] em{opacity:1}
.v04 .r .hv-kbd{grid-row:1/3}
.v04 .pre{padding:1.5rem 1.75rem;min-height:19rem}
.v04 .foot{display:flex;gap:1rem;padding:.5rem 1rem;border-top:1px solid var(--g200);background:var(--g50);font-size:.75rem;color:var(--g500)}
@media (max-width:760px){.v04 .cols{grid-template-columns:1fr}.v04 .res{border-right:0;border-bottom:1px solid var(--g200)}.v04 .pal{margin:1rem auto;width:calc(100% - 1.5rem)}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="w"><div class="bar">${HV.dots}<span>${D.legal}</span></div><div class="bg" aria-hidden="true"><div>${'<i></i>'.repeat(9)}</div><div>${'<i></i>'.repeat(12)}</div></div><div class="pal"><label class="q"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><input type="text" placeholder="${D.legend}" aria-label="${D.legend}" autocomplete="off" spellcheck="false">${HV.kbd('esc')}</label><div class="cols"><div class="res" role="tablist"><span class="hl"></span>${S.map((s, i) => `<button type="button" class="r" role="tab" data-go="${i}">${HV.kbd(i + 1)}<span>${s.cmd}</span><em>↵</em><small>${s.teaser}</small></button>`).join('')}</div><div class="pre">${HV.stack()}</div></div><div class="foot"><span>${HV.kbd('↑')} ${HV.kbd('↓')}</span><span>${HV.kbd('1')} ${HV.kbd('2')} ${HV.kbd('3')}</span><span>${HV.kbd('↵')}</span></div></div></div>`
            const hl = root.querySelector('.hl')
            const input = root.querySelector('input')
            const rows = [...root.querySelectorAll('.r')]
            const place = () => {
                const r = rows.find((x) => x.getAttribute('aria-selected') === 'true')
                if (!r || r.hidden) {
                    hl.style.opacity = 0
                    return
                }
                hl.style.opacity = 1
                hl.style.height = r.offsetHeight + 'px'
                hl.style.transform = `translateY(${r.offsetTop}px)`
            }
            const nav = ctx.nav({
                change(i) {
                    HV.sel(root, i)
                    HV.show(root, i)
                    place()
                },
            })
            const visible = () => rows.filter((r) => !r.hidden).map((r) => +r.dataset.go)
            ctx.on(input, 'input', () => {
                const q = input.value.trim().toLowerCase()
                rows.forEach((r, i) => (r.hidden = q !== '' && !(S[i].cmd + ' ' + S[i].title + ' ' + S[i].teaser).toLowerCase().includes(q)))
                const v = visible()
                if (v.length && !v.includes(nav.i)) nav.go(v[0])
                place()
            })
            ctx.on(window, 'keydown', (e) => {
                if (e.key === 'Escape') {
                    input.value = ''
                    input.dispatchEvent(new Event('input'))
                    input.blur()
                    return
                }
                if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') return
                const v = visible()
                if (!v.length) return
                e.preventDefault()
                const at = v.indexOf(nav.i)
                nav.go(v[HV.mod(at + (e.key === 'ArrowDown' ? 1 : -1), v.length)])
            })
            ctx.resize(root.querySelector('.res'), place)
            nav.go(0)
        },
    })

    HV.add({
        name: 'Browser',
        group: 0,
        desc: 'Ein Browserfenster: jede Sektion ist ein Tab mit eigener Adresse, Ladebalken und kurzem Skeleton vor dem Inhalt.',
        css: `
.v05 .b{border:1px solid var(--g300);border-radius:var(--panel);overflow:hidden;background:#fff;box-shadow:0 24px 50px -30px rgba(17,24,39,.4)}
.v05 .tr{display:flex;align-items:flex-end;gap:.25rem;padding:.6rem .75rem 0;background:var(--g200)}
.v05 .tr .hv-dots{align-self:center;margin:0 .75rem .6rem .25rem}
.v05 .tb{position:relative;display:flex;align-items:center;gap:.5rem;padding:.5rem .9rem;border-radius:.6rem .6rem 0 0;font-size:.8125rem;color:var(--g600);min-width:0;transition:background .25s,color .25s}
.v05 .tb span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.v05 .tb::before{content:'';flex:none;width:.7rem;height:.7rem;border-radius:3px;background:var(--g400);transition:background .25s}
.v05 .tb:hover{background:var(--g100)}
.v05 .tb[aria-selected=true]{background:#fff;color:var(--g900)}
.v05 .tb[aria-selected=true]::before{background:var(--brand)}
.v05 .tool{display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;border-bottom:1px solid var(--g200)}
.v05 .tool>button{width:1.9rem;height:1.9rem;border-radius:50%;display:grid;place-items:center;color:var(--g600);font-size:1rem}
.v05 .tool>button:hover{background:var(--g100)}
.v05 .tool>button.spin{animation:v05r .6s ease}
@keyframes v05r{to{transform:rotate(360deg)}}
.v05 .url{flex:1;min-width:0;display:flex;align-items:center;gap:.5rem;padding:.35rem .9rem;border-radius:2rem;background:var(--g100);font-size:.8125rem;color:var(--g500);white-space:nowrap;overflow:hidden}
.v05 .url b{font-weight:400;color:var(--g900)}
.v05 .load{height:2px;background:transparent}
.v05 .load i{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--brand),#c026d3)}
.v05 .load.go i{animation:v05l .75s cubic-bezier(.2,.7,.2,1) forwards}
@keyframes v05l{0%{width:0;opacity:1}80%{width:100%;opacity:1}100%{width:100%;opacity:0}}
.v05 .page{position:relative;padding:2rem 2.25rem 2.25rem;min-height:21rem}
.v05 .page h3{margin:0 0 .15rem;font-size:1.5rem;line-height:1.3;font-weight:600;color:var(--g900)}
.v05 .page .tz{color:var(--g500);margin-bottom:1rem}
.v05 .sk{position:absolute;inset:2rem 2.25rem;display:grid;gap:.9rem;align-content:start;opacity:0;pointer-events:none;transition:opacity .2s}
.v05 .sk i{height:.8rem;border-radius:1rem;background:linear-gradient(90deg,var(--g100),var(--g200),var(--g100));background-size:200% 100%;animation:v04s 1.2s linear infinite}
@keyframes v04s{to{background-position:-200% 0}}
.v05 .sk i:first-child{height:1.6rem;width:14rem;margin-bottom:.5rem}
.v05 .sk i:nth-child(4){width:70%}
.v05 .sk i:nth-child(6){width:40%}
.v05 .page.busy .sk{opacity:1}
.v05 .page.busy .hv-stack{opacity:0}
.v05 .hv-stack{transition:opacity .2s}
@media (max-width:640px){.v05 .page{padding:1.25rem}.v05 .sk{inset:1.25rem}.v05 .tb{padding:.5rem .6rem}}`,
        mount(root, ctx) {
            root.innerHTML = `<div class="b"><div class="tr" role="tablist">${HV.dots}${S.map((s, i) => `<button type="button" class="tb" role="tab" data-go="${i}"><span>${s.title}</span></button>`).join('')}</div><div class="tool"><button type="button" data-step="-1" aria-label="${D.prev}">←</button><button type="button" data-step="1" aria-label="${D.nextSection}">→</button><button type="button" class="re" aria-label="Reload">↻</button><div class="url"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg><span>codebar.ch/<b></b></span></div></div><div class="load"><i></i></div><div class="page"><div class="sk" aria-hidden="true">${'<i></i>'.repeat(6)}</div><div class="hv-stack">${S.map((s, i) => `<div class="hv-pan" data-p="${i}"><h3>${s.title}</h3><p class="tz">${s.teaser}</p>${HV.text(i)}${HV.actions(i)}</div>`).join('')}</div></div></div>`
            const page = root.querySelector('.page')
            const load = root.querySelector('.load')
            const path = root.querySelector('.url b')
            const re = root.querySelector('.re')
            let gen = 0
            const open = async (i) => {
                const g = ++gen
                HV.sel(root, i)
                load.classList.remove('go')
                void load.offsetWidth
                load.classList.add('go')
                page.classList.add('busy')
                HV.show(root, i, false)
                ctx.type(path, S[i].cmd, 22, () => g === gen)
                await ctx.wait(ctx.reduced ? 0 : 480)
                if (g !== gen) return
                page.classList.remove('busy')
                HV.show(root, i)
            }
            const nav = ctx.nav({ change: open })
            ctx.on(re, 'click', () => {
                re.classList.remove('spin')
                void re.offsetWidth
                re.classList.add('spin')
                open(nav.i)
            })
            nav.go(0)
        },
    })
})()
