(() => {
    const tour = document.getElementById('stadion-demo');
    if (!tour) return;
    const scenes = Array.from(tour.querySelectorAll('.stadium-scene'));
    const stops = Array.from(tour.querySelectorAll('.stadium-route a'));
    const initial = scenes[0];

    function show(scene) {
        for (const panel of scenes) {
            panel.hidden = panel !== scene;
            if (panel.hidden) panel.querySelector('details').open = false;
        }
        for (const stop of stops) {
            if (stop.hash === '#' + scene.id) stop.setAttribute('aria-current', 'step');
            else stop.removeAttribute('aria-current');
        }
    }

    function requestedScene() {
        return scenes.find(scene => '#' + scene.id === window.location.hash) || initial;
    }

    tour.addEventListener('click', event => {
        const link = event.target.closest('a[data-tour-link]');
        if (!link || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        const scene = scenes.find(panel => '#' + panel.id === link.hash);
        if (!scene) return;
        event.preventDefault();
        if (window.location.hash !== link.hash) window.history.pushState(null, '', link.hash);
        show(scene);
        if (!link.closest('.stadium-route')) {
            // The original control disappears when the station changes.
            const stop = stops.find(item => item.hash === link.hash);
            stop.focus({preventScroll: true});
            tour.scrollIntoView({block: 'start', behavior: 'instant'});
        }
    });
    tour.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;
        const point = tour.querySelector('details[open]');
        if (point) {
            point.open = false;
            point.querySelector('summary').focus();
        }
    });

    window.addEventListener('hashchange', () => show(requestedScene()));
    show(requestedScene());
})();
