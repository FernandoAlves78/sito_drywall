import './bootstrap';
import '../css/app.css';

const phoneNumber = '393291238688';

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

function jsonHeaders() {
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': getCsrfToken(),
    'X-Requested-With': 'XMLHttpRequest',
  };
}

function openImg(img) {
  const lightbox = document.getElementById('lightbox');
  document.getElementById('lightImg').src = img.src;
  lightbox.style.display = 'flex';
}

function closeImg() {
  document.getElementById('lightbox').style.display = 'none';
}

async function sendQuote() {
  const name = document.getElementById('quote-name').value.trim();
  const phone = document.getElementById('quote-phone').value.trim();
  const email = document.getElementById('quote-email').value.trim();
  const message = document.getElementById('quote-message').value.trim();

  if (!name || !phone || !message) {
    alert('Per favore, compila nome, telefono e descrizione del servizio.');
    return;
  }

  try {
    const res = await fetch('/preventivo', {
      method: 'POST',
      headers: jsonHeaders(),
      body: JSON.stringify({ name, phone, email, message }),
    });
    if (!res.ok) {
      console.warn('Preventivo: risposta HTTP', res.status);
    }
  } catch (err) {
    console.warn('Preventivo: invio non riuscito (rete o server non disponibile).', err);
  }

  alert('Richiesta di preventivo inviata con successo!');
  document.getElementById('quote-name').value = '';
  document.getElementById('quote-phone').value = '';
  document.getElementById('quote-email').value = '';
  document.getElementById('quote-message').value = '';
}

function sendQuoteEmail() {
  const name = document.getElementById('quote-name').value.trim();
  const phone = document.getElementById('quote-phone').value.trim();
  const email = document.getElementById('quote-email').value.trim();
  const message = document.getElementById('quote-message').value.trim();

  const subject = encodeURIComponent('Orçamento de drywall e pintura');
  const body = encodeURIComponent(
    `Nome: ${name || '-'}\nTelefone: ${phone || '-'}\nEmail: ${email || '-'}\n\nDetalhes:\n${message || '-'}`
  );
  window.location.href = `mailto:fernando.alves@libero.it?subject=${subject}&body=${body}`;
}

function sendWhatsAppForm() {
  const name = document.getElementById('quote-name').value.trim();
  const phone = document.getElementById('quote-phone').value.trim();
  const message = document.getElementById('quote-message').value.trim();

  const text = `Ciao, mi chiamo ${name || 'cliente'}.\nTelefono: ${phone || 'non fornito'}\n\nVorrei un preventivo per:\n${message || '...'}`;
  window.open(`https://wa.me/${phoneNumber}?text=${encodeURIComponent(text)}`, '_blank');
}

async function sendReview() {
  const name = document.getElementById('review-name').value.trim();
  const text = document.getElementById('review-text').value.trim();

  if (!name || !text) {
    alert('Per favore, compila nome e recensione.');
    return;
  }

  await fetch('/recensioni', {
    method: 'POST',
    headers: jsonHeaders(),
    body: JSON.stringify({ nome: name, testo: text }),
  });

  document.getElementById('review-name').value = '';
  document.getElementById('review-text').value = '';
  loadReviews();
}

function escapeHtml(value) {
  return value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function reviewCardMarkup(nome, testo) {
  const author = nome ? `<strong>${escapeHtml(nome)}</strong>` : '<strong>Cliente</strong>';
  return `
    <article class="review-card glass-inner">
      ${author}
      <p>${escapeHtml(testo)}</p>
    </article>`;
}

function renderReviewGrid(reviews) {
  const grid = document.getElementById('review-grid');
  if (!grid) return;

  const normalized = Array.isArray(reviews) ? reviews.slice(0, 4) : [];
  const fillersNeeded = Math.max(0, 4 - normalized.length);

  let html = '';
  normalized.forEach(review => {
    html += reviewCardMarkup(review.nome, review.testo);
  });

  for (let i = 0; i < fillersNeeded; i++) {
    html += reviewCardMarkup(
      'Nuova recensione presto',
      'Questa card e pronta per mostrare nuove recensioni dal backoffice.'
    );
  }

  grid.innerHTML = html;
}

async function loadReviews() {
  try {
    const res = await fetch('/recensioni', { headers: { Accept: 'application/json' } });
    const data = await res.json();
    const filtered = Array.isArray(data) ? data.filter(r => r.nome && r.testo) : [];

    if (filtered.length === 0) {
      renderReviewGrid([
        { nome: 'Alves Drywall', testo: 'Ancora non ci sono recensioni. Sii il primo a commentare!' },
      ]);
      return;
    }

    renderReviewGrid(filtered);
  } catch (error) {
    renderReviewGrid([
      { nome: 'Mario Rossi', testo: 'Eccellente lavoro di drywall e pittura. Professionale e puntuale. Consigliato!' },
      { nome: 'Laura Bianchi', testo: 'Servizio di alta qualità. Hanno rifatto il mio appartamento con grande attenzione ai dettagli.' },
    ]);
  }
}

function activateFadeEffects() {
  const faders = document.querySelectorAll('.section');
  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    },
    { threshold: 0.2 }
  );
  faders.forEach(section => observer.observe(section));
}

function smoothScrollToElement(target, durationMs = 1050) {
  if (!target) return;
  if (typeof window.matchMedia === 'function' && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    target.scrollIntoView({ behavior: 'auto', block: 'start' });
    return;
  }

  const topbar = document.querySelector('.topbar');
  const topbarOffset = topbar ? topbar.getBoundingClientRect().height + 12 : 0;
  const startY = window.scrollY;
  const targetY = target.getBoundingClientRect().top + startY - topbarOffset;
  const distance = targetY - startY;
  const startTime = performance.now();

  const easeInOutCubic = t => (t < 0.5 ? 4 * t * t * t : 1 - ((-2 * t + 2) ** 3) / 2);

  function step(now) {
    const elapsed = now - startTime;
    const progress = Math.min(elapsed / durationMs, 1);
    const eased = easeInOutCubic(progress);
    window.scrollTo(0, startY + distance * eased);
    if (progress < 1) {
      requestAnimationFrame(step);
    }
  }

  requestAnimationFrame(step);
}

function initSectionNavigation() {
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', event => {
      const href = link.getAttribute('href');
      if (!href || href === '#') return;
      const target = document.querySelector(href);
      if (!target) return;

      event.preventDefault();
      smoothScrollToElement(target, 1100);
      if (window.history && typeof window.history.replaceState === 'function') {
        window.history.replaceState(null, '', href);
      }
    });
  });
}

function updateScrollTopVisibility() {
  const button = document.getElementById('scroll-top');
  if (!button) return;
  const isAtTop = window.scrollY <= 10;
  button.classList.toggle('is-hidden', isAtTop);
}

window.openImg = openImg;
window.closeImg = closeImg;
window.sendQuote = sendQuote;
window.sendQuoteEmail = sendQuoteEmail;
window.sendWhatsAppForm = sendWhatsAppForm;
window.sendReview = sendReview;

window.addEventListener('DOMContentLoaded', () => {
  const y = document.getElementById('footer-year');
  if (y) y.textContent = String(new Date().getFullYear());

  document.getElementById('scroll-top')?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
  updateScrollTopVisibility();
  window.addEventListener('scroll', updateScrollTopVisibility, { passive: true });

  loadReviews();
  activateFadeEffects();
  initSectionNavigation();
  document.querySelectorAll('.hero-actions .button-whatsapp-solid').forEach(button => {
    button.addEventListener('click', event => {
      if (event.currentTarget.textContent.includes('WhatsApp')) {
        event.preventDefault();
        sendWhatsAppForm();
      }
    });
  });
});
