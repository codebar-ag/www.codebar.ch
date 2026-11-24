import {spawn} from 'node:child_process';
import {mkdtempSync, rmSync, writeFileSync} from 'node:fs';
import {tmpdir} from 'node:os';
import {join} from 'node:path';

const [url, out, wArg, hArg] = process.argv.slice(2);
if (!url || !out) {
  console.error('usage: node site-to-svg.mjs <url> <out.svg> [width=1440] [height=900]');
  process.exit(1);
}
const W = +(wArg || 1440), H = +(hArg || 900);
const CHROME = process.env.CHROME || '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const port = 9300 + Math.floor(Math.random() * 500);
const profile = mkdtempSync(join(tmpdir(), 'site-to-svg-'));
const chrome = spawn(CHROME, ['--headless=new', '--disable-gpu', '--hide-scrollbars', '--no-first-run', `--remote-debugging-port=${port}`, `--user-data-dir=${profile}`, `--window-size=${W},${H}`, 'about:blank'], {stdio: 'ignore'});
const sleep = ms => new Promise(r => setTimeout(r, ms));

async function target() {
  for (let i = 0; i < 50; i++) {
    try {
      const list = await (await fetch(`http://127.0.0.1:${port}/json`)).json();
      const page = list.find(t => t.type === 'page');
      if (page) return page.webSocketDebuggerUrl;
    } catch (_) {}
    await sleep(200);
  }
  throw new Error('Chrome did not start');
}

function client(wsUrl) {
  const ws = new WebSocket(wsUrl);
  let id = 0;
  const pending = new Map(), waiters = [];
  ws.onmessage = m => {
    const d = JSON.parse(m.data);
    if (d.id && pending.has(d.id)) {
      const {res, rej} = pending.get(d.id);
      pending.delete(d.id);
      d.error ? rej(new Error(d.error.message)) : res(d.result);
    } else if (d.method) {
      waiters.filter(w => w.method === d.method).forEach(w => { waiters.splice(waiters.indexOf(w), 1); w.res(d.params); });
    }
  };
  const send = (method, params = {}) => new Promise((res, rej) => { pending.set(++id, {res, rej}); ws.send(JSON.stringify({id, method, params})); });
  const once = (method, ms = 20000) => Promise.race([new Promise(res => waiters.push({method, res})), sleep(ms)]);
  return new Promise(res => { ws.onopen = () => res({send, once, close: () => ws.close()}); });
}

const collect = `(() => {
  const W = innerWidth, H = innerHeight, out = [];
  const rgba = c => { const m = c.match(/[\\d.]+/g); return m ? {r:+m[0], g:+m[1], b:+m[2], a: m[3] === undefined ? 1 : +m[3]} : null; };
  const vis = r => r.width > 3 && r.height > 3 && r.right > 0 && r.bottom > 0 && r.left < W && r.top < H;
  const box = r => ({x: Math.max(0, r.left), y: Math.max(0, r.top), w: Math.min(W, r.right) - Math.max(0, r.left), h: Math.min(H, r.bottom) - Math.max(0, r.top)});
  const body = getComputedStyle(document.body), html = getComputedStyle(document.documentElement);
  const bg = [body.backgroundColor, html.backgroundColor].map(rgba).find(c => c && c.a > 0) || {r:255, g:255, b:255, a:1};
  let logo = null;
  const walk = el => {
    const cs = getComputedStyle(el);
    if (cs.display === 'none' || cs.visibility === 'hidden' || +cs.opacity < .05) return;
    const r = el.getBoundingClientRect();
    if (r.width === 0 && r.height === 0 && !el.children.length) return;
    const tag = el.tagName.toLowerCase(), b = box(r), rad = Math.min(parseFloat(cs.borderTopLeftRadius) || 0, b.h / 2, b.w / 2);
    const media = tag === 'img' || tag === 'video' || tag === 'picture' || tag === 'canvas' || tag === 'iframe' || (tag === 'svg' && r.width > 20);
    if (media) {
      if (vis(r)) {
        if (!logo && r.top < 160 && r.width < 420 && r.height < 140 && r.width > 24) { logo = b; el.setAttribute('data-sk-logo', ''); out.push({k: 'logo', ...b}); }
        else out.push({k: 'media', ...b, rad});
      }
      return;
    }
    const c = rgba(cs.backgroundColor), hasImg = cs.backgroundImage && cs.backgroundImage !== 'none';
    if (vis(r) && r.width > 6 && r.height > 6) {
      if (hasImg && /url\\(/.test(cs.backgroundImage) && r.width > 60 && r.height > 60) out.push({k: 'media', ...b, rad});
      else if (hasImg || (c && c.a > .05)) out.push({k: 'fill', ...b, rad, sample: hasImg, c: c && c.a > .05 ? cs.backgroundColor : null});
      const bw = parseFloat(cs.borderTopWidth) || 0, bc = rgba(cs.borderTopColor);
      if (bw >= 1 && bc && bc.a > .1 && cs.borderTopStyle !== 'none' && r.width > 30 && r.height > 20) out.push({k: 'stroke', ...b, rad, c: cs.borderTopColor, sw: bw});
    }
    for (const n of el.childNodes) {
      if (n.nodeType === 1) walk(n);
      else if (n.nodeType === 3 && n.textContent.trim().length > 1 && !(r.top < 14 && r.left < 6 && cs.position !== 'static')) {
        const range = document.createRange(); range.selectNodeContents(n);
        const fs = parseFloat(cs.fontSize) || 16, rects = [...range.getClientRects()].filter(vis);
        const r0 = rects[0], hit = r0 && document.elementFromPoint(Math.min(W - 1, Math.max(0, r0.left + Math.min(r0.width / 2, 12))), Math.min(H - 1, Math.max(0, r0.top + r0.height / 2)));
        if (!hit || !(hit === el || el.contains(hit) || hit.contains(el))) continue;
        if (fs >= 26) {
          const lines = [], re = /\\S+/g; let m;
          while ((m = re.exec(n.textContent))) { const wr = document.createRange(); wr.setStart(n, m.index); wr.setEnd(n, m.index + m[0].length); const q = wr.getClientRects()[0]; if (!q) continue;
            const ln = lines.find(l => Math.abs(l.top - q.top) < fs * .5); if (ln) { ln.words.push(m[0]); ln.right = Math.max(ln.right, q.right); ln.left = Math.min(ln.left, q.left); } else lines.push({top: q.top, bottom: q.bottom, left: q.left, right: q.right, words: [m[0]]}); }
          lines.filter(vis0 => vis0.right > 0 && vis0.left < W && vis0.bottom > 0 && vis0.top < H).forEach(l => out.push({k: 'head', x: l.left, y: l.top, w: l.right - l.left, h: l.bottom - l.top, fs, c: cs.color, fw: cs.fontWeight, t: (cs.textTransform === 'uppercase' ? l.words.join(' ').toUpperCase() : l.words.join(' '))}));
        } else rects.forEach(q => { const t = box(q), th = Math.max(4, Math.min(fs * .62, t.h)); if (t.w > 4) out.push({k: 'text', x: t.x, y: t.y + (t.h - th) / 2, w: t.w, h: th, c: cs.color, big: false}); });
      }
    }
  };
  walk(document.body);
  let cta = null;
  const solid = el => { for (let n = el; n && n.nodeType === 1; n = n.parentElement) { const c = rgba(getComputedStyle(n).backgroundColor); if (c && c.a > .5) return 'rgb(' + Math.round(c.r) + ',' + Math.round(c.g) + ',' + Math.round(c.b) + ')'; } return 'rgb(255,255,255)'; };
  for (const el of document.querySelectorAll('a,button')) {
    const r = el.getBoundingClientRect(), cs = getComputedStyle(el), c = rgba(cs.backgroundColor), bw = parseFloat(cs.borderTopWidth) || 0;
    if (r.top < 150 || r.bottom > H - 60 || r.left < 0 || r.right > W || r.width < 90 || r.width > 460 || r.height < 30 || r.height > 84) continue;
    if (cs.visibility === 'hidden' || cs.display === 'none' || !((c && c.a > .5) || bw >= 1)) continue;
    if (!cta || r.top < cta.y - 4) cta = {x: r.left, y: r.top, w: r.width, h: r.height, bg: solid(el), color: cs.color, border: bw >= 1 ? bw + 'px solid ' + cs.borderTopColor : '0', radius: Math.min(parseFloat(cs.borderTopLeftRadius) || 0, r.height / 2), fs: parseFloat(cs.fontSize) || 16};
  }
  if (!cta) { const h = out.filter(i => i.k === 'head' && i.y > 120 && i.y < H * .6).sort((m, n) => n.fs - m.fs)[0];
    if (h) { const blk = out.filter(i => i.k === 'head' && i.fs === h.fs && Math.abs(i.x - h.x) < 4), bottom = Math.max(...blk.map(i => i.y + i.h)), c = rgba(h.c), dark = c.r * .3 + c.g * .59 + c.b * .11 < 140;
      cta = {x: h.x, y: Math.min(H - 110, bottom + 34), w: 168, h: 50, bg: h.c, color: dark ? 'rgb(255,255,255)' : 'rgb(16,24,40)', border: '0', radius: 10, fs: 17, own: true}; } }
  return JSON.stringify({W, H, cta, bg: 'rgb(' + bg.r + ',' + bg.g + ',' + bg.b + ')', items: out.slice(0, 1400)});
})()`;

const sample = (png, jpg, data) => `(async () => {
  const d = ${data}, img = new Image(), hi = 'data:image/png;base64,${png}';
  img.src = 'data:image/jpeg;base64,${jpg}';
  await img.decode();
  const cv = document.createElement('canvas'); cv.width = d.W; cv.height = d.H;
  const cx = cv.getContext('2d', {willReadFrequently: true}); cx.drawImage(img, 0, 0, d.W, d.H);
  const avg = (x, y, w, h) => { x = Math.max(0, Math.round(x)); y = Math.max(0, Math.round(y)); w = Math.max(1, Math.min(d.W - x, Math.round(w))); h = Math.max(1, Math.min(d.H - y, Math.round(h)));
    const p = cx.getImageData(x, y, w, h).data, st = Math.max(4, Math.floor(p.length / 4 / 400) * 4); let r = 0, g = 0, b = 0, n = 0;
    for (let i = 0; i < p.length; i += st) { r += p[i]; g += p[i + 1]; b += p[i + 2]; n++; }
    return 'rgb(' + Math.round(r / n) + ',' + Math.round(g / n) + ',' + Math.round(b / n) + ')'; };
  const f = n => (Math.round(n * 10) / 10), esc = s => s.replace(/"/g, '');
  const flat = (x, y, w, h) => { x = Math.max(0, Math.round(x)); y = Math.max(0, Math.round(y)); w = Math.max(1, Math.min(d.W - x, Math.round(w))); h = Math.max(1, Math.min(d.H - y, Math.round(h)));
    const p = cx.getImageData(x, y, w, h).data, st = Math.max(4, Math.floor(p.length / 4 / 600) * 4), R = [], G = [], B = [];
    for (let i = 0; i < p.length; i += st) { R.push(p[i]); G.push(p[i + 1]); B.push(p[i + 2]); }
    const med = a => a.slice().sort((m, n) => m - n)[a.length >> 1], m = [med(R), med(G), med(B)]; let off = 0;
    for (let i = 0; i < R.length; i++) if (Math.abs(R[i] - m[0]) + Math.abs(G[i] - m[1]) + Math.abs(B[i] - m[2]) > 70) off++;
    return {c: 'rgb(' + m.join(',') + ')', busy: off / R.length}; };
  let defs = '', body = '', top = '', g = 0;
  const picture = (it, a, min) => { const id = 'g' + (g++), x = Math.max(0, Math.round(it.x)), y = Math.max(0, Math.round(it.y)), w = Math.max(1, Math.min(d.W - x, Math.round(it.w))), h = Math.max(1, Math.min(d.H - y, Math.round(it.h)));
    const cell = Math.max(min || 4, Math.round(Math.max(w, h) / 120)), iw = Math.ceil(w / cell), ih = Math.ceil(h / cell), gw = iw + 2, gh = ih + 2, n = gw * gh, K = 7;
    const t = document.createElement('canvas'); t.width = iw; t.height = ih; const tc = t.getContext('2d'); tc.imageSmoothingQuality = 'high'; tc.filter = 'blur(1.1px)'; tc.drawImage(cv, x, y, w, h, 0, 0, iw, ih); tc.filter = 'none'; tc.globalCompositeOperation = 'destination-over'; tc.drawImage(cv, x, y, w, h, 0, 0, iw, ih);
    const src = tc.getImageData(0, 0, iw, ih).data, px = new Float32Array(n * 3);
    for (let r = 0; r < gh; r++) for (let c = 0; c < gw; c++) { const o = (Math.min(ih - 1, Math.max(0, r - 1)) * iw + Math.min(iw - 1, Math.max(0, c - 1))) * 4, q = (r * gw + c) * 3; px[q] = src[o]; px[q + 1] = src[o + 1]; px[q + 2] = src[o + 2]; }
    const order = Array.from({length: n}, (_, k) => k).sort((m, o) => (px[m * 3] * .3 + px[m * 3 + 1] * .59 + px[m * 3 + 2] * .11) - (px[o * 3] * .3 + px[o * 3 + 1] * .59 + px[o * 3 + 2] * .11));
    const cen = []; for (let k = 0; k < K; k++) { const q = order[Math.floor((k + .5) * n / K)] * 3; cen.push([px[q], px[q + 1], px[q + 2]]); }
    let idx = new Uint8Array(n);
    for (let pass = 0; pass < 10; pass++) { const sum = cen.map(() => [0, 0, 0, 0]);
      for (let k = 0; k < n; k++) { let best = 0, bd = 1e9; for (let m = 0; m < K; m++) { const dr = px[k * 3] - cen[m][0], dg = px[k * 3 + 1] - cen[m][1], db = px[k * 3 + 2] - cen[m][2], dd = dr * dr + dg * dg + db * db; if (dd < bd) { bd = dd; best = m; } }
        idx[k] = best; const u = sum[best]; u[0] += px[k * 3]; u[1] += px[k * 3 + 1]; u[2] += px[k * 3 + 2]; u[3]++; }
      sum.forEach((u, m) => { if (u[3]) cen[m] = [u[0] / u[3], u[1] / u[3], u[2] / u[3]]; }); }
    for (let pass = 0; pass < 5; pass++) { const nx = new Uint8Array(idx);
      for (let r = 1; r < gh - 1; r++) for (let c = 1; c < gw - 1; c++) { const cnt = new Uint8Array(K); let best = idx[r * gw + c], bc = 0;
        for (let dy = -1; dy < 2; dy++) for (let dx = -1; dx < 2; dx++) { const v = idx[(r + dy) * gw + c + dx]; if (++cnt[v] > bc) { bc = cnt[v]; best = v; } }
        if (bc >= 4) nx[r * gw + c] = best; }
      idx = nx; }
    const count = new Array(K).fill(0); idx.forEach(v => count[v]++);
    const rank = count.map((v, m) => m).sort((m, o) => count[o] - count[m]), col = m => 'rgb(' + cen[m].map(v => Math.round(v)).join(',') + ')';
    const X = v => Math.round(x + (v - 1) * cell), Y = v => Math.round(y + (v - 1) * cell), at = (c, r) => c >= 0 && r >= 0 && c < gw && r < gh ? idx[r * gw + c] : -1;
    let out = '<rect ' + a + ' fill="' + col(rank[0]) + '"/>';
    for (const m of rank.slice(1)) { if (!count[m]) continue; const next = new Map(), add = (x1, y1, x2, y2) => { const key = x1 + y1 * (gw + 1); (next.get(key) || next.set(key, []).get(key)).push([x2, y2]); };
      for (let r = 0; r < gh; r++) for (let c = 0; c < gw; c++) if (idx[r * gw + c] === m) { if (at(c, r - 1) !== m) add(c, r, c + 1, r); if (at(c + 1, r) !== m) add(c + 1, r, c + 1, r + 1); if (at(c, r + 1) !== m) add(c + 1, r + 1, c, r + 1); if (at(c - 1, r) !== m) add(c, r + 1, c, r); }
      let dd = '';
      for (const [key, list] of next) while (list.length) { let cx0 = key % (gw + 1), cy0 = Math.floor(key / (gw + 1)), px0 = cx0, py0 = cy0; const mid = [];
        for (let guard = 0; guard < 200000; guard++) { const l = next.get(px0 + py0 * (gw + 1)); if (!l || !l.length) break; const e = l.pop(); mid.push([(px0 + e[0]) / 2, (py0 + e[1]) / 2]); px0 = e[0]; py0 = e[1]; if (px0 === cx0 && py0 === cy0) break; }
        const pts = mid.filter((q, k) => { const o = mid[(k + mid.length - 1) % mid.length], u = mid[(k + 1) % mid.length]; return Math.abs((q[0] - o[0]) * (u[1] - q[1]) - (q[1] - o[1]) * (u[0] - q[0])) > 1e-6; });
        if (pts.length < 4) continue;
        const mm = k => [(pts[k][0] + pts[(k + 1) % pts.length][0]) / 2, (pts[k][1] + pts[(k + 1) % pts.length][1]) / 2], s0 = mm(pts.length - 1);
        dd += 'M' + X(s0[0]) + ' ' + Y(s0[1]); for (let k = 0; k < pts.length; k++) { const e = mm(k); dd += 'Q' + X(pts[k][0]) + ' ' + Y(pts[k][1]) + ' ' + X(e[0]) + ' ' + Y(e[1]); } dd += 'Z'; }
      if (dd) out += '<path d="' + dd + '" fill="' + col(m) + '" stroke="' + col(m) + '" stroke-width="' + f(cell * .8) + '" stroke-linejoin="round" fill-rule="evenodd"/>'; }
    defs += '<clipPath id="' + id + '"><rect ' + a + ' rx="' + f(it.rad) + '"/></clipPath>';
    return '<g clip-path="url(#' + id + ')">' + out + '</g>'; };
  for (const it of d.items) {
    const a = 'x="' + f(it.x) + '" y="' + f(it.y) + '" width="' + f(it.w) + '" height="' + f(it.h) + '"', large = it.w >= 90 && it.h >= 60;
    if (it.k === 'fill') { const s = flat(it.x, it.y, it.w, it.h); body += large && s.busy > .35 ? picture(it, a) : '<rect ' + a + ' rx="' + f(it.rad) + '" fill="' + s.c + '"/>'; }
    else if (it.k === 'media') body += large ? picture(it, a) : '<rect ' + a + ' rx="' + f(it.rad) + '" fill="' + avg(it.x, it.y, it.w, it.h) + '"/>';
    else if (it.k === 'stroke') body += '<rect ' + a + ' rx="' + f(it.rad) + '" fill="none" stroke="' + esc(it.c) + '" stroke-width="' + f(it.sw) + '"/>';
    else if (it.k === 'text') top += '<rect ' + a + ' rx="' + f(it.h / 2) + '" fill="' + esc(it.c) + '" opacity=".45"/>';
    else if (it.k === 'head') { const bh = Math.max(6, it.fs * .6); top += '<rect x="' + f(it.x) + '" y="' + f(it.y + (it.h - bh) / 2) + '" width="' + f(it.w) + '" height="' + f(bh) + '" rx="' + f(bh * .3) + '" fill="' + esc(it.c) + '" opacity=".88"/>'; }
    else if (it.k === 'logo') top += picture(it, a, 3);
  }
  body += top;
  return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + d.W + ' ' + d.H + '" preserveAspectRatio="xMidYMin slice"><defs>' + defs + '</defs><rect width="' + d.W + '" height="' + d.H + '" fill="' + flat(0, 0, d.W, d.H).c + '"/>' + body + '</svg>';
})()`;

try {
  const c = await client(await target());
  await c.send('Page.enable');
  await c.send('Emulation.setDeviceMetricsOverride', {width: W, height: H, deviceScaleFactor: /\.jpe?g$/i.test(out) ? 1.5 : 2, mobile: false});
  const loaded = c.once('Page.loadEventFired');
  await c.send('Page.navigate', {url});
  await loaded;
  await sleep(2500);
  if (/\.jpe?g$/i.test(out)) {
    const jpg = (await c.send('Page.captureScreenshot', {format: 'jpeg', quality: 78})).data;
    writeFileSync(out, Buffer.from(jpg, 'base64'));
    console.log(`${out}: ${(jpg.length * .75 / 1024).toFixed(0)} KB`);
    c.close();
    chrome.kill();
    await sleep(300);
    rmSync(profile, {recursive: true, force: true});
    process.exit(0);
  }
  const data = (await c.send('Runtime.evaluate', {expression: collect, returnByValue: true})).result.value;
  const png = (await c.send('Page.captureScreenshot', {format: 'png'})).data;
  if (process.env.DEBUG_PNG) writeFileSync(process.env.DEBUG_PNG, Buffer.from(png, 'base64'));
  await c.send('Runtime.evaluate', {expression: "(()=>{const s=document.createElement('style');s.textContent='*{color:transparent!important;text-shadow:none!important;-webkit-text-fill-color:transparent!important}';document.head.appendChild(s)})()"});
  await sleep(300);
  const jpg = (await c.send('Page.captureScreenshot', {format: 'jpeg', quality: 85})).data;
  const blank = c.once('Page.loadEventFired');
  await c.send('Page.navigate', {url: 'about:blank'});
  await blank;
  const svg = (await c.send('Runtime.evaluate', {expression: sample(png, jpg, data), awaitPromise: true, returnByValue: true})).result.value;
  if (!svg) throw new Error('no SVG produced');
  writeFileSync(out, svg);
  writeFileSync(out.replace(/\.svg$/, '.cta.json'), JSON.stringify(JSON.parse(data).cta));
  console.log(`${out}: ${(svg.length / 1024).toFixed(1)} KB, ${JSON.parse(data).items.length} shapes`);
  c.close();
} finally {
  chrome.kill();
  await sleep(300);
  rmSync(profile, {recursive: true, force: true});
}
