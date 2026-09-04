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
})();
