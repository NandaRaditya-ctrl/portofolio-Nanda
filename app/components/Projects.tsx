"use client";

import { motion } from "framer-motion";
import { Github } from "lucide-react";

const projects = [
  {
    "title": "Nova Topup",
    "description": "Prototipe storefront top-up game dengan Next.js, TypeScript, Tailwind CSS, dan Framer Motion.",
    "tech": [
      "Next.js",
      "TypeScript",
      "Tailwind CSS"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/nova-topup-nextjs"
  },
  {
    "title": "Futsal Booking",
    "description": "Proyek pembelajaran reservasi lapangan futsal dengan panel admin, jadwal, dan riwayat booking.",
    "tech": [
      "PHP",
      "MySQL"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/futsal-booking-php"
  },
  {
    "title": "WashCL Laundry",
    "description": "Prototipe pemesanan laundry UMKM berbasis PHP dan MySQL dengan dashboard status pesanan.",
    "tech": [
      "PHP",
      "MySQL"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/washcl-laundry-php"
  },
  {
    "title": "SIMPAKA — Manifes Kereta Api",
    "description": "Latihan CRUD manifes penumpang kereta api dengan PHP, MySQL, pencarian, dan pagination.",
    "tech": [
      "PHP",
      "MySQL"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/simpaka-railway-manifest"
  },
  {
    "title": "InsightClass LMS",
    "description": "Prototipe LMS PHP untuk admin, guru, dan siswa: materi, tugas, ujian, dan laporan nilai.",
    "tech": [
      "PHP",
      "MySQL"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/insightclass-lms-php"
  },
  {
    "title": "Laravel Learning Lab",
    "description": "Kumpulan latihan Laravel untuk routing, controller, validasi, autentikasi, migration, dan seeder.",
    "tech": [
      "Laravel",
      "PHP"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/laravel-learning-lab"
  },
  {
    "title": "AI SaaS Landing Page",
    "description": "Eksplorasi landing page SaaS bertema AI dengan Next.js, animasi Framer Motion, dan Tailwind CSS.",
    "tech": [
      "Next.js",
      "TypeScript",
      "Tailwind CSS"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/ai-saas-landing-nextjs"
  },
  {
    "title": "Web Development Learning Path",
    "description": "Kumpulan latihan HTML, CSS, dan JavaScript: portofolio, landing page sekolah, dan to-do list.",
    "tech": [
      "HTML",
      "CSS",
      "JavaScript"
    ],
    "github": "https://github.com/NandaRaditya-ctrl/web-development-learning-path"
  }
];

export default function Projects() {
  return (
    <section id="projects" className="py-24">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.5 }}
      >
        <h3 className="text-3xl font-bold text-white mb-12 flex items-center gap-4">
          <span className="w-12 h-[2px] bg-indigo-500 rounded-full"></span>
          Featured Projects
        </h3>
        
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {projects.map((project, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              className="group flex flex-col p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800 hover:border-zinc-700 hover:bg-zinc-900/60 transition-all duration-300"
            >
              <div className="mb-4">
                 <div className="flex justify-between items-start mb-4">
                    <div className="p-3 bg-zinc-800/50 rounded-lg">
                      {/* Placeholder icon based on title? Or just generic folder */}
                      <div className="w-6 h-6 bg-gradient-to-tr from-indigo-500 to-cyan-500 rounded-md opacity-80"></div>
                    </div>
                    <div className="flex gap-2">
                       <a href={project.github} className="text-zinc-500 hover:text-white transition-colors"><Github size={20} /></a>
                    </div>
                 </div>
                 
                <h4 className="text-xl font-bold text-zinc-100 mb-2 group-hover:text-indigo-400 transition-colors">
                  {project.title}
                </h4>
                <p className="text-zinc-400 text-sm leading-relaxed mb-6 flex-grow">
                  {project.description}
                </p>
              </div>
              
              <div className="flex flex-wrap gap-2 mt-auto">
                {project.tech.map((t, i) => (
                  <span
                    key={i}
                    className="px-3 py-1 text-xs font-medium text-zinc-300 bg-zinc-800/50 rounded-full border border-zinc-700/50"
                  >
                    {t}
                  </span>
                ))}
              </div>
            </motion.div>
          ))}
        </div>
      </motion.div>
    </section>
  );
}
