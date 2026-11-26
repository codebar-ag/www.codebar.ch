export const esc=v=>String(v).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

const ICON='<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4h6v6"/><path d="M20 4l-9 9"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>';

const row=(label,value)=>`<dt>${esc(label)}</dt><dd>${value}</dd>`;

export function stationSheet(station,{labels,flags={},url=null}){
  const s=station,flag=code=>flags[code]||'';
  const places=s.countries.map((c,i)=>`${i===0&&s.place?esc(s.place)+', ':''}${flag(c.code)} ${esc(c.name)}`).join(' &nbsp; ');
  const open=url?`<a class="infra-dialog-open" href="${esc(url)}" target="_blank" rel="noopener" aria-label="${esc(labels.details)}" title="${esc(labels.details)}">${ICON}</a>`:'';
  const origin=s.vendor_country?row(labels.origin,`${flag(s.vendor_country.code)} ${esc(s.vendor_country.name)}`):'';
  const links=s.links.length?`<ul>${s.links.map(l=>`<li><a href="${esc(l.url)}" target="_blank" rel="noopener noreferrer"><b>↗ ${esc(l.label)}</b><span>${esc(l.host)}</span></a></li>`).join('')}</ul>`:'';
  return `<button type="button" class="infra-dialog-x" data-close aria-label="${esc(labels.close)}">×</button>${open}<div class="infra-dialog-body"><h2 id="infraDialogTitle">${esc(s.name)}</h2><p class="infra-dialog-lead">${esc(s.provider)} <span>${s.flags.map(flag).join('')}</span></p><p class="infra-dialog-text">${esc(s.text)}</p><dl>${row(labels.processing,places)}${row(labels.provider,esc(s.vendor))}${origin}${row(labels.operation,esc(s.operation))}</dl>${links}</div>`;
}
