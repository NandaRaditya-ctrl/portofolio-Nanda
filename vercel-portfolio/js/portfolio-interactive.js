document.addEventListener('DOMContentLoaded', () => {
    const items = [...document.querySelectorAll('.timeline-item')];
    const timeline = document.querySelector('.timeline');
    const search = document.querySelector('#project-search');
    const dialog = document.querySelector('#project-dialog');
    const read = (key, fallback) => { try { return JSON.parse(localStorage.getItem(key)) ?? fallback; } catch { return fallback; } };
    const write = (key, value) => { try { localStorage.setItem(key, JSON.stringify(value)); } catch { /* Browsing still works without storage. */ } };
    const storedSaved = read('journey:saved', []);
    const storedOpened = read('journey:opened', []);
    const saved = new Set(Array.isArray(storedSaved) ? storedSaved : []);
    const opened = new Set(Array.isArray(storedOpened) ? storedOpened : []);
    let filter = 'all';
    let activeProject = 0;
    let activeOpener;
    const projects = items.map((item, index) => {
        const card = item.querySelector('.timeline-content');
        const badge = item.querySelector('.month-badge');
        const id = String(index + 1);
        const title = item.querySelector('h3').textContent.trim();
        const links = [...card.querySelectorAll('a.project-btn')].map(link => ({href:link.getAttribute('href'), label:link.textContent.trim()}));
        const project = { item, card, id, title, links, month:badge.textContent.trim(), skills:[...item.querySelectorAll('.skills li')].map(li => li.textContent.trim()), description:item.querySelector('.target span').textContent.replace(/^Target:\s*/, ''), category:index < 3 ? 'frontend' : index < 7 ? 'php' : 'laravel' };
        const toolbar = document.createElement('div'); toolbar.className = 'card-toolbar';
        card.prepend(toolbar); toolbar.append(badge);
        const save = document.createElement('button'); save.type = 'button'; save.className = 'save-project';
        save.addEventListener('click', () => { saved.has(id) ? saved.delete(id) : saved.add(id); write('journey:saved', [...saved]); updateSaved(); applyFilters(); });
        toolbar.append(save); project.save = save;
        const footer = document.createElement('div'); footer.className = 'card-footer';
        const detail = document.createElement('button'); detail.type = 'button'; detail.className = 'project-btn detail-button'; detail.textContent = 'Detail proyek ↗'; detail.setAttribute('aria-label', 'Detail proyek ' + title);
        detail.addEventListener('click', () => { activeOpener = detail; showProject(index); dialog.showModal(); });
        const visited = document.createElement('span'); visited.className = 'visited-badge'; project.visited = visited;
        footer.append(detail, visited); card.append(footer);
        card.querySelectorAll('a.project-btn').forEach(link => { link.rel = 'noopener'; link.addEventListener('click', () => markOpened(id)); });
        item.id = 'proyek-' + id;
        return project;
    });
    function updateSaved() {
        projects.forEach(p => { const selected = saved.has(p.id); p.save.textContent = selected ? '♥' : '♡'; p.save.setAttribute('aria-pressed', String(selected)); p.save.setAttribute('aria-label', (selected ? 'Hapus simpanan ' : 'Simpan ') + p.title); });
    }
    function markOpened(id) { opened.add(id); write('journey:opened', [...opened]); updateOpened(); }
    function updateOpened() {
        const count = projects.filter(p => opened.has(p.id)).length;
        projects.forEach(p => { p.visited.textContent = opened.has(p.id) ? '✓ Dibuka' : ''; });
        document.querySelector('#explored-label').textContent = `${count} / ${projects.length} proyek dibuka`;
        document.querySelector('#explored-progress').value = count;
    }
    function applyFilters() {
        const query = search.value.trim().toLocaleLowerCase('id');
        let count = 0;
        projects.forEach(p => {
            const text = `${p.title} ${p.month} ${p.id === '11' ? 'bulan 11 bulan 12' : ''} ${p.skills.join(' ')} ${p.description}`.toLocaleLowerCase('id');
            const visible = (filter === 'all' || (filter === 'saved' ? saved.has(p.id) : p.category === filter)) && text.includes(query);
            p.item.hidden = !visible; if (visible) count++;
        });
        document.querySelector('#result-count').textContent = `${count} dari ${projects.length} tahap proyek`;
        document.querySelector('#no-projects').hidden = count !== 0;
        timeline.hidden = count === 0;
        updateScroll();
    }
    function showProject(index) {
        activeProject = index;
        const p = projects[index];
        document.querySelector('#dialog-month').textContent = p.month;
        document.querySelector('#dialog-title').textContent = p.title;
        document.querySelector('#dialog-description').textContent = p.description;
        document.querySelector('#dialog-position').textContent = `${index + 1} / ${projects.length}`;
        document.querySelector('#previous-project').disabled = index === 0;
        document.querySelector('#next-project').disabled = index === projects.length - 1;
        const skills = document.querySelector('#dialog-skills'); skills.replaceChildren();
        p.skills.forEach(skill => { const li = document.createElement('li'); li.textContent = skill; skills.append(li); });
        const links = document.querySelector('#dialog-links'); links.replaceChildren();
        p.links.forEach(data => { const link = document.createElement('a'); link.href = data.href; link.textContent = data.label; link.className = 'project-btn'; link.addEventListener('click', () => markOpened(p.id)); links.append(link); });
    }
    document.querySelector('#close-dialog').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', event => { const rect = dialog.getBoundingClientRect(); if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close(); });
    dialog.addEventListener('close', () => activeOpener?.focus());
    document.querySelector('#previous-project').addEventListener('click', () => { if (activeProject > 0) showProject(activeProject - 1); });
    document.querySelector('#next-project').addEventListener('click', () => { if (activeProject < projects.length - 1) showProject(activeProject + 1); });
    document.querySelectorAll('[data-filter]').forEach(button => button.addEventListener('click', () => { filter = button.dataset.filter; document.querySelectorAll('[data-filter]').forEach(b => b.setAttribute('aria-pressed', String(b === button))); applyFilters(); }));
    search.addEventListener('input', applyFilters);
    document.querySelector('#reset-filters').addEventListener('click', () => { search.value = ''; document.querySelector('[data-filter="all"]').click(); search.focus(); });
    function setView(view) { timeline.classList.toggle('grid-view', view === 'grid'); document.querySelectorAll('[data-view]').forEach(button => button.setAttribute('aria-pressed', String(button.dataset.view === view))); write('journey:view', view); }
    document.querySelectorAll('[data-view]').forEach(button => button.addEventListener('click', () => { setView(button.dataset.view); updateScroll(); }));
    const themeButton = document.querySelector('#theme-toggle');
    function setTheme(theme) { document.body.dataset.theme = theme; const light = theme === 'light'; themeButton.textContent = light ? '☾' : '☀'; themeButton.setAttribute('aria-label', light ? 'Aktifkan tema gelap' : 'Aktifkan tema terang'); write('journey:theme', theme); }
    themeButton.addEventListener('click', () => setTheme(document.body.dataset.theme === 'light' ? 'dark' : 'light'));
    function updateScroll() { const max = document.documentElement.scrollHeight - window.innerHeight; document.querySelector('#reading-progress').style.transform = `scaleX(${max > 0 ? Math.min(1, window.scrollY / max) : 0})`; document.querySelector('#back-to-top').hidden = window.scrollY < 500; }
    let scheduled = false;
    window.addEventListener('scroll', () => { if (!scheduled) { scheduled = true; requestAnimationFrame(() => { updateScroll(); scheduled = false; }); } }, {passive:true});
    window.addEventListener('resize', updateScroll);
    const motion = matchMedia('(prefers-reduced-motion: reduce)');
    const pointer = matchMedia('(hover: hover) and (pointer: fine)');
    projects.forEach(({card}) => { card.addEventListener('pointermove', event => { if (motion.matches || !pointer.matches) return; const box = card.getBoundingClientRect(); card.style.setProperty('--rx', `${(event.clientY - box.top - box.height / 2) / box.height * -3}deg`); card.style.setProperty('--ry', `${(event.clientX - box.left - box.width / 2) / box.width * 3}deg`); }); card.addEventListener('pointerleave', () => { card.style.removeProperty('--rx'); card.style.removeProperty('--ry'); }); });
    setTheme(read('journey:theme', 'dark') === 'light' ? 'light' : 'dark');
    setView(read('journey:view', 'timeline') === 'grid' ? 'grid' : 'timeline');
    updateSaved(); updateOpened(); applyFilters();
    document.querySelector('.hero-secondary').addEventListener('click', () => markOpened('11'));
    document.querySelector('#explorer').hidden = false; themeButton.hidden = false;
    window.addEventListener('pageshow', updateScroll);
});
