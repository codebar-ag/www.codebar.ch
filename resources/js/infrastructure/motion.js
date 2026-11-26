export const lerp=(a,b,t)=>a+(b-a)*t;
export const clamp=(v,a=0,b=1)=>Math.min(b,Math.max(a,v));
export const smooth=(a,b,v)=>{const t=clamp((v-a)/(b-a));return t*t*(3-2*t)};

export function matrix(th,ph){
  return {a:Math.cos(th),b:-Math.sin(th)*Math.cos(ph),c:Math.sin(th),d:Math.cos(th)*Math.cos(ph)};
}

export function opennessTarget(sv,k){
  return clamp((sv-k-(k?.08:.4))/(k?.72:.5));
}

export function springStep(o,v,t,dt,stiffness=110,damping=13){
  for(let r=Math.min(1,Math.max(0,dt));r>0;r-=1/120){const h=Math.min(r,1/120);v+=((t-o)*stiffness-v*damping)*h;o+=v*h}
  if(Math.abs(t-o)<.0004&&Math.abs(v)<.002)return {o:t,v:0,moving:false};
  return {o,v,moving:true};
}

export function snapPoints(layers){
  const points=[];
  for(let k=0;k<layers;k++)points.push(k+.95);
  points.push(layers+1);
  return points;
}

export function nearestSnap(sv,layers){
  let best=0;
  snapPoints(layers).forEach(p=>{if(Math.abs(p-sv)<Math.abs(best-sv))best=p});
  return best;
}
