const phoneNumber = '393291238688';

function showToast(message, type = 'success') {
  return new Promise(resolve => {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    container.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('toast-visible'));

    setTimeout(() => {
      toast.classList.remove('toast-visible');
      toast.addEventListener('transitionend', () => {
        toast.remove();
        resolve();
      });
    }, 4000);
  });
}

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
  const btn = document.querySelector('.contact-form .button-primary');
  const name = document.getElementById('quote-name').value.trim();
  const phoneRaw = document.getElementById('quote-phone').value.trim();
  const phone = phoneRaw ? `+39${phoneRaw}` : '';
  const email = document.getElementById('quote-email').value.trim();
  const message = document.getElementById('quote-message').value.trim();

  if (!name || !phoneRaw || !message) {
    btn.disabled = true;
    await showToast('Per favore, compila nome, telefono e descrizione del servizio.', 'error');
    btn.disabled = false;
    return;
  }

  btn.disabled = true;
  btn.classList.add('is-loading');

  try {
    const res = await fetch('/preventivo', {
      method: 'POST',
      headers: jsonHeaders(),
      body: JSON.stringify({ name, phone, email, message }),
    });

    btn.classList.remove('is-loading');

    if (!res.ok) {
      let errorMessage = 'Invio non riuscito. Controlla i dati inseriti.';
      try {
        const data = await res.json();
        const firstError = data.errors ? Object.values(data.errors).flat()[0] : null;
        if (firstError) {
          errorMessage = firstError;
        } else if (data.message) {
          errorMessage = data.message;
        }
      } catch {
        // ignore JSON parse errors
      }
      await showToast(errorMessage, 'error');
      btn.disabled = false;
      return;
    }

    document.getElementById('quote-name').value = '';
    document.getElementById('quote-phone').value = '';
    document.getElementById('quote-email').value = '';
    document.getElementById('quote-message').value = '';

    await showToast('Richiesta di preventivo inviata con successo!');
  } catch (err) {
    btn.classList.remove('is-loading');
    console.warn('Preventivo: invio non riuscito (rete o server non disponibile).', err);
    await showToast('Errore di connessione. Riprova più tardi.', 'error');
  }

  btn.disabled = false;
}

function sendQuoteEmail() {
  const name = document.getElementById('quote-name').value.trim();
  const phoneRaw = document.getElementById('quote-phone').value.trim();
  const phone = phoneRaw ? `+39 ${phoneRaw}` : '';
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
  const phoneRaw = document.getElementById('quote-phone').value.trim();
  const phone = phoneRaw ? `+39 ${phoneRaw}` : '';
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

  const quoteNameInput = document.getElementById('quote-name');
  quoteNameInput?.addEventListener('focusout', function () {
    if (/\d/.test(this.value)) {
      this.setCustomValidity('Il nome non può contenere numeri.');
      this.reportValidity();
      return;
    }
    if (this.value.replace(/[^a-zA-ZÀ-ÿ]/g, '').length < 3) {
      this.setCustomValidity('Il nome deve contenere almeno 3 lettere.');
      this.reportValidity();
      return;
    }
    this.setCustomValidity('');
    this.value = this.value.replace(/\S+/g, w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase());
  });

  const quotePhoneInput = document.getElementById('quote-phone');
  quotePhoneInput?.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
  });
  quotePhoneInput?.addEventListener('focusout', function () {
    const digits = this.value.replace(/\D/g, '');
    if (digits.length > 0 && (digits.length < 9 || digits.length > 10)) {
      this.setCustomValidity('Il numero deve avere 9 o 10 cifre (es. 329 123 8688).');
      this.reportValidity();
      return;
    }
    this.setCustomValidity('');
    if (digits.length === 10) {
      this.value = `${digits.slice(0, 3)} ${digits.slice(3, 6)} ${digits.slice(6)}`;
    } else if (digits.length === 9) {
      this.value = `${digits.slice(0, 3)} ${digits.slice(3, 6)} ${digits.slice(6)}`;
    }
  });

  const quoteEmailInput = document.getElementById('quote-email');
  quoteEmailInput?.addEventListener('focusout', function () {
    const val = this.value.trim();
    if (!val) return;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    if (!emailRegex.test(val)) {
      this.setCustomValidity('Inserisci un indirizzo email valido (es. nome@email.com).');
      this.reportValidity();
      return;
    }
    this.setCustomValidity('');
    this.value = val.toLowerCase();
  });

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
