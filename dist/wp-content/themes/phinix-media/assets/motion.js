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
  if (!story) return;
  const section = story.closest('.screens-story');
  const toggle = story.querySelector('.screens-story__toggle');
  const steps = [...section.querySelectorAll('.screens-story__steps li')];
  if (reduceMotion || !window.gsap) {
    if (toggle) toggle.hidden = true;
    steps[0]?.classList.add('is-active');
    steps[0]?.setAttribute('aria-current', 'step');
    return;
  }

  const person = story.querySelector('.screens-story__person');
  const shadow = story.querySelector('.screens-story__shadow');
  const world = story.querySelector('.screens-story__world');
  const walkFrames = [...story.querySelectorAll('.screens-story__walk-frame')];
  const scan = story.querySelector('.screens-story__pose--scan');
  const jump = story.querySelector('.screens-story__pose--jump');
  const signal = story.querySelector('.screens-story__signal');
  const beam = story.querySelector('.screens-story__scan-beam');
  const qr = story.querySelector('.screens-story__qr');
  const screen = story.querySelector('.screens-story__screen');
  const idle = story.querySelector('.screens-story__idle');
  const ad = story.querySelector('.screens-story__ad');
  const flash = story.querySelector('.screens-story__flash');
  const success = story.querySelector('.screens-story__success');
  const statement = [...story.querySelectorAll('.screens-story__statement > *')];
  const caption = story.querySelector('[data-story-caption]');
  const progress = story.querySelector('.screens-story__progress span');
  const captions = [
    'Der Kunde nähert sich dem gebuchten Display.',
    'Das Smartphone erkennt den QR-Code.',
    'Der Werbespot wird auf dem Screen freigeschaltet.',
    'Kampagne live – sichtbar mitten in Gladbeck.'
  ];

  section.classList.add('is-enhanced');
  const setPhase = index => {
    caption.textContent = captions[index];
    steps.forEach((item, i) => {
      item.classList.toggle('is-active', i === index);
      if (i === index) item.setAttribute('aria-current', 'step');
      else item.removeAttribute('aria-current');
    });
  };

  const walkDistance = () => {
    const display = story.querySelector('.screens-story__display');
    return Math.max(0, display.offsetLeft - person.offsetLeft - person.offsetWidth * .93);
  };

  const positionScanFx = () => {
    const stageRect = story.getBoundingClientRect();
    const personRect = person.getBoundingClientRect();
    const qrRect = qr.getBoundingClientRect();
    const startX = personRect.left - stageRect.left + personRect.width * .78;
    const startY = personRect.top - stageRect.top + personRect.height * .28;
    const endX = qrRect.left - stageRect.left + qrRect.width * .5;
    const endY = qrRect.top - stageRect.top + qrRect.height * .5;
    const deltaX = endX - startX;
    const deltaY = endY - startY;
    const thickness = Math.max(24, Math.min(40, personRect.width * .12));
    gsap.set(beam, {
      left: startX,
      top: startY - thickness * .5,
      width: Math.hypot(deltaX, deltaY),
      height: thickness,
      rotation: Math.atan2(deltaY, deltaX) * 180 / Math.PI,
      transformOrigin: '0 50%'
    });
    gsap.set(signal, {left: startX - 10, top: startY - 28});
  };

  const resetScene = () => {
    gsap.set(person, {x: 0, y: 0, rotation: 0, scaleX: 1, scaleY: 1});
    gsap.set(shadow, {x: 0, scaleX: 1, opacity: 1});
    gsap.set(walkFrames, {autoAlpha: 0});
    if (walkFrames[0]) gsap.set(walkFrames[0], {autoAlpha: 1});
    gsap.set([scan, jump, signal, flash], {autoAlpha: 0});
    gsap.set(qr, {autoAlpha: 0, scale: .78, boxShadow: '0 0 0 0 rgba(255,100,46,0)'});
    gsap.set(ad, {autoAlpha: 0, scale: 1.06});
    gsap.set(ad.children, {autoAlpha: 0, y: 16});
    gsap.set(idle, {autoAlpha: 1, opacity: 1});
    gsap.set(success, {autoAlpha: 0, y: 12, scale: 1});
    gsap.set(beam, {autoAlpha: 0, scaleX: 0});
    gsap.set(statement, {autoAlpha: 0, y: 18});
    gsap.set(progress, {width: 0});
    gsap.set(world, {autoAlpha: 0});
    screen.classList.remove('is-live');
  };

  resetScene();
  setPhase(0);
  toggle.hidden = false;

  const tl = gsap.timeline({
    paused: true,
    repeat: -1,
    repeatDelay: .8,
    repeatRefresh: true,
    defaults: {ease: 'power2.inOut'}
  });

  tl.call(resetScene, null, 0)
    .call(() => setPhase(0), null, 0)
    .to(world, {autoAlpha: 1, duration: .55, ease: 'power1.out'}, 0)
    .fromTo(statement, {autoAlpha: 0, y: 18}, {autoAlpha: 1, y: 0, stagger: .09, duration: .65}, .1)
    .to(progress, {width: '28%', duration: 3.55, ease: 'none'}, 0)
    .to(person, {x: walkDistance, duration: 3.2, ease: 'power1.inOut'}, .45)
    .to(shadow, {x: walkDistance, duration: 3.2, ease: 'power1.inOut'}, .45);

  const gaitY = [0, 4, 1, 0, 4, 1];
  const gaitRotation = [0, -.16, .1, 0, .16, -.1];
  const gaitCycles = 4;
  const gaitFrameDuration = 3.2 / Math.max(1, walkFrames.length * gaitCycles);
  for (let cycle = 0; cycle < gaitCycles; cycle += 1) {
    walkFrames.forEach((frame, index) => {
      const at = .45 + (cycle * walkFrames.length + index) * gaitFrameDuration;
      tl.set(walkFrames, {autoAlpha: 0}, at)
        .set(frame, {autoAlpha: 1}, at)
        .to(person, {
          y: gaitY[index] ?? 0,
          rotation: gaitRotation[index] ?? 0,
          duration: gaitFrameDuration,
          ease: 'sine.inOut'
        }, at);
    });
  }

  tl.call(() => setPhase(1), null, 3.55)
    .to(walkFrames, {autoAlpha: 0, duration: .12}, 3.55)
    .to(person, {y: 0, rotation: 0, duration: .22, ease: 'sine.out'}, 3.55)
    .fromTo(scan, {autoAlpha: 0, y: 8}, {autoAlpha: 1, y: 0, duration: .34}, 3.58)
    .to(progress, {width: '52%', duration: 1.9, ease: 'none'}, 3.55)
    .to(idle, {opacity: .28, duration: .4}, 3.8)
    .fromTo(qr, {autoAlpha: 0, scale: .78}, {autoAlpha: 1, scale: 1, boxShadow:'0 0 0 18px rgba(255,100,46,.14)',duration:.55,ease:'back.out(1.5)'}, 3.85)
    .call(positionScanFx, null, 3.72)
    .to(beam, {autoAlpha: 1, scaleX: 1, duration: .48, ease: 'power2.out'}, 4.28)
    .to(signal, {autoAlpha: 1, duration: .18}, 4.32)
    .fromTo(signal.querySelectorAll('i'), {scale:.35}, {scale:1.35,stagger:.11,repeat:3,yoyo:true,duration:.18,ease:'sine.inOut'}, 4.36)
    .call(() => setPhase(2), null, 5.45)
    .to(progress, {width: '76%', duration: 1.35, ease: 'none'}, 5.45)
    .to(beam, {autoAlpha: 0, scaleX: .2, duration: .25}, 5.48)
    .to(signal, {autoAlpha: 0, duration: .18}, 5.48)
    .to(flash, {autoAlpha: 1, duration: .09, repeat: 1, yoyo: true}, 5.58)
    .to(qr, {autoAlpha: 0, scale: 1.24, duration: .25}, 5.62)
    .to(idle, {autoAlpha: 0, duration: .3}, 5.62)
    .call(() => screen.classList.add('is-live'), null, 5.64)
    .fromTo(ad, {autoAlpha: 0, scale: 1.06}, {autoAlpha: 1, scale: 1, duration: .58, ease: 'expo.out'}, 5.66)
    .fromTo(ad.children, {y: 16, autoAlpha: 0}, {y: 0, autoAlpha: 1, stagger: .1, duration: .42}, 5.82)
    .fromTo(success, {autoAlpha: 0, y: 12}, {autoAlpha: 1, y: 0, duration: .35}, 6.15)
    .call(() => setPhase(3), null, 6.8)
    .to(progress, {width: '100%', duration: 3.65, ease: 'none'}, 6.8)
    .to(person, {y: 8, scaleY: .97, duration: .18, ease: 'power2.in'}, 6.85)
    .set(scan, {autoAlpha: 0}, 7.04)
    .set(jump, {autoAlpha: 1}, 7.04)
    .to(person, {y: -92, scaleY: 1, rotation: -2, duration: .4, ease: 'power3.out'}, 7.05)
    .to(shadow, {scaleX: .55, opacity: .35, duration: .4, ease: 'power2.out'}, 7.05)
    .to(person, {y: 0, rotation: 0, duration: .5, ease: 'power2.in'}, 7.45)
    .to(shadow, {scaleX: 1, opacity: 1, duration: .5, ease: 'power2.in'}, 7.45)
    .set(jump, {autoAlpha: 0}, 7.95)
    .set(scan, {autoAlpha: 1}, 7.95)
    .to(success, {scale:1.04,repeat:1,yoyo:true,duration:.2,ease:'sine.inOut'}, 8.05)
    .to({}, {duration: 2.15})
    .to(world, {autoAlpha: 0, duration: .45, ease: 'power1.in'}, 10.45)
    .to(statement, {autoAlpha: 0, y: -12, stagger: .05, duration: .32}, 10.45);

  let userPaused = false;
  let inView = false;
  let assetsReady = false;
  const syncPlayback = () => {
    if (assetsReady && inView && !userPaused && !document.hidden) tl.play();
    else tl.pause();
  };
  const observer = new IntersectionObserver(entries => {
    inView = entries[0]?.isIntersecting && entries[0].intersectionRatio >= .28;
    syncPlayback();
  }, {threshold:[0,.28,.65]});
  observer.observe(story);

  toggle.addEventListener('click', () => {
    userPaused = !userPaused;
    const label = userPaused ? 'Animation abspielen' : 'Animation pausieren';
    toggle.dataset.paused = String(userPaused);
    toggle.setAttribute('aria-label', label);
    toggle.textContent = label;
    syncPlayback();
  });
  document.addEventListener('visibilitychange', syncPlayback);
  const media = [...story.querySelectorAll('.screens-story__pose, .screens-story__qr img')];
  Promise.all(media.map(image => image.decode ? image.decode().catch(() => {}) : Promise.resolve()))
    .then(() => {
      assetsReady = true;
      syncPlayback();
    });
})();
