"use client";

import Link from "next/link";
import { useState, type FormEvent } from "react";
import { ArrowRight, ArrowUpLeft, Snowflake, Utensils, Building2, Check, Copy } from "lucide-react";
import styles from "./site.module.css";

type Kind = "sejuk" | "saji" | "ruang";
const money = (n: number) => new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 }).format(n);
const services = [{ name: "Cuci AC", price: 85000, detail: "Pembersihan unit indoor dan outdoor." }, { name: "Pemeriksaan AC", price: 100000, detail: "Pemeriksaan awal untuk keluhan AC." }, { name: "Bongkar pasang", price: 350000, detail: "Simulasi jasa pemindahan satu unit." }];
const meals = [{ name: "Paket Harian", price: 28000, detail: "Nasi, ayam kecap, tumis sayur, dan buah." }, { name: "Paket Berbagi", price: 38000, detail: "Nasi, ayam panggang, tahu rempah, sayur, dan buah." }, { name: "Paket Perayaan", price: 55000, detail: "Nasi, rendang, ayam suwir, sayuran, dan dessert." }];
const projects = [{ name: "Rumah Sudut", category: "Hunian", area: "Konsep hunian / 120 m²", tone: "sand" }, { name: "Studio Terbuka", category: "Komersial", area: "Konsep ruang kerja / 85 m²", tone: "sage" }, { name: "Kedai Halaman", category: "Komersial", area: "Konsep kedai / 65 m²", tone: "clay" }];
const content = {
  sejuk: { brand: "Sejuk.", eyebrow: "PERAWATAN AC · SURABAYA", headline: "Ruangan nyaman.\nHari lebih tenang.", desc: "Rencanakan perawatan AC tanpa menebak biaya. Pilih layanan, hitung kebutuhan, dan susun permintaan Anda dalam satu tempat.", cta: "Hitung estimasi", section: "Perawatan sesuai kebutuhan.", sub: "Mulai dari layanan yang relevan dengan keluhan Anda.", form: "Rencanakan layanan Anda", note: "Harga ilustrasi, bukan penawaran nyata. Sparepart dan material tambahan belum termasuk.", icon: Snowflake },
  saji: { brand: "Saji & Cerita", eyebrow: "KATERING UNTUK MOMEN BERSAMA", headline: "Rasa rumahan.\nCerita berkesan.", desc: "Dari makan siang tim hingga perayaan kecil, mulai rencana jamuan Anda dari menu dan anggaran yang mudah dipahami.", cta: "Susun paket acara", section: "Menu untuk setiap cerita.", sub: "Tiga pilihan paket contoh. Sesuaikan jumlah porsi dengan rencana acara.", form: "Rencanakan jamuan Anda", note: "Harga dan menu ilustrasi. Pengiriman, peralatan, dan kebutuhan diet khusus belum termasuk.", icon: Utensils },
  ruang: { brand: "RUANG / KARYA", eyebrow: "ARSITEKTUR · INTERIOR · RENOVASI", headline: "Ruang yang hidup.\nDetail yang berarti.", desc: "Eksplorasi desain untuk cara hidup dan bekerja yang lebih baik. Temukan arah visual, lalu ceritakan ruang yang ingin Anda wujudkan.", cta: "Ceritakan proyek", section: "Setiap ruang, sebuah kemungkinan.", sub: "Studi visual konseptual untuk hunian dan ruang komersial. Bukan dokumentasi proyek terbangun.", form: "Mulai dari sebuah cerita", note: "Formulir simulasi. Brief hanya ditampilkan di perangkat Anda dan tidak dikirim ke server.", icon: Building2 },
};

export default function DemoSite({ kind }: { kind: Kind }) {
  const c = content[kind];
  const Icon = c.icon;
  const choices = kind === "saji" ? meals : services;
  const [selected, setSelected] = useState(0);
  const [quantity, setQuantity] = useState(kind === "saji" ? "20" : "1");
  const [filter, setFilter] = useState("Semua");
  const [summary, setSummary] = useState("");
  const [copyMessage, setCopyMessage] = useState("");
  const [error, setError] = useState("");
  const min = kind === "saji" ? 20 : 1;
  const max = kind === "saji" ? 1000 : 50;
  const count = Number(quantity);
  const valid = Number.isInteger(count) && count >= min && count <= max;
  function reset() { setSummary(""); setCopyMessage(""); setError(""); }
  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    const location = String(data.get("location") || "").trim();
    if (!location) { setError("Isi lokasi dengan nama wilayah yang valid."); return; }
    if (kind === "ruang") {
      const brief = String(data.get("brief") || "").trim();
      if (brief.length < 15) { setError("Jelaskan kebutuhan proyek minimal 15 karakter."); return; }
      setSummary(`SIMULASI BRIEF — ${c.brand}\nJenis: ${data.get("type")}\nLokasi: ${location}\nKebutuhan: ${brief}`);
    } else {
      if (!valid) { setError(`Jumlah harus bilangan bulat antara ${min} dan ${max}.`); return; }
      setSummary(`SIMULASI PERMINTAAN — ${c.brand}\nPilihan: ${choices[selected].name}\nJumlah: ${count} ${kind === "saji" ? "porsi" : "unit"}\nLokasi: ${location}\nEstimasi contoh: ${money(count * choices[selected].price)}\n${c.note}`);
    }
    setError(""); setCopyMessage("");
  }
  async function copy() {
    try { await navigator.clipboard.writeText(summary); setCopyMessage("Ringkasan berhasil disalin."); }
    catch { setCopyMessage("Penyalinan tidak tersedia. Pilih dan salin teks ringkasan secara manual."); }
  }
  return <main className={`${styles.demo} ${styles[kind]}`}>
    <div className={styles.notice}><Link href="/#business-demos"><ArrowUpLeft size={15} /> Portofolio Nanda</Link><span>DEMO KONSEP · BUKAN USAHA AKTIF</span></div>
    <header className={styles.header}><Link href={`/demo/${kind}`} className={styles.brand}><Icon size={23} />{c.brand}</Link><a href="#pilihan">{kind === "ruang" ? "Eksplorasi proyek" : "Pilihan layanan"}</a><a className={styles.smallCta} href="#rencana">Mulai rencana <ArrowRight size={15} /></a></header>
    <section className={styles.hero}>
      <div><p className={styles.eyebrow}>{c.eyebrow}</p><h1>{c.headline.split("\n").map(line => <span key={line}>{line}</span>)}</h1><p className={styles.intro}>{c.desc}</p><a className={styles.button} href="#rencana">{c.cta}<ArrowRight size={18} /></a><div className={styles.heroNotes}><span><Check size={15} /> Pilihan yang jelas</span><span><Check size={15} /> Rencana sesuai kebutuhan</span></div></div>
      <div className={styles.visual} aria-hidden="true">
        <span className={styles.visualLabel}>DESIGN STUDY / {kind === "sejuk" ? "01" : kind === "saji" ? "02" : "03"}</span>
        {kind === "sejuk" ? <><div className={styles.orbit} /><div className={styles.ac}><span>sejuk<span className={styles.light} /></span><div className={styles.vent} /></div><div className={styles.visualCaption}>CARE FOR YOUR EVERYDAY<span>Udara nyaman, mulai dari perawatan.</span></div></> : kind === "saji" ? <><div className={styles.plate}><div className={styles.rice} /><div className={styles.food} /><div className={styles.greens} /></div><div className={styles.visualCaption}>MADE FOR SHARING<span>Momen sederhana, rasa istimewa.</span></div></> : <><div className={styles.building}><div /><div /><div /></div><div className={styles.visualCaption}>FORM MEETS EVERYDAY LIFE<span>Perspektif baru untuk ruang Anda.</span></div></>}
      </div>
    </section>
    <div className={styles.strip}><span>01 / PILIH KEBUTUHAN</span><span>02 / SUSUN RENCANA</span><span>03 / LIHAT RINGKASAN</span></div>
    <section id="pilihan" className={styles.section}><p className={styles.eyebrow}>{kind === "ruang" ? "PILIHAN KONSEP" : "PILIHAN LAYANAN"}</p><h2>{c.section}</h2><p className={styles.muted}>{c.sub}</p>
      {kind === "ruang" ? <><div className={styles.filters} aria-label="Filter kategori proyek">{["Semua", "Hunian", "Komersial"].map(item => <button type="button" key={item} aria-pressed={filter === item} onClick={() => setFilter(item)}>{item}</button>)}</div><div className={styles.cards}>{projects.filter(p => filter === "Semua" || p.category === filter).map(p => <article key={p.name} className={styles.project}><div className={`${styles.projectArt} ${styles[p.tone]}`} aria-hidden="true"><span /><i /></div><p>{p.area}</p><h3>{p.name}</h3><small>Studi konsep fiktif</small></article>)}</div></> : <div className={styles.cards}>{choices.map((item, i) => <button type="button" key={item.name} className={styles.card} aria-pressed={selected === i} onClick={() => { setSelected(i); reset(); }}><span className={styles.cardTop}>0{i + 1}<span>{selected === i ? "DIPILIH ✓" : "PILIH PAKET ↗"}</span></span><h3>{item.name}</h3><p>{item.detail}</p><strong>{money(item.price)}<small> / {kind === "saji" ? "porsi" : "unit"}</small></strong></button>)}</div>}
    </section>
    <section id="rencana" className={`${styles.section} ${styles.planner}`}><div><p className={styles.eyebrow}>COBA ALURNYA</p><h2>{c.form}</h2><p className={styles.muted}>{c.note}</p><div className={styles.annotation}><span>CATATAN PORTOFOLIO</span><p>{kind === "ruang" ? "Menampilkan layanan melalui studi visual, menyaring kategori proyek, dan mengubah kebutuhan awal menjadi brief terstruktur." : kind === "saji" ? "Membantu pengunjung membandingkan menu dan menghitung anggaran acara sebelum menghubungi penyedia katering." : "Membantu pengunjung memilih layanan dan memahami estimasi awal sebelum berkonsultasi dengan teknisi."}</p><small>Next.js · TypeScript · CSS responsif</small></div></div>
      <form className={styles.form} onSubmit={submit} onChange={reset}>
        {kind === "ruang" ? <><label htmlFor="type">Jenis proyek</label><select id="type" name="type"><option>Hunian</option><option>Komersial</option><option>Renovasi interior</option></select><label htmlFor="brief">Ceritakan kebutuhan ruang</label><textarea id="brief" name="brief" required minLength={15} maxLength={1000} placeholder="Contoh: Renovasi ruang kerja 60 m² dengan area diskusi dan penyimpanan." rows={4} /></> : <><label htmlFor="package">{kind === "saji" ? "Paket menu" : "Jenis layanan"}</label><select id="package" value={selected} onChange={e => setSelected(Number(e.target.value))}>{choices.map((item, i) => <option value={i} key={item.name}>{item.name}</option>)}</select><label htmlFor="quantity">Jumlah {kind === "saji" ? "porsi" : "unit AC"} ({min}–{max})</label><input id="quantity" type="number" min={min} max={max} step="1" required value={quantity} onChange={e => setQuantity(e.target.value)} /><div className={styles.total} aria-live="polite"><span>Estimasi contoh</span><strong>{valid ? money(count * choices[selected].price) : "Jumlah belum valid"}</strong></div></>}
        <label htmlFor="location">Wilayah {kind === "saji" ? "acara" : "layanan"}</label><input id="location" name="location" required maxLength={120} placeholder="Contoh: Rungkut, Surabaya" autoComplete="off" /><p className={styles.formNote}>Gunakan data contoh. Tidak ada pemesanan, pembayaran, atau pengiriman data.</p><button className={styles.button} type="submit">Buat ringkasan simulasi <ArrowRight size={17} /></button>
        {error && <p role="alert">{error}</p>}{summary && <div className={styles.result} role="status"><h3>Ringkasan siap</h3><pre>{summary}</pre><button type="button" onClick={copy}><Copy size={15} /> Salin ringkasan</button><p>{copyMessage}</p></div>}
      </form>
    </section>
    <footer className={styles.footer}><strong>{c.brand}</strong><p>Proyek konsep oleh Nanda. Seluruh merek, harga, dan proyek adalah ilustrasi.</p><Link href="/#business-demos">Lihat demo lainnya <ArrowRight size={16} /></Link></footer>
  </main>;
}
