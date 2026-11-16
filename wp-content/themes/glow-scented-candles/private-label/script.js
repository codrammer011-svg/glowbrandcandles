(() => {
  const menu = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  menu?.addEventListener('click', () => { const open = nav.classList.toggle('open'); menu.setAttribute('aria-expanded', String(open)); });
  nav?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { nav.classList.remove('open'); menu?.setAttribute('aria-expanded', 'false'); }));

  const lightbox = document.querySelector('#lightbox');
  const img = lightbox?.querySelector('img');
  const caption = lightbox?.querySelector('.lightbox-caption');
  let lastButton = null;
  const openCollection = (button) => {
    if (!lightbox || !img || !caption) return;
    lastButton = button;
    img.src = button.dataset.image;
    img.alt = button.dataset.caption || 'Full-size collection';
    caption.textContent = button.dataset.caption || '';
    lightbox.showModal();
  };
  document.querySelectorAll('.image-button').forEach(btn => btn.addEventListener('click', () => openCollection(btn)));
  lightbox?.querySelector('.lightbox-close')?.addEventListener('click', () => lightbox.close());
  lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) lightbox.close(); });
  lightbox?.addEventListener('close', () => { img.removeAttribute('src'); lastButton?.focus(); });
  document.querySelector('#expand-collections')?.addEventListener('click', () => { const first = document.querySelector('.image-button'); if (first) openCollection(first); });
})();
