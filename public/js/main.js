// Sin numero de WhatsApp cargado, los botones de WhatsApp llevan al formulario de contacto
// (misma pestaña). Cuando haya numero, los links vuelven a ser wa.me y esto no aplica.
document.querySelectorAll('a[href*="origen=whatsapp"]').forEach(a => {
  a.removeAttribute('target');
  a.removeAttribute('rel');
});

// Header & navigation
const header = document.getElementById('header');
const siteTop = document.getElementById('site-top');
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobile-menu');
const isHomePage = document.body.classList.contains('home-page');

// Mobile menu toggle
if (hamburger && mobileMenu) {
  const closeMenu = () => {
    mobileMenu.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    mobileMenu.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (isHomePage && header && window.scrollY <= 50) {
      header.classList.remove('header--scrolled');
    }
    syncTop();
  };

  hamburger.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    hamburger.setAttribute('aria-expanded', open);
    mobileMenu.setAttribute('aria-hidden', !open);
    document.body.style.overflow = open ? 'hidden' : '';

    if (isHomePage && header && window.scrollY <= 50) {
      header.classList.toggle('header--scrolled', open);
    }
    syncTop();
  });

  mobileMenu.querySelectorAll('.mobile-link').forEach(link => {
    link.addEventListener('click', closeMenu);
  });
}

// Header + topbar wrapper sync on scroll
// Celular + home: los primeros HIDE_PX de scroll el header se va con el contenido (queda atras);
// pasado ese punto entra deslizandose (transicion en .site-top--reveal) y se pone blanco.
const HIDE_PX = 120;   // scroll a partir del cual el header vuelve a aparecer
const HEADER_H = 72;   // cuanto se desplaza como maximo (alto del header + margen)
const isMobile = () => window.matchMedia('(max-width: 600px)').matches;

const syncTop = () => {
  const menuOpen = mobileMenu && mobileMenu.classList.contains('open');
  const y = window.scrollY;
  const mobileHome = isHomePage && isMobile();
  const scrolled = y > (mobileHome ? HIDE_PX : 50);
  if (isHomePage && header) {
    header.classList.toggle('header--scrolled', scrolled || menuOpen);
  }
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

const metricsConfig = window.siteMetricsConfig || {};

if (metricsConfig.enabled && !metricsConfig.isMetricsPage) {
  const clientIdKey = 'mudanzasmontevideo_metrics_client_id';

  const getClientId = () => {
    let clientId = localStorage.getItem(clientIdKey);
    if (!clientId) {
      clientId = `mm-${Math.random().toString(36).slice(2)}${Date.now().toString(36)}`;
      localStorage.setItem(clientIdKey, clientId);
    }
    return clientId;
  };

  const sendMetric = (payload) => {
    const body = JSON.stringify({
      client_id: getClientId(),
      path: window.location.pathname,
      title: document.title,
      referrer: document.referrer,
      utm_source: new URLSearchParams(window.location.search).get('utm_source') || '',
      language: navigator.language || '',
      screen: `${window.innerWidth}x${window.innerHeight}`,
      timezone: Intl.DateTimeFormat().resolvedOptions().timeZone || '',
      ...payload,
    });

    if (navigator.sendBeacon) {
      navigator.sendBeacon(metricsConfig.endpoint, new Blob([body], { type: 'application/json' }));
      return;
    }

    fetch(metricsConfig.endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body,
      keepalive: true,
    }).catch(() => {});
  };

  sendMetric({ type: 'pageview', event_name: 'pageview' });

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a');
    if (!link) {
      return;
    }

    const href = link.getAttribute('href') || '';
    const isWhatsapp = href.includes('wa.me') || href.includes('whatsapp');
    const isPhone = href.startsWith('tel:');
    const explicitName = link.dataset.track || '';

    if (!isWhatsapp && !isPhone && !explicitName) {
      return;
    }

    sendMetric({
      type: 'click',
      event_name: explicitName || (isWhatsapp ? 'whatsapp_click' : 'phone_click'),
      href,
      label: (link.textContent || '').trim().slice(0, 120),
    });
  });
}

// Animated counters (.stats-banner__count)
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
    const counterObserver = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    statCounters.forEach((c) => counterObserver.observe(c));
  } else {
    statCounters.forEach(animateCounter);
  }
}

// Galeria de proyecto (ficha de proyecto): visor + thumbs + lightbox
(function () {
  const gallery = document.getElementById('pj-gallery');
  if (!gallery) return;

  let fuentes = [];
  try {
    fuentes = JSON.parse(gallery.dataset.imagenes || '[]');
  } catch (e) {
    return;
  }
  if (!fuentes.length) return;

  const stageImg = document.getElementById('pj-stage-img');
  const stageCount = document.getElementById('pj-stage-count');
  const thumbs = Array.from(gallery.querySelectorAll('.pj-thumb'));
  const lightbox = document.getElementById('pj-lightbox');
  const lightboxImg = document.getElementById('pj-lightbox-img');
  const lightboxCount = document.getElementById('pj-lightbox-count');
  const total = fuentes.length;
  let actual = 0;

  function mostrar(indice) {
    actual = (indice + total) % total;
    const src = fuentes[actual];

    if (stageImg) stageImg.src = src;
    if (stageCount) stageCount.textContent = (actual + 1) + ' / ' + total;
    if (lightboxImg && lightbox && lightbox.classList.contains('is-open')) lightboxImg.src = src;
    if (lightboxCount) lightboxCount.textContent = (actual + 1) + ' / ' + total;

    thumbs.forEach(function (t, i) {
      t.setAttribute('aria-current', i === actual ? 'true' : 'false');
    });

    const activo = thumbs[actual];
    if (activo) activo.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
  }

  thumbs.forEach(function (t) {
    t.addEventListener('click', function () {
      mostrar(parseInt(t.dataset.pjIndex, 10) || 0);
    });
  });

  document.querySelectorAll('[data-pj-step]').forEach(function (btn) {
    btn.addEventListener('click', function (ev) {
      ev.stopPropagation();
      mostrar(actual + (parseInt(btn.dataset.pjStep, 10) || 0));
    });
  });

  // Lightbox
  function abrir() {
    if (!lightbox) return;
    lightboxImg.src = fuentes[actual];
    if (lightboxCount) lightboxCount.textContent = (actual + 1) + ' / ' + total;
    lightbox.classList.add('is-open');
    document.body.classList.add('pj-noscroll');
  }

  function cerrar() {
    if (!lightbox) return;
    lightbox.classList.remove('is-open');
    document.body.classList.remove('pj-noscroll');
  }

  const zoom = document.getElementById('pj-zoom');
  if (zoom) zoom.addEventListener('click', abrir);
  if (stageImg) stageImg.addEventListener('click', abrir);

  const cerrarBtn = document.getElementById('pj-lightbox-close');
  if (cerrarBtn) cerrarBtn.addEventListener('click', cerrar);

  if (lightbox) {
    lightbox.addEventListener('click', function (ev) {
      if (ev.target === lightbox) cerrar();
    });
  }

  document.addEventListener('keydown', function (ev) {
    const abierto = lightbox && lightbox.classList.contains('is-open');
    if (ev.key === 'Escape' && abierto) return cerrar();
    if (!abierto && document.activeElement && !gallery.contains(document.activeElement)) return;
    if (ev.key === 'ArrowLeft') mostrar(actual - 1);
    if (ev.key === 'ArrowRight') mostrar(actual + 1);
  });

  // Swipe en mobile sobre el visor
  const stage = gallery.querySelector('.pj-stage');
  if (stage) {
    let inicioX = 0;
    stage.addEventListener('touchstart', function (ev) {
      inicioX = ev.changedTouches[0].clientX;
    }, { passive: true });
    stage.addEventListener('touchend', function (ev) {
      const delta = ev.changedTouches[0].clientX - inicioX;
      if (Math.abs(delta) > 45) mostrar(actual + (delta < 0 ? 1 : -1));
    }, { passive: true });
  }
})();

// Slideshow del fondo del hero (solo mobile): terminado primero, proceso despues
(function () {
  const bg = document.querySelector('.hero__bg');
  if (!bg) return;

  let rutas = [];
  try {
    rutas = JSON.parse(bg.dataset.slides || '[]');
  } catch (e) {
    return;
  }
  if (!rutas.length) return;
  if (!window.matchMedia('(max-width: 600px)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  if (navigator.connection && navigator.connection.saveData) return;

  const overlay = bg.querySelector('.hero__bg-overlay');
  const slides = rutas.map(function (src) {
    const img = document.createElement('img');
    img.className = 'hero__bg-slide';
    img.alt = '';
    img.setAttribute('aria-hidden', 'true');
    img.decoding = 'async';
    img.dataset.src = src;
    bg.insertBefore(img, overlay);
    return img;
  });

  const total = slides.length;
  let actual = total; // arranca en la foto base del <picture>

  function precargar(i) {
    const s = slides[i];
    if (s && !s.src) s.src = s.dataset.src;
  }

  function avanzar() {
    if (document.hidden) return;
    const sig = (actual + 1) % (total + 1); // total = foto base
    if (sig < total) {
      const el = slides[sig];
      if (!el.src) el.src = el.dataset.src;
      if (!(el.complete && el.naturalWidth)) return; // reintenta en el proximo tick
      slides.forEach(function (s, i) { s.classList.toggle('is-active', i === sig); });
      actual = sig;
      precargar(sig + 1);
    } else {
      slides.forEach(function (s) { s.classList.remove('is-active'); });
      actual = total;
    }
  }

  // Precarga la primera diapositiva cuando el hero ya esta pintado
  setTimeout(function () { precargar(0); }, 2200);
  setInterval(avanzar, 4500);
})();

// Reseñas: avanzar una tarjeta, con soporte táctil y teclado.
(() => {
  document.querySelectorAll('[data-reviews]').forEach(section => {
    const track = section.querySelector('.reviews-strip__track');
    const cards = [...track.children];
    if (cards.length < 2) return;
    const copy = card => {
      const clone = card.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      clone.inert = true;
      return clone;
    };
    track.prepend(...cards.map(copy));
    track.append(...cards.map(copy));
    let step = 0;
    let cycle = 0;
    let restoring = false;
    const jump = left => {
      restoring = true;
      track.style.scrollSnapType = 'none';
      track.scrollTo({ left, behavior:'instant' });
      requestAnimationFrame(() => {
        track.style.scrollSnapType = '';
        restoring = false;
      });
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
    track.addEventListener('scroll', () => {
      clearTimeout(settle);
      settle = setTimeout(wrap, 160);
    }, { passive:true });
    track.addEventListener('scrollend', wrap);
    new ResizeObserver(measure).observe(track);
    measure();
    const move = direction => {
      const index = Math.round(track.scrollLeft / step);
      track.scrollTo({ left: (index + direction) * step, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' });
    };
    track.addEventListener('keydown', event => {
      if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
        event.preventDefault();
        move(event.key === 'ArrowRight' ? 1 : -1);
      }
    });
  });
})();
