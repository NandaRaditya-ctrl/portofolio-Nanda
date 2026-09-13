import { ArrowUpRight, Snowflake, Utensils, Building2 } from "lucide-react";

const demos = [
  { slug: "sejuk", name: "Sejuk.", type: "WEBSITE JASA SERVIS AC", title: "Dari keluhan ke estimasi layanan.", description: "Pemilihan layanan, perhitungan biaya berdasarkan jumlah unit, dan ringkasan permintaan yang bisa disalin.", icon: Snowflake, color: "from-cyan-950 to-slate-950", accent: "text-cyan-300", tags: ["Kalkulator biaya", "Alur pemesanan"] },
  { slug: "saji", name: "Saji & Cerita", type: "WEBSITE KATERING", title: "Paket acara, lebih mudah direncanakan.", description: "Katalog paket menu dan simulasi anggaran berdasarkan jumlah porsi untuk membantu perencanaan acara.", icon: Utensils, color: "from-orange-950 to-stone-950", accent: "text-orange-300", tags: ["Pilihan paket", "Estimasi anggaran"] },
  { slug: "ruang", name: "RUANG / KARYA", type: "COMPANY PROFILE KONTRAKTOR", title: "Keahlian yang terlihat, brief yang terarah.", description: "Galeri konsep dengan filter kategori dan formulir brief proyek dengan validasi serta ringkasan kebutuhan.", icon: Building2, color: "from-lime-950 to-zinc-950", accent: "text-lime-300", tags: ["Filter proyek", "Formulir brief"] },
];

export default function BusinessDemos() {
  return <section id="business-demos" className="py-20 border-b border-zinc-800">
    <p className="text-indigo-300 text-xs font-semibold tracking-[.2em] mb-4">WEBSITE UNTUK BISNIS</p>
    <h2 className="text-3xl sm:text-4xl font-bold tracking-tight mb-4">Lihat seperti apa website<br />untuk usaha Anda.</h2>
    <p className="text-zinc-400 max-w-2xl leading-relaxed mb-10">Tiga eksplorasi website dengan alur yang bisa dicoba langsung. Seluruh merek, proyek, dan harga di bagian ini adalah contoh fiktif untuk demonstrasi, bukan pekerjaan klien.</p>
    <div className="grid gap-6">
      {demos.map(({ icon: Icon, ...demo }, index) => <a key={demo.slug} href={`/demo/${demo.slug}`} data-preview-title={demo.name} className="group grid md:grid-cols-[.9fr_1.1fr] rounded-2xl overflow-hidden border border-zinc-800 hover:border-zinc-500 transition-colors focus-visible:outline-2 focus-visible:outline-indigo-300">
        <div className={`bg-gradient-to-br ${demo.color} relative min-h-52 p-8 flex flex-col justify-between`}>
          <div className="flex justify-between items-center"><span className="text-xs text-white/70 tracking-widest">KONSEP / 0{index + 1}</span><Icon className={demo.accent} size={30} /></div>
          <span className={`text-4xl font-bold tracking-tight ${demo.accent}`}>{demo.name}</span>
          <span className="text-[10px] text-white/70 tracking-[.2em]">{demo.type}</span>
        </div>
        <div className="p-7 sm:p-8 bg-zinc-900/40"><h3 className="text-xl font-semibold mb-3">{demo.title}</h3><p className="text-zinc-400 text-sm leading-relaxed">{demo.description}</p><div className="flex flex-wrap gap-2 my-5">{demo.tags.map(tag => <span key={tag} className="text-xs px-3 py-1 border border-zinc-700 rounded-full text-zinc-300">{tag}</span>)}</div><span className="inline-flex gap-2 items-center text-sm text-indigo-300">Buka demo interaktif <ArrowUpRight size={17} /></span></div>
      </a>)}
    </div>
  </section>;
}
