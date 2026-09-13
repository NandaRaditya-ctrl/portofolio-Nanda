document.addEventListener('DOMContentLoaded',()=>{
    const match=location.pathname.match(/bulan-(\d+)/);
    let month=match?Number(match[1]):location.pathname.startsWith('/inventaris')?9:location.pathname.startsWith('/booking')?10:location.pathname.startsWith('/pkl')?11:0;
    if(!month)return;
    const links=['/journey/bulan-1/index.html','/journey/bulan-2/index.html','/journey/bulan-3/index.html'];
    const labels=['HTML Portofolio','CSS Sekolah','To-do List','Buku Tamu','Perpustakaan','Bootstrap Library','Kasir POS','Laravel Inventaris','Tailwind Dashboard','Booking Lapangan','PKLFinder','Dashboard PKL'];
    const dock=document.createElement('aside');dock.className='journey-dock';dock.setAttribute('aria-label','Navigasi proyek bulanan');
    const home=document.createElement('a');home.href='/journey/index.html#timeline';home.textContent='← Portofolio';
    const label=document.createElement('label');label.htmlFor='journey-month';label.textContent='Pilih proyek';label.className='dock-label';
    const select=document.createElement('select');select.id='journey-month';select.setAttribute('aria-label','Pilih proyek bulanan');
    links.forEach((url,i)=>{const o=document.createElement('option');o.value=url;o.textContent=`${i+1}. ${labels[i]}`;o.selected=i===month-1;select.append(o)});
    const go=document.createElement('button');go.textContent='Buka';go.type='button';go.addEventListener('click',()=>location.assign(select.value));
    dock.append(home,label,select,go);document.body.append(dock);
    document.querySelectorAll('a[target="_blank"]').forEach(a=>a.rel='noopener');
    const menu=document.querySelector('.menu-toggle');const nav=document.querySelector('.navbar .nav-links');
    if(menu&&nav){menu.addEventListener('click',()=>{const open=menu.getAttribute('aria-expanded')!=='true';menu.setAttribute('aria-expanded',String(open));nav.classList.toggle('mobile-open',open)});nav.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{menu.setAttribute('aria-expanded','false');nav.classList.remove('mobile-open')}));}
    document.querySelectorAll('form[data-demo-form]').forEach(form=>{form.addEventListener('submit',event=>{event.preventDefault();if(!form.reportValidity())return;let notice=form.querySelector('[role="status"]');if(!notice){notice=document.createElement('p');notice.className='journey-notice';notice.setAttribute('role','status');form.append(notice)}notice.textContent='Validasi berhasil. Ini formulir latihan; pesan tidak dikirim ke pihak lain.';});});
});
