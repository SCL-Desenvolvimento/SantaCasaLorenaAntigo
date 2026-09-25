"use strict";
window.SCLCarousel = function(root, track, slides, controls) {
  if (!slides.length) return;
  let index = 0, timer, paused = matchMedia('(prefers-reduced-motion: reduce)').matches, suspended = false;
  const prev=controls.querySelector('[data-gallery-prev]'),next=controls.querySelector('[data-gallery-next]'),count=controls.querySelector('[data-gallery-count]');
  const reset=()=>{clearTimeout(timer);if(slides.length>1&&!paused&&!suspended&&!document.hidden)timer=setTimeout(()=>show(index+1),6000);};
  const show=target=>{index=(target+slides.length)%slides.length;slides.forEach((slide,i)=>{slide.hidden=i!==index;slide.setAttribute('aria-hidden',String(i!==index));});count.textContent=(index+1)+' de '+slides.length;reset();};
  root.classList.add('carousel-ready');track.setAttribute('aria-roledescription','carrossel');controls.hidden=slides.length<2;
  prev.addEventListener('click',()=>show(index-1));next.addEventListener('click',()=>show(index+1));
  track.addEventListener('keydown',e=>{if(e.key==='ArrowLeft'||e.key==='ArrowRight'){e.preventDefault();show(index+(e.key==='ArrowRight'?1:-1));}});
  let start;track.addEventListener('pointerdown',e=>{start=e.clientX;});track.addEventListener('pointerup',e=>{if(start!==undefined&&Math.abs(e.clientX-start)>50)show(index+(e.clientX<start?1:-1));start=undefined;});
  document.addEventListener('visibilitychange',reset);show(0);
  return {show,suspend(value){suspended=value;reset();}};
};
document.querySelectorAll('[data-campaign-carousel]').forEach(root=>{const track=root.querySelector('.campaign-list');window.SCLCarousel(root,track,[...track.children],root.querySelector('.carousel-controls'));});
