/**
 * JS del sitio. Tres bloques: eventos GA4 (WhatsApp / teléfono), header + menú móvil, extras de diseño
 * (contadores, carrusel de reseñas, buscador del 404). NO se toca para armar un sitio nuevo.
 */

// ── Eventos GA4: todos los <a data-wsp> disparan click_wsp con servicio, zona y página ──
(() => {
  const cfg = window.sitioConfig || {};
  const enviar = (nombre, params) => {
    if (typeof window.gtag === 'function') {
      window.gtag('event', nombre, params);
    } else if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push(Object.assign({ event: nombre }, params));
    }
  };
  document.addEventListener('click', (ev) => {
    const a = ev.target.closest('a');
    if (!a) return;
    if (a.hasAttribute('data-wsp')) {
      enviar(cfg.eventoWsp || 'click_wsp', {
        servicio: a.dataset.servicio || cfg.servicio || '',
        zona: a.dataset.zona || cfg.zona || '',
        pagina: a.dataset.pagina || cfg.pagina || '',
        ubicacion: a.className.split(' ')[0] || '',
        path: window.location.pathname,
      });
    } else if (a.hasAttribute('data-tel')) {
      enviar(cfg.eventoTel || 'click_tel', { servicio: cfg.servicio || '', zona: cfg.zona || '', pagina: cfg.pagina || '', path: window.location.pathname });
    }
  });

  // Sin número de WhatsApp cargado, los botones llevan al formulario de contacto en la misma pestaña
  document.querySelectorAll('a[href*="origen=whatsapp"]').forEach((a) => {
    a.removeAttribute('target');
    a.removeAttribute('rel');
  });
})();

// ── Header & navegación ──
const header = document.getElementById('header');
const siteTop = document.getElementById('site-top');
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobile-menu');
const isHomePage = document.body.classList.contains('home-page');

if (hamburger && mobileMenu) {
  const closeMenu = () => {
    mobileMenu.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    mobileMenu.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (isHomePage && header && window.scrollY <= 50) header.classList.remove('header--scrolled');
    syncTop();
  };
  hamburger.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    hamburger.setAttribute('aria-expanded', open);
    mobileMenu.setAttribute('aria-hidden', !open);
    document.body.style.overflow = open ? 'hidden' : '';
    if (isHomePage && header && window.scrollY <= 50) header.classList.toggle('header--scrolled', open);
    syncTop();
  });
  mobileMenu.querySelectorAll('.mobile-link').forEach((link) => link.addEventListener('click', closeMenu));
}

// Celular + home: el header se va con el scroll y vuelve deslizándose pasado HIDE_PX
const HIDE_PX = 120;
const HEADER_H = 72;
const isMobile = () => window.matchMedia('(max-width: 600px)').matches;

const syncTop = () => {
  const menuOpen = mobileMenu && mobileMenu.classList.contains('open');
  const y = window.scrollY;
  const mobileHome = isHomePage && isMobile();
  const scrolled = y > (mobileHome ? HIDE_PX : 50);
  if (isHomePage && header) header.classList.toggle('header--scrolled', scrolled || menuOpen);
  if (!siteTop) return;
  siteTop.classList.toggle('is-scrolled', scrolled);
  if (mobileHome && !menuOpen && y <= HIDE_PX) {
    siteTop.classList.remove('site-top--reveal');
    siteTop.style.transform = `translateY(-${Math.min(y, HEADER_H)}px)`;
  } else {
    siteTop.classList.toggle('site-top--reveal', mobileHome && !menuOpen);
    siteTop.style.transform = '';
  }
};
if (header || siteTop) {
  window.addEventListener('scroll', syncTop, { passive: true });
  window.addEventListener('resize', syncTop);
  syncTop();
}

// ── Contadores animados ([data-count-to]) ──
const statCounters = document.querySelectorAll('[data-count-to]');
if (statCounters.length) {
  const animateCounter = (el) => {
    const target = parseInt(el.dataset.countTo, 10);
    if (isNaN(target)) return;
    const duration = 1600;
    const startTime = performance.now();
    const tick = (now) => {
      const progress = Math.min((now - startTime) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.round(target * eased).toString();
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };
  if ('IntersectionObserver' in window) {
    const obs = new IntersectionObserver((entries, o) => {
      entries.forEach((entry) => { if (entry.isIntersecting) { animateCounter(entry.target); o.unobserve(entry.target); } });
    }, { threshold: 0.4 });
    statCounters.forEach((c) => obs.observe(c));
  } else {
    statCounters.forEach(animateCounter);
  }
}

// ── Reseñas: carrusel con scroll infinito, táctil y teclado ──
(() => {
  document.querySelectorAll('[data-reviews]').forEach((section) => {
    const track = section.querySelector('.reviews-strip__track');
    const cards = [...track.children];
    if (cards.length < 2) return;
    const copy = (card) => { const clone = card.cloneNode(true); clone.setAttribute('aria-hidden', 'true'); clone.inert = true; return clone; };
    track.prepend(...cards.map(copy));
    track.append(...cards.map(copy));
    let step = 0, cycle = 0, restoring = false;
    const jump = (left) => {
      restoring = true;
      track.style.scrollSnapType = 'none';
      track.scrollTo({ left, behavior: 'instant' });
      requestAnimationFrame(() => { track.style.scrollSnapType = ''; restoring = false; });
    };
    const measure = () => {
      const index = step ? Math.round(track.scrollLeft / step) % cards.length : 0;
      step = cards[1].getBoundingClientRect().left - cards[0].getBoundingClientRect().left;
      cycle = step * cards.length;
      jump(cycle + index * step);
    };
    const wrap = () => {
      if (restoring || !cycle) return;
      if (track.scrollLeft < cycle - 1) jump(track.scrollLeft + cycle);
      else if (track.scrollLeft >= cycle * 2 - 1) jump(track.scrollLeft - cycle);
    };
    let settle;
    track.addEventListener('scroll', () => { clearTimeout(settle); settle = setTimeout(wrap, 160); }, { passive: true });
    track.addEventListener('scrollend', wrap);
    new ResizeObserver(measure).observe(track);
    measure();
    const move = (direction) => {
      const index = Math.round(track.scrollLeft / step);
      track.scrollTo({ left: (index + direction) * step, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' });
    };
    track.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') { event.preventDefault(); move(event.key === 'ArrowRight' ? 1 : -1); }
    });
  });
})();

// ── Buscador de zonas del 404 ──
(() => {
  const q = document.getElementById('e404-buscar');
  const items = document.querySelectorAll('#e404-lista li');
  if (!q) return;
  const norm = (s) => s.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
  q.addEventListener('input', () => {
    const v = norm(q.value.trim());
    items.forEach((li) => { li.hidden = v !== '' && norm(li.dataset.nombre).indexOf(v) === -1; });
  });
})();
