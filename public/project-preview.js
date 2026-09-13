(() => {
  if (window.top !== window) return;
  if (window.__portfolioPreview) return;
  window.__portfolioPreview = true;
  let dialog, frame, opener, timer, previousOverflow;
  const allowed = path => /^\/journey\/bulan-[123]\/index\.html$/.test(path) || /^\/demo\/(sejuk|saji|ruang)$/.test(path);
  function close() { dialog.close(); }
  function create() {
    dialog = document.createElement('dialog');
    dialog.className = 'portfolio-preview';
    dialog.setAttribute('aria-labelledby', 'portfolio-preview-title');
    dialog.innerHTML = '<header class="portfolio-preview-bar"><div><span>PREVIEW PROYEK</span><h2 id="portfolio-preview-title"></h2></div><button type="button" class="portfolio-preview-close">← Kembali ke portofolio</button></header><p class="portfolio-preview-status" role="status">Memuat proyek…</p><iframe title="Preview proyek" sandbox="allow-scripts allow-same-origin allow-forms" referrerpolicy="same-origin"></iframe>';
    document.body.append(dialog);
    frame = dialog.querySelector('iframe');
    dialog.querySelector('button').addEventListener('click', close);
    dialog.addEventListener('close', () => {
      clearTimeout(timer); frame.src = 'about:blank';
      document.body.style.overflow = previousOverflow;
      opener?.focus({ preventScroll: true });
    });
    frame.addEventListener('load', () => {
      if (!dialog.open || frame.getAttribute('src') === 'about:blank') return;
      clearTimeout(timer);
      dialog.querySelector('[role="status"]').hidden = true;
      // Same-origin demo navigation remains inside the frame; portfolio links close it.
      try {
        const doc = frame.contentDocument;
        doc.addEventListener('keydown', event => { if (event.key === 'Escape') { event.preventDefault(); close(); } });
        doc.addEventListener('click', event => {
          const link = event.target.closest('a[href]');
          if (!link) return;
          const url = new URL(link.href);
          if (url.origin === location.origin && ['/', '/journey/index.html'].includes(url.pathname)) {
            event.preventDefault(); close();
          }
        });
      } catch { /* The toolbar remains available if a frame becomes inaccessible. */ }
    });
  }
  document.addEventListener('click', event => {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    const link = event.target.closest('a[href]');
    if (!link) return;
    const url = new URL(link.href, location.href);
    if (url.origin !== location.origin || !allowed(url.pathname)) return;
    event.preventDefault();
    if (!dialog) create();
    const detail = link.closest('dialog');
    opener = detail ? document.querySelector(`#${detail.dataset.projectId} .detail-button`) || link : link;
    if (detail?.open) detail.close();
    const title = link.dataset.previewTitle || link.closest('.timeline-content')?.querySelector('h3')?.textContent || document.querySelector('#dialog-title')?.textContent || 'Demo interaktif';
    dialog.querySelector('h2').textContent = title;
    frame.title = `Demo ${title}`;
    const status = dialog.querySelector('[role="status"]'); status.textContent = 'Memuat proyek…'; status.hidden = false;
    previousOverflow = document.body.style.overflow; document.body.style.overflow = 'hidden';
    frame.src = url.href; dialog.showModal(); dialog.querySelector('button').focus();
    timer = setTimeout(() => { status.textContent = 'Proyek belum selesai dimuat. Anda dapat kembali dan mencoba lagi.'; }, 15000);
  });
})();
