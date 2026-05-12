<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Alves Drywall e Pittura | Preventivi e Recensioni</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <header class="topbar">
    <a href="{{ url('/') }}" class="brand brand-link" aria-label="Alves Drywall — inizio pagina">
      <img src="{{ asset('logo.svg') }}" width="200" height="54" alt="Alves Drywall & Pittura" class="brand-logo">
    </a>
    <nav class="menu">
      <a href="#galeria">Galeria</a>
      <a href="#servizi">Servizi</a>
      <a href="#recensioni">Recensioni</a>
      <a href="#contatto">Contatto</a>
    </nav>
  </header>

  <main>
    <section class="hero section-hero">
      <div class="hero-copy">
        <span>Finitura premium per il tuo spazio</span>
        <h1>Drywall e tinterggiatura professionale per case e attività commerciali</h1>
        <p>Servizi con finitura pulita, tempi rispettati e contatto diretto via WhatsApp o email.</p>
        <div class="hero-actions">
          <a href="#contatto" class="button button-primary">Richiedi un preventivo</a>
          <a href="https://wa.me/393291238688" target="_blank" rel="noopener noreferrer" class="button button-whatsapp-solid">Contatta su WhatsApp</a>
        </div>
      </div>
      <div class="hero-image">
        <img src="{{ asset('immagini/IMG_3.jpeg') }}" alt="Progetto drywall e pittura">
      </div>
    </section>

    <section id="galeria" class="section section-portfolio">
      <div class="section-header">
        <h2>Galeria</h2>
        <p>Guarda alcuni dei nostri lavori recenti di drywall e pittura con risultati professionali.</p>
      </div>
      <div class="gallery">
        <img src="{{ asset('immagini/IMG.JPG') }}" alt="Lavoro 1" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_1.JPG') }}" alt="Lavoro 2" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_2.jpeg') }}" alt="Lavoro 3" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_3.jpeg') }}" alt="Lavoro 4" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_4.jpeg') }}" alt="Lavoro 5" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_5.JPG') }}" alt="Lavoro 6" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_6.JPG') }}" alt="Lavoro 7" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_7.jpeg') }}" alt="Lavoro 8" onclick="openImg(this)">
        <img src="{{ asset('immagini/IMG_8.jpeg') }}" alt="Lavoro 9" onclick="openImg(this)">
      </div>
    </section>

    <section id="servizi" class="section section-services">
      <div class="section-header">
        <h2>Servizi</h2>
        <p>Installazione di drywall, pittura premium, riparazione pareti e soluzioni complete per il tuo progetto.</p>
      </div>
      <div class="cards">
        <article class="card">
          <h3>Drywall su misura</h3>
          <p>Controsoffitti, pareti divisorie, nicchie e cornici con finitura perfetta e installazione rapida.</p>
        </article>
        <article class="card">
          <h3>Pittura professionale</h3>
          <p>Vernici di qualità, preparazione delle superfici e finitura impeccabile per ambienti interni.</p>
        </article>
        <article class="card">
          <h3>Ristrutturazioni e rifiniture</h3>
          <p>Montaggio, riparazioni e rinnovo di spazi con attenzione alla qualità e durata.</p>
        </article>
      </div>
    </section>

    <section id="recensioni" class="section section-reviews">
      <div class="section-header">
        <h2>Recensioni</h2>
        <p>Scopri cosa dicono i clienti soddisfatti sui nostri servizi e assistenza.</p>
      </div>
      <div class="reviews-grid" id="review-grid" aria-label="Recensioni dei clienti">
        <article class="review-card glass-inner">
          <p class="review-text">Caricamento recensioni...</p>
        </article>
        <article class="review-card glass-inner">
          <p class="review-text">Caricamento recensioni...</p>
        </article>
        <article class="review-card glass-inner">
          <p class="review-text">Caricamento recensioni...</p>
        </article>
        <article class="review-card glass-inner">
          <p class="review-text">Caricamento recensioni...</p>
        </article>
      </div>
    </section>

    <section id="contatto" class="section section-contact">
      <div class="section-header">
        <h2>Contatto</h2>
        <p>Richiedi un preventivo tramite il modulo, email o WhatsApp. Risposta rapida e servizio personalizzato.</p>
      </div>
      <div class="contact-grid">
        <div class="contact-card contact-form">
          <input id="quote-name" type="text" placeholder="Nome" />
          <input id="quote-phone" type="text" placeholder="Telefono / WhatsApp" />
          <input id="quote-email" type="email" placeholder="Email" />
          <textarea id="quote-message" rows="5" placeholder="Descrivi il servizio o l'ambiente desiderato"></textarea>
          <div class="form-actions">
            <button class="button button-primary" onclick="sendQuote()">Invia preventivo</button>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-unified glass-panel">
        <div class="footer-columns">
          <div class="footer-col footer-col-logo">
            <img src="{{ asset('logo.svg') }}" width="180" height="48" alt="" class="footer-logo" decoding="async">
          </div>
          <div class="footer-col">
            <p>+39 329 123 8688</p>
          </div>
          <div class="footer-col">
            <p>fernando.alves@libero.it</p>
          </div>
          <div class="footer-col">
            <p>Mantova, Italia</p>
          </div>
        </div>
        <p class="footer-copy">© <span id="footer-year"></span> Alves Drywall &amp; Pittura. Tutti i diritti riservati.</p>
      </div>
      <button type="button" class="scroll-top" id="scroll-top" aria-label="Torna su">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M18 15l-6-6-6 6"/>
        </svg>
      </button>
    </div>
  </footer>

  <div class="lightbox" id="lightbox" onclick="closeImg()">
    <img id="lightImg" alt="Imagem ampliada">
  </div>

  <a href="https://wa.me/393291238688" target="_blank" rel="noopener noreferrer" class="whatsapp-float" aria-label="Apri WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor" aria-hidden="true">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
    </svg>
  </a>
</body>
</html>
