export const FILTER_PARAM='bereich';

export function initFilter(doc=document,win=window){
  const buttons=[...doc.querySelectorAll('[data-infra-filter]')],sections=[...doc.querySelectorAll('[data-infra-layer]')],reset=doc.querySelector('[data-infra-filter-reset]');
  if(!buttons.length)return null;
  const known=buttons.map(b=>b.dataset.infraFilter),active=new Set();
  (new URL(win.location.href).searchParams.get(FILTER_PARAM)||'').split(',').forEach(k=>{if(known.includes(k))active.add(k)});
  const apply=()=>{
    if(active.size===known.length)active.clear();
    buttons.forEach(b=>b.setAttribute('aria-pressed',String(active.has(b.dataset.infraFilter))));
    if(reset)reset.setAttribute('aria-pressed',String(active.size===0));
    sections.forEach(s=>{s.hidden=active.size>0&&!active.has(s.dataset.infraLayer)});
  };
  const write=()=>{
    const url=new URL(win.location.href);
    if(active.size)url.searchParams.set(FILTER_PARAM,known.filter(k=>active.has(k)).join(','));else url.searchParams.delete(FILTER_PARAM);
    win.history.replaceState(win.history.state,'',url.pathname+url.search.replace(/%2C/gi,',')+url.hash);
  };
  buttons.forEach(b=>b.addEventListener('click',()=>{const k=b.dataset.infraFilter;if(active.has(k))active.delete(k);else active.add(k);apply();write()}));
  if(reset)reset.addEventListener('click',()=>{active.clear();apply();write()});
  apply();
  return {active:()=>known.filter(k=>active.has(k))};
}
