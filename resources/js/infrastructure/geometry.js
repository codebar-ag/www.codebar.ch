const fixed=n=>n.toFixed(1);

export function roundedPath(points,radius=18){
  const q=points.filter((p,i)=>!i||Math.hypot(p[0]-points[i-1][0],p[1]-points[i-1][1])>1.5);
  if(q.length===0)return '';
  let d='M'+fixed(q[0][0])+' '+fixed(q[0][1]);
  for(let i=1;i<q.length-1;i++){
    const a=q[i-1],b=q[i],c=q[i+1],l1=Math.hypot(b[0]-a[0],b[1]-a[1]),l2=Math.hypot(c[0]-b[0],c[1]-b[1]),r=Math.min(radius,l1/2,l2/2);
    d+='L'+fixed(b[0]-(b[0]-a[0])/l1*r)+' '+fixed(b[1]-(b[1]-a[1])/l1*r)+'Q'+fixed(b[0])+' '+fixed(b[1])+' '+fixed(b[0]+(c[0]-b[0])/l2*r)+' '+fixed(b[1]+(c[1]-b[1])/l2*r);
  }
  if(q.length>1){const z=q[q.length-1];d+='L'+fixed(z[0])+' '+fixed(z[1])}
  return d;
}
