/* Progressive enhancement: all scenes remain readable without JavaScript. */
(() => {
  'use strict';
  document.querySelectorAll('.phinix-hero').forEach((hero, instance) => {
    const panels = [...hero.querySelectorAll('.phinix-scene')];
    const controls = hero.querySelector('.scene-controls');
    if (panels.length !== 3 || !controls) return;
    const buttons = [...controls.querySelectorAll('button')];
    if (buttons.length !== panels.length) return;
    controls.setAttribute('role', 'tablist');
    controls.setAttribute('aria-label', 'Unsere Schwerpunkte');
    const select = (index, focus = false) => {
      buttons.forEach((button, i) => {
        const active = index === i;
        button.setAttribute('aria-selected', String(active));
        button.tabIndex = active ? 0 : -1;
        panels[i].hidden = !active;
      });
      hero.dataset.scene = String(index);
      if (focus) buttons[index].focus();
    };
    buttons.forEach((button, i) => {
      button.id = `phinix-tab-${instance}-${i}`;
      button.setAttribute('role', 'tab');
      panels[i].id = `phinix-panel-${instance}-${i}`;
      panels[i].setAttribute('role', 'tabpanel');
      panels[i].setAttribute('aria-labelledby', button.id);
      panels[i].tabIndex = 0;
      button.setAttribute('aria-controls', panels[i].id);
      button.addEventListener('click', () => select(i));
      button.addEventListener('keydown', event => {
        let next;
        if (event.key === 'ArrowRight') next = (i + 1) % buttons.length;
        if (event.key === 'ArrowLeft') next = (i + buttons.length - 1) % buttons.length;
        if (event.key === 'Home') next = 0;
        if (event.key === 'End') next = buttons.length - 1;
        if (next !== undefined) {
          event.preventDefault();
          select(next, true);
        }
      });
    });
    select(0);
    controls.hidden = false;
    hero.classList.add('motion-ready');
  });

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const story = document.querySelector('[data-screens-story]');
  if (!story || reduceMotion || !window.gsap || !window.ScrollTrigger) return;

  gsap.registerPlugin(ScrollTrigger);
  const section = story.closest('.screens-story');
  const person = story.querySelector('.screens-story__person');
  const signal = story.querySelector('.screens-story__signal');
  const qr = story.querySelector('.screens-story__qr');
  const idle = story.querySelector('.screens-story__idle');
  const ad = story.querySelector('.screens-story__ad');
  const flash = story.querySelector('.screens-story__flash');
  const burst = story.querySelector('.screens-story__burst');
  const types = [...story.querySelectorAll('.screens-story__type span')];
  const step = story.querySelector('[data-story-step]');
  const caption = story.querySelector('[data-story-caption]');
  const progress = story.querySelector('.screens-story__progress span');
  const captions = [
    'Passenden Standort und Zeitraum auswählen.',
    'QR-Code am Display mit dem Smartphone scannen.',
    'Motiv freigeben – der Werbespot geht live.',
    'Lokale Aufmerksamkeit, die Freude macht.'
  ];

  section.classList.add('is-enhanced');
  const showSlide = index => {
    types.forEach((type, i) => type.classList.toggle('is-active', i === index));
    step.textContent = String(index + 1).padStart(2, '0');
    caption.textContent = captions[index];
  };

  gsap.set(types.slice(1), {autoAlpha: 0, y: 60});
  gsap.set(person, {xPercent: -35});
  const tl = gsap.timeline({
    defaults: {ease: 'power3.inOut'},
    scrollTrigger: {
      trigger: story,
      start: 'top top+=24',
      end: '+=3200',
      scrub: 1,
      pin: true,
      anticipatePin: 1,
      invalidateOnRefresh: true,
      onUpdate: self => showSlide(Math.min(3, Math.floor(self.progress * 4)))
    }
  });

  tl.to(progress, {width: '25%', duration: 1}, 0)
    .to(person, {xPercent: 62, duration: 1.6, ease: 'power2.inOut'}, 0)
    .to(person, {y: -8, repeat: 5, yoyo: true, duration: .16, ease: 'sine.inOut'}, .05)
    .to(types[0], {autoAlpha: 0, y: -60, duration: .25}, 1.45)
    .to(types[1], {autoAlpha: 1, y: 0, duration: .35}, 1.55)
    .to(progress, {width: '50%', duration: .8}, 1.5)
    .to(signal, {autoAlpha: 1, duration: .2}, 1.65)
    .fromTo(signal.querySelectorAll('i'), {scale: .2}, {scale: 1.5, stagger: .1, repeat: 2, yoyo: true, duration: .2}, 1.7)
    .to(qr, {autoAlpha: 1, scale: 1, boxShadow: '0 0 0 22px rgba(255,100,46,.16)', duration: .6, ease: 'back.out(1.7)'}, 1.75)
    .to(types[1], {autoAlpha: 0, y: -60, duration: .25}, 2.65)
    .to(types[2], {autoAlpha: 1, y: 0, duration: .35}, 2.75)
    .to(progress, {width: '75%', duration: .8}, 2.7)
    .to(signal, {autoAlpha: 0, duration: .2}, 2.75)
    .to(flash, {autoAlpha: 1, duration: .08, repeat: 1, yoyo: true}, 2.9)
    .to(qr, {autoAlpha: 0, scale: 1.7, duration: .25}, 2.95)
    .to(idle, {autoAlpha: 0, duration: .25}, 2.95)
    .to(ad, {autoAlpha: 1, scale: 1, duration: .65, ease: 'expo.out'}, 3)
    .to(types[2], {autoAlpha: 0, y: -60, duration: .25}, 3.7)
    .to(types[3], {autoAlpha: 1, y: 0, duration: .35}, 3.8)
    .to(progress, {width: '100%', duration: .8}, 3.75)
    .to(person, {y: -95, rotation: -7, duration: .28, ease: 'power3.out'}, 3.95)
    .to(person, {y: 0, rotation: 0, duration: .38, ease: 'bounce.out'}, 4.23)
    .to(burst, {autoAlpha: 1, scale: 1, rotation: -6, duration: .35, ease: 'back.out(2)'}, 4)
    .to(burst, {scale: 1.08, repeat: 1, yoyo: true, duration: .18}, 4.35)
    .to({}, {duration: .65});
})();
