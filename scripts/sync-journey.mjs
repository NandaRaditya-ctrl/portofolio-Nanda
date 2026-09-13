import { readFile, writeFile, mkdir } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

// Copy only explicitly listed public assets. Never copy .env, databases or .vercel.
const source = process.argv[2];
if (!source) throw new Error('Usage: node scripts/sync-journey.mjs <portofolio-v3-directory>');
const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const files = ['index.html', 'css/style.css', 'css/portfolio-interactive.css', 'js/portfolio-interactive.js', 'bulan-1/index.html', 'bulan-2/index.html', 'bulan-2/style.css', 'bulan-3/index.html', 'bulan-3/style.css', 'bulan-3/script.js'];
const repo = 'https://github.com/NandaRaditya-ctrl/portofolio-Nanda/tree/laravel-portfolio-v3';
for (const file of [...files, 'shared/projects.css', 'shared/projects.js']) {
  const original = path.join(source, file.startsWith('shared/') ? 'public' : 'vercel-portfolio', file);
  let text = await readFile(original, 'utf8');
  if (file === 'bulan-3/script.js') text = text.replace('(todo, index)', '(todo)');
  if (file === 'js/portfolio-interactive.js') text = text.replace('saved.has(id) ? saved.delete(id) : saved.add(id);', 'if (saved.has(id)) { saved.delete(id); } else { saved.add(id); }');
  if (file.endsWith('.html')) text = text.replaceAll('"/shared/', '"/journey/shared/');
  if (file === 'index.html') {
    text = text.replace('<div class="nav-links">', '<div class="nav-links"><a href="/" class="nav-btn">← Portofolio Utama</a><a href="/#business-demos" class="nav-btn">Demo UMKM</a>');
    text = text.replaceAll('href="css/', 'href="/journey/css/').replaceAll('src="js/', 'src="/journey/js/').replaceAll('href="bulan-', 'href="/journey/bulan-');
    text = text.replace(/<a href="#top" data-backend-project="true"[^>]*>.*?<\/a>/g, `<a href="${repo}" class="project-btn">Lihat source PHP/Laravel ↗</a>`);
    text = text.replace('Versi Vercel menampilkan portofolio interaktif.', 'DevJourney adalah bagian dari portofolio Nanda. Proyek HTML bulan 1–3 dapat dicoba langsung; proyek lainnya tersedia sebagai source code.');
    text = text.replaceAll('target="_blank"', 'target="_blank" rel="noopener noreferrer"');
  }
  if (file === 'shared/projects.js') {
    text = text.replace(/const links=\[.*?\];/, "const links=['/journey/bulan-1/index.html','/journey/bulan-2/index.html','/journey/bulan-3/index.html'];");
    text = text.replace("home.href='/#timeline'", "home.href='/journey/index.html#timeline'");
  }
  const destination = path.join(root, 'public/journey', file);
  await mkdir(path.dirname(destination), { recursive: true });
  await writeFile(destination, text);
}
console.log('Synced DevJourney and three frontend demos into public/journey.');
