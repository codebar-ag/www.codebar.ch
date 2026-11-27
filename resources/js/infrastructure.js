import '../css/infrastructure.css';
import { roundedPath } from './infrastructure/geometry.js';
import { clamp, lerp, matrix, nearestSnap, opennessTarget, smooth, springStep } from './infrastructure/motion.js';
import { esc, stationSheet } from './infrastructure/sheet.js';
import { initFilter } from './infrastructure/filter.js';

initFilter();

const source=document.getElementById('infraData');

if(source){
const D=JSON.parse(source.textContent),T=D.labels,root=document.documentElement;
const S={};D.layers.forEach(l=>l.stations.forEach(s=>{S[s.id]={...s,color:l.color}}));
const FLOW={constant:[33.33,2.3,4.5],frequent:[50,4.6,4.5],occasional:[100,12,4.5],scheduled:[50,9,4.5]};
const EDGES=D.edges.map(e=>({from:e[0],to:e[1],flow:FLOW[e[2]]||FLOW.constant}));
const pace=(p,f,shift)=>{p.style.strokeDasharray=f[2]+' '+(f[0]-f[2]).toFixed(2);p.style.setProperty('--o',-f[0]);p.style.animationDuration=f[1]+'s';p.style.animationDelay=(-(shift%1)*f[1]).toFixed(2)+'s'};
const REDUCE=matchMedia('(prefers-reduced-motion: reduce)').matches;
const FLAGS={};document.querySelectorAll('#infraFlags [data-flag]').forEach(el=>{FLAGS[el.dataset.flag]=el.innerHTML});
const flagsOf=s=>s.flags.map(c=>FLAGS[c]||'').join('');
const LOCK='<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>';
const siteMock=()=>`<div class="sx-browser"><div class="sx-browser-bar"><i></i><i></i><i></i><span>${S.tls?`<button type="button" class="sx-https" data-station="tls" aria-label="${esc(S.tls.name)}">${LOCK}https://</button>`:`<b class="sx-https">${LOCK}https://</b>`}${esc(D.site.domain)}</span></div><div class="sx-browser-body">${D.site.image?`<img src="${esc(D.site.image)}" alt="${esc(D.site.alt)}" draggable="false">`:`<p>${esc(D.site.domain)}</p>`}</div></div>`;
const dialog=document.getElementById('infraDialog'),overlay=document.getElementById('infraOverlay'),SC=document.getElementById('infraScroller');
function openStation(id){
  const s=S[id];if(!s||!dialog)return;
  dialog.innerHTML=stationSheet(s,{labels:T,flags:FLAGS,url:D.urls&&D.urls[id]});
  if(!dialog.open)dialog.showModal();
}
if(dialog){
  document.addEventListener('click',ev=>{const b=ev.target.closest('[data-station]');if(b&&!dialog.contains(b))openStation(b.dataset.station)});
  dialog.addEventListener('click',ev=>{if(ev.target===dialog||ev.target.closest('[data-close]'))dialog.close()});
}
const $=s=>document.querySelector(s);
const LAY=D.layers.map((l,k)=>({k,name:l.name,color:l.color,ids:l.stations.map(x=>x.id)}));
const NL=LAY.length,layerOf={};
LAY.forEach(l=>l.ids.forEach(id=>layerOf[id]=l.k));
const EDG=EDGES.filter(e=>layerOf[e.from]!==undefined&&layerOf[e.to]!==undefined),E0=EDGES.find(e=>e.from==='req'&&layerOf[e.to]!==undefined),LIDS=Object.keys(layerOf);
const TREE=LAY.map(l=>{const r=l.ids[0],kids=l.ids.slice(1);return kids.length>1&&kids.every(id=>EDG.some(e=>e.from===r&&e.to===id))?r:null});
const world=$('#sxWorld'),view=$('#sxView'),scene=$('#sxScene'),stage=$('#sxStage');
const NS='http://www.w3.org/2000/svg',mk=(t,c,h)=>{const el=document.createElement(t);if(c)el.className=c;if(h!=null)el.innerHTML=h;return el};
const plates=[],shadows=[],tabs=[],cards={},feet={},tops={},CH={},P=[],ST=[];
for(let k=NL-1;k>=0;k--){const sh=mk('div','sx-sh'),pl=mk('div','sx-plate');pl.style.setProperty('--c',LAY[k].color);pl.dataset.k=k;world.append(sh,pl);plates[k]=pl;shadows[k]=sh;P[k]={o:0,t:0,fired:false,bs:''}}
const site=mk('div','sx-plate sx-site',siteMock());site.style.setProperty('--c','#C9D3DD');world.append(site);
const cta=null;
const svgL=LAY.map(l=>{const v=document.createElementNS(NS,'svg');v.style.setProperty('--c',l.color);v.setAttribute('class','sx-lines');v.setAttribute('width','1');v.setAttribute('height','1');v.setAttribute('aria-hidden','true');world.append(v);return v});
const svgG=LAY.map(()=>{const v=document.createElementNS(NS,'svg');v.setAttribute('class','sx-lines');v.setAttribute('width','1');v.setAttribute('height','1');v.setAttribute('aria-hidden','true');world.append(v);return v});
const mkPath=(k,col,i)=>{const e=document.createElementNS(NS,'path');e.setAttribute('class','sx-ln');e.setAttribute('stroke',col);if(i!=null)e.dataset.i=i;const p=document.createElementNS(NS,'path');p.setAttribute('class','sx-pk');p.setAttribute('stroke',col);p.setAttribute('pathLength','100');svgL[k].append(e,p);e.pk=p;return e};
const ln0=E0?mkPath(layerOf[E0.to],LAY[layerOf[E0.to]].color):mkPath(0,LAY[0].color),lnEls=EDG.map((e,i)=>mkPath(Math.max(layerOf[e.from],layerOf[e.to]),LAY[Math.max(layerOf[e.from],layerOf[e.to])].color,i));
const ghEls=EDG.map(e=>{const ka=layerOf[e.from],kb=layerOf[e.to];const out=[];for(let k=Math.min(ka,kb);k<Math.max(ka,kb);k++){const p=document.createElementNS(NS,'path');p.setAttribute('class','sx-gh');p.style.setProperty('--c',LAY[Math.max(ka,kb)].color);const q=document.createElementNS(NS,'path');q.setAttribute('class','sx-pk');q.setAttribute('pathLength','100');q.style.setProperty('--c',LAY[Math.max(ka,kb)].color);svgG[k].append(p,q);p.pk=q;out.push(p)}return out});
{const dep={};if(E0)dep[E0.to]=1;for(let n=0;n<8;n++)EDG.forEach(e=>{if(dep[e.from]!=null&&(dep[e.to]==null||dep[e.to]>dep[e.from]+1))dep[e.to]=dep[e.from]+1});const D=id=>dep[id]!=null?dep[id]:Math.max(1,...Object.values(dep))+1;pace(ln0.pk,(E0||{flow:FLOW.constant}).flow,0);EDG.forEach((e,i)=>{const shift=(D(e.from)*.9+i*.37)/4.2;pace(lnEls[i].pk,e.flow,shift);ghEls[i].forEach(p=>pace(p.pk,e.flow,shift))})}
LIDS.forEach(id=>{const s=S[id],b=mk('button','sx-card',`<i class="sx-bs"></i><i class="sx-bf"><b>${esc(s.name)}</b><small>${esc(s.provider)}</small></i><i class="sx-br"><span class="sx-f">${flagsOf(s)}</span></i>`);b.type='button';b.dataset.station=id;b.style.setProperty('--c',s.color);world.append(b);cards[id]=b});
LAY.forEach((l,k)=>{const b=mk('button','sx-tab',`<b>${esc(l.name)}</b>`);b.type='button';b.dataset.k=k;b.style.setProperty('--c',l.color);plates[k].append(b);tabs[k]=b});
const time=mk('nav','sx-time',`<button type="button" class="sx-time-r" data-t="-1" aria-label="${esc(T.back)}"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg></button><div class="sx-time-t">`+LAY.map(l=>`<button type="button" data-t="${l.k}" style="--c:${l.color}" aria-label="${esc(l.name)}" title="${esc(l.name)}"><span></span></button>`).join('')+`</div>`);time.setAttribute('aria-label',T.progress);stage.append(time);
const timeB=[...time.querySelectorAll('.sx-time-t [data-t]')];

let wide=true,geo={},sv=0,svS=0,dirty=true,hot='',snap=false,STEPS=NL;

function fitNames(){LIDS.forEach(id=>cards[id].querySelectorAll('b,small').forEach(el=>{el.style.fontSize='';let f=parseFloat(getComputedStyle(el).fontSize),n=0;while(el.scrollWidth>el.clientWidth+.5&&f>6.5&&n++<24){f-=.5;el.style.fontSize=f+'px'}}))}
function ang(){const g=geo,z=smooth(0,.6,svS),th=lerp(.07,g.th0,z),ph=lerp(.42,g.ph0,z);Object.assign(g,matrix(th,ph));g.key=th.toFixed(3)+ph.toFixed(3)}
function setup(){
  wide=innerWidth>=980&&overlay.open;root.dataset.sx=wide?'iso':'mob';if(!wide){dirty=false;return}
  const g=geo={},th=(wide?12:8)*Math.PI/180,ph=(wide?62:52)*Math.PI/180;
  g.th0=th;g.ph0=ph;ang();
  g.sw=scene.clientWidth;g.shh=scene.clientHeight;
  g.per=wide?5:3;g.cols=wide?6:3;g.d0=Math.cos(th)*Math.cos(ph);g.w=wide?960:Math.min(520,(g.sw-26)/(g.a+g.c*.66));g.H0=Math.round(g.w*(wide?.66:.94));
  g.colS=g.w/g.cols;g.cw=wide?134:Math.min(118,Math.floor(g.colS-9));g.fw=wide?118:Math.min(104,Math.floor(g.colS-12));g.fd=wide?70:44;g.bh=wide?46:36;g.RS=(wide?112:78)/g.d0;g.base=wide?140:112;
  g.card=wide?84:62;g.tabW=0;g.maxS=wide?1.45:1;g.Hmin=Math.round(g.H0*.38);
  world.style.setProperty('--cw',g.fw+'px');
  LAY.forEach(l=>{const rs=[],st=TREE[l.k]?1:0,n=l.ids.length-st,size=Math.ceil(n/Math.ceil(n/g.per));for(let i=st;i<l.ids.length;i+=size)rs.push(l.ids.slice(i,i+size));l.pos={};rs.forEach((row,r)=>row.forEach((id,c)=>l.pos[id]=[r,c+(g.cols-row.length)/2]));if(st)l.pos[l.ids[0]]=[0,(g.cols-1)/2];l.rows=rs.length;l.band=g.base+(l.rows-1)*g.RS;l.h=g.H0});g.mb=Math.max(...LAY.map(l=>l.band));g.tall=TREE.some(Boolean)?g.bh:0;g.HL=Math.round(Math.min(g.H0,Math.max(g.H0*.62,g.mb+70)));
  STEPS=NL+1;
  {const X=id=>LAY[layerOf[id]].pos[id][1],near=e=>{const p=LAY[layerOf[e.from]].pos;return p[e.from][0]===p[e.to][0]&&Math.abs(p[e.from][1]-p[e.to][1])<1.01},by=(arr,f)=>arr.slice().sort((a,b)=>f(a)-f(b));g.eL=[];g.eS=[];g.eT=[];
    LAY.forEach((l,k)=>{by(EDG.map((e,i)=>i).filter(i=>layerOf[EDG[i].to]===k&&!(layerOf[EDG[i].from]===k&&(TREE[k]||near(EDG[i])))),i=>X(EDG[i].from)*10+X(EDG[i].to)).forEach((i,n)=>g.eL[i]=n+(E0&&layerOf[E0.to]===k?1:0))});
    LIDS.forEach(id=>{const o=by(EDG.map((e,i)=>i).filter(i=>EDG[i].from===id),i=>layerOf[EDG[i].to]*10+X(EDG[i].to));o.forEach((i,n)=>g.eS[i]=(n-(o.length-1)/2)*Math.min(9,(g.fw-24)/Math.max(1,o.length-1)));const t=by(EDG.map((e,i)=>i).filter(i=>EDG[i].to===id),i=>X(EDG[i].from)),m=t.length+(E0&&E0.to===id?1:0);t.forEach((i,n)=>g.eT[i]=(n+(E0&&E0.to===id?1:0)-(m-1)/2)*Math.min(9,(g.fw-24)/Math.max(1,m-1)))})}
  $('#sxScroll').style.height=`calc(100vh + ${STEPS*(wide?64:60)}vh)`;
  site.style.width=g.w+'px';site.style.height=g.H0+'px';site.style.setProperty('--sw',g.w+'px');
  fitNames();LIDS.forEach(id=>CH[id]=cards[id].offsetHeight);P.forEach(p=>p.bs='');g.bsS='';
  if(cta){let x=cta.offsetWidth/2,y=cta.offsetHeight,n=cta;while(n&&n!==site){x+=n.offsetLeft;y+=n.offsetTop;n=n.offsetParent}g.req=[x,Math.min(1,y/g.H0)]}else g.req=[g.w/2,.9];
  dirty=true}
const vh=h=>(-geo.b*geo.w+geo.d*h)/2;
const local=(k,i)=>{const g=geo,l=LAY[k],[r,c]=l.pos[l.ids[i]];return[g.colS*(c+.5),(g.HL-66-(l.rows-1-r)*g.RS)/g.HL]};
const pt=(st,x,fy)=>[st.ox+geo.a*x+geo.c*fy*st.hk,st.oy+geo.b*x+geo.d*fy*st.hk];
const edge=(pl,key,holder)=>{const g=geo;if(holder.bs===key)return;holder.bs=key;const det=g.a*g.d-g.b*g.c,vx=-g.c/det,vy=g.a/det,b=[];for(let j=1;j<=7;j++)b.push(`${(vx*j).toFixed(2)}px ${(vy*j).toFixed(2)}px 0 0 var(--sx-edge)`);pl.style.boxShadow=b.join(',')};
function draw(){
  ang();const g=geo,mt=`matrix(${g.a},${g.b},${g.c},${g.d},`,place=(cy,hk)=>({ox:-(g.a*g.w+g.c*hk)/2,oy:cy-(g.b*g.w+g.d*hk)/2,hk,cy});
  const Hs=g.H0,sS=place(0,Hs);let Y=0,pc=0,pcC=0,bottom=vh(Hs);
  for(let k=0;k<NL;k++){const l=LAY[k],o=P[k].o,oc=clamp(o),es=oc*oc*(3-2*oc),stp=g.d*g.mb+g.card+g.tall,cy=pc+lerp(6,stp,es+(o-oc)*.9);pcC+=lerp(6,stp,es);Y+=(g.d*g.mb+g.card+g.tall);ST[k]=place(cy,Hs);ST[k].ox+=g.c*(Hs-g.HL);ST[k].oy+=g.d*(Hs-g.HL);ST[k].hk=g.HL;ST[k].vis=smooth(.12,.6,o);pc=cy;bottom=Math.max(bottom,pcC+vh(Hs))}
  const top=-vh(Hs)-14,half=(g.a*g.w+g.c*g.H0)/2,left=-half-g.tabW,right=half+10,full=Y+vh(Hs)+10;
  const aw=g.sw-28,ah=g.shh-64,W=right-left;
  let Sc=Math.min(g.maxS,aw/W,wide?Math.max(.86,ah/(bottom+16-top)):9),ty;
  {const z=smooth(0,1,svS-NL),fit=Math.min(wide?g.maxS:1,aw/W,ah/(full-top));Sc=lerp(Sc,fit,z);const hh=(bottom+16-top)*Sc,follow=hh<=ah?(ah-hh)/2-top*Sc+32:ah-(bottom+16)*Sc+32;ty=lerp(follow,(ah-(full-top)*Sc)/2-top*Sc+32,z)}
  const tx=(g.sw-W*Sc)/2-left*Sc;
  world.style.transform=`translate(${tx}px,${ty}px) scale(${Sc})`;
  site.style.transform=mt+sS.ox+','+sS.oy+')';edge(site,'s'+g.key,g);if(g.bsS!=='s'){g.bsS='s'}
  LAY.forEach((l,k)=>{
    const s=ST[k],pl=plates[k],sh=shadows[k],vis=s.vis;
    pl.style.width=sh.style.width=g.w+'px';pl.style.height=sh.style.height=s.hk+'px';
    pl.style.transform=mt+s.ox+','+s.oy+')';pl.style.zIndex=900-k*10;svgL[k].style.zIndex=902-k*10;svgG[k].style.zIndex=901-k*10;svgG[k].style.clipPath='polygon('+[[0,0],[g.w,0],[g.w,1],[0,1]].map(q=>{const c=pt(s,q[0],q[1]);return c[0].toFixed(1)+'px '+c[1].toFixed(1)+'px'}).join(',')+')';
    sh.style.transform=mt+(s.ox+10)+','+(s.oy+16)+')';sh.style.opacity=.75*clamp(P[k].o);sh.style.zIndex=897-k*10;
    edge(pl,'p'+g.key,P[k]);
    l.ids.forEach((id,i)=>{const[x,fy]=local(k,i),c=cards[id],root=TREE[k]===id,grow=smooth(.05,1,vis),bh=g.bh*grow,fw=root?g.colS*(l.ids.length-1):TREE[k]?g.colS:g.fw,fd=g.fd,lift=root?bh:0,hh=bh+lift,yb=fy*g.HL,inner=TREE[k]&&!root&&l.pos[id][1]>l.pos[l.ids[1]][1],x0=x-fw/2,p=pt(s,x,fy),A=pt(s,x0,(yb-fd)/g.HL),B=pt(s,x0,fy),R=pt(s,x0+fw,(yb-fd)/g.HL),left=g.b<=0,f=c.children;feet[id]=p;tops[id]=[(A[0]+R[0])/2+g.c*fd/2,(A[1]+R[1])/2+g.d*fd/2-hh];
      c.style.zIndex=900-k*10+(root?6:5);f[0].style.cssText=`${inner?'display:none;':''}width:${fd}px;height:${bh}px;transform:matrix(${g.c},${g.d},0,1,${left?A[0]:R[0]},${(left?A[1]:R[1])-hh})`;f[1].style.cssText=`width:${fw}px;height:${bh}px;transform:matrix(${g.a},${g.b},0,1,${B[0]},${B[1]-hh})`;f[2].style.cssText=`width:${fw}px;height:${fd}px;transform:matrix(${g.a},${g.b},${g.c},${g.d},${A[0]},${A[1]-hh})`;
      c.style.pointerEvents=vis<.5?'none':'';c.style.visibility=vis<.03?'hidden':'';
      });
    tabs[k].style.opacity=vis});
  const H=g.HL,F=n=>n.toFixed(1),path=roundedPath;
  const loc={};LAY.forEach((l,k)=>l.ids.forEach((id,i)=>loc[id]=local(k,i)));
  const bk=(id,o)=>{const k=layerOf[id],a=loc[id];return pt(ST[k],a[0]+o,(a[1]*H-g.fd)/H)},ly=(id,n)=>(loc[id][1]*H-g.fd-g.bh*(TREE[layerOf[id]]?2:1)/g.d-14-n*10)/H,lim=id=>{const k=layerOf[id];return(TREE[k]===id?g.colS*(LAY[k].ids.length-1):TREE[k]?g.colS:g.fw)/2-14},mid=id=>pt(ST[layerOf[id]],loc[id][0],(loc[id][1]*H-g.fd/2)/H),fit=(sx,ls,tx,lt)=>{const lo=Math.max(sx-ls,tx-lt),hi=Math.min(sx+ls,tx+lt);return lo<=hi?[Math.min(hi,Math.max(lo,tx)),Math.min(hi,Math.max(lo,tx))]:sx>tx?[sx-ls,tx+lt]:[sx+ls,tx-lt]};
  if(E0){const k=layerOf[E0.to],t=loc[E0.to],yl=ly(E0.to,0),sh=g.c*(H-12-yl*H)/g.a,[X,Z]=fit(g.w/2+sh,g.w/2-40,t[0],lim(E0.to)),A=pt(sS,X-sh,(g.H0-12)/g.H0);feet.req=A;const dd=path([A,pt(ST[k],X,yl),pt(ST[k],Z,yl),bk(E0.to,Z-t[0])]);ln0.setAttribute('d',dd);ln0.pk.setAttribute('d',dd);ln0.dataset.v=ST[k].vis.toFixed(2)}else ln0.dataset.v=0;
  EDG.forEach((ed,i)=>{const ka=layerOf[ed.from],kb=layerOf[ed.to],a=loc[ed.from],b=loc[ed.to],tree=ka===kb&&TREE[ka]&&(TREE[ka]===ed.from||TREE[ka]===ed.to),v=tree?0:Math.min(ST[ka].vis,ST[kb].vis),n=g.eL[i]||0,os=g.eS[i]||0,ot=g.eT[i]||0;
    const yl=ly(ed.to,n),sh=g.c*(a[1]*H-g.fd-yl*H)/g.a,[X,Z]=fit(a[0]+sh,lim(ed.from),b[0],lim(ed.to)),row=ka===kb&&!tree&&LAY[ka].pos[ed.from][0]===LAY[ka].pos[ed.to][0]&&Math.abs(LAY[ka].pos[ed.from][1]-LAY[ka].pos[ed.to][1])<1.01;
    const pts=row?[mid(ed.from),mid(ed.to)]:ka===kb?[bk(ed.from,os),pt(ST[ka],a[0]+os,ly(ed.from,n)),pt(ST[kb],b[0]+ot,ly(ed.from,n)),pt(ST[kb],b[0]+ot,ly(ed.to,n)),bk(ed.to,ot)]:[bk(ed.from,X-sh-a[0]),pt(ST[kb],X,yl),pt(ST[kb],Z,yl),bk(ed.to,Z-b[0])];
    const dd=path(pts);lnEls[i].setAttribute('d',dd);lnEls[i].pk.setAttribute('d',dd);lnEls[i].dataset.v=v.toFixed(2);ghEls[i].forEach(p=>{p.setAttribute('d',dd);p.pk.setAttribute('d',dd)})});
  applyEdges()}
function applyEdges(){
  const set=(ln,v)=>{ln.style.opacity=v*.55;ln.style.visibility=ln.pk.style.visibility=v<.03?'hidden':'';ln.pk.style.opacity=v};
  EDG.forEach((ed,i)=>{const v=+lnEls[i].dataset.v||0;set(lnEls[i],v);ghEls[i].forEach(p=>{p.style.opacity=v*.45;p.pk.style.opacity=v;p.style.visibility=p.pk.style.visibility=v<.03?'hidden':''})});set(ln0,+ln0.dataset.v||0)}

let last=0;
function frame(){requestAnimationFrame(frame);tick()}
function tick(){
  if(!wide)return;
  const now=performance.now(),dt=Math.min(.6,Math.max(0,(now-last)/1000||0));last=now;let moving=false;
  if(Math.abs(sv-svS)>.0004){svS=REDUCE||snap?sv:svS+(sv-svS)*(1-Math.exp(-dt*7));moving=true}else if(svS!==sv){svS=sv;moving=true}
  P.forEach((p,k)=>{const t=opennessTarget(sv,k);if(REDUCE||snap){p.o=t;p.v=0;return}const n=springStep(p.o,p.v||0,t,dt);p.o=n.o;p.v=n.v;if(n.moving)moving=true});timeB.forEach((b,k)=>{b.firstChild.style.setProperty('--p',clamp(P[k].o).toFixed(3))});
  if(moving||dirty){dirty=false;draw()}}

const track=()=>{const sec=$('#sxScroll'),r=sec.getBoundingClientRect(),top=SC.getBoundingClientRect().top;return{y0:r.top-top+SC.scrollTop,span:Math.max(1,r.height-stage.offsetHeight),top,r}};
function onScroll(){
  if(!wide)return;
  const t=track(),p=clamp((t.top-t.r.top)/t.span);sv=p*STEPS;


  root.classList.toggle('sx-closed',sv<.2);dirty=true;clearTimeout(idle);idle=setTimeout(settle,170)}
let idle=0;
function settle(){if(!wide||!overlay.open||dialog.open||REDUCE)return;const n=nearestSnap(sv,NL);if(Math.abs(n-sv)>.02)go(n)}
const go=(v,instant)=>{const t=track();SC.scrollTo({top:t.y0+clamp(v/STEPS)*t.span,behavior:instant||REDUCE?'auto':'smooth'})};
world.addEventListener('click',ev=>{if(ev.target.closest('.sx-card'))return;const pl=ev.target.closest('.sx-plate[data-k],.sx-tab');if(!pl)return;const k=+pl.dataset.k;if(P[k].o<.6)go(k+.95)});
time.addEventListener('click',ev=>{const b=ev.target.closest('[data-t]');if(!b)return;const k=+b.dataset.t;go(k<0?0:k+.95)});
let tour=0;
function startTour(){const t=track(),from=SC.scrollTop,to=t.y0+(1/STEPS)*t.span,dur=1900,t0=performance.now(),id=++tour;if(REDUCE){SC.scrollTo(0,to);return}
  const step=now=>{if(id!==tour)return;const u=clamp((now-t0)/dur),e=u<.5?2*u*u:1-2*(1-u)*(1-u);SC.scrollTo(0,from+(to-from)*e);if(u<1)requestAnimationFrame(step)};requestAnimationFrame(step)}
['wheel','touchstart','keydown'].forEach(n=>addEventListener(n,()=>{tour++},{passive:true}));addEventListener('pointerdown',ev=>{if(!ev.target.closest('.sx-site'))tour++});
site.addEventListener('click',ev=>{if(ev.target.closest('[data-station]'))return;if(sv<.6)startTour()});
SC.addEventListener('scroll',onScroll,{passive:true});
const lock=ev=>{if(!dialog.open)return;const b=ev.target.closest&&ev.target.closest('.infra-dialog-body');if(!b||b.scrollHeight<=b.clientHeight+1||(ev.deltaY<0&&b.scrollTop<=0)||(ev.deltaY>0&&b.scrollTop+b.clientHeight>=b.scrollHeight-1))ev.preventDefault()};addEventListener('wheel',lock,{passive:false});addEventListener('touchmove',lock,{passive:false});addEventListener('keydown',ev=>{if(dialog.open&&['ArrowDown','ArrowUp','PageDown','PageUp','Home','End',' '].includes(ev.key)&&!ev.target.closest('a,button'))ev.preventDefault()});
addEventListener('resize',()=>{if(innerWidth<980&&overlay.open){overlay.close();return}if((innerWidth>=980&&overlay.open)!==wide){setup();onScroll()}});
let rw=0,rh=0;new ResizeObserver(()=>{const w=scene.clientWidth,h=scene.clientHeight;if(w===rw&&h===rh)return;rw=w;rh=h;setup();rw=scene.clientWidth;rh=scene.clientHeight;onScroll();dirty=false;draw()}).observe(scene);

root.classList.add('sx-closed');setup();
if(document.fonts&&document.fonts.ready)document.fonts.ready.then(()=>{fitNames();LIDS.forEach(id=>CH[id]=cards[id].offsetHeight);dirty=true});
const hp=(location.hash.match(/(?:^|[#&])p=([\d.]+)/)||[])[1]??null;
const jump=v=>{snap=true;go(v,true);onScroll();svS=sv;setTimeout(()=>{go(v,true);onScroll();svS=sv;dirty=true;snap=false;tick()},70)};
if(hp!==null)jump(parseFloat(hp));else if(REDUCE)jump(STEPS);
onScroll();
const openOverlay=()=>{if(innerWidth<980||overlay.open)return;overlay.showModal();SC.scrollTop=0;sv=svS=0;setup();onScroll();dirty=true};
document.addEventListener('click',ev=>{if(ev.target.closest('[data-infra-open]'))openOverlay();else if(ev.target.closest('[data-infra-close]'))overlay.close()});
overlay.addEventListener('close',()=>{wide=false;overlay.classList.remove('infra-instant');if(location.hash==='#grafik')history.replaceState(null,'',location.pathname+location.search)});
if(D.auto||location.hash==='#grafik'){overlay.classList.add('infra-instant');openOverlay()}
document.querySelector('[data-infra-cover]')?.remove();
addEventListener('hashchange',()=>{if(location.hash==='#grafik')openOverlay()});
last=performance.now();requestAnimationFrame(frame);setInterval(()=>{if(performance.now()-last>80)tick()},40);
}
