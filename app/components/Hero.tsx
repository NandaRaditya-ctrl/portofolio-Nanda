"use client";

import { motion } from "framer-motion";
import { ArrowRight, Github } from "lucide-react";

export default function Hero() {
  return (
    <section className="py-20 flex flex-col items-start justify-center min-h-[85vh]">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
      >
        <h1 className="text-5xl md:text-7xl font-bold tracking-tight text-white mb-6">
          Halo, saya <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Nanda</span>
        </h1>
        <h2 className="text-2xl md:text-3xl font-medium text-zinc-300 mb-6 flex items-center gap-3">
          <span className="w-8 h-[2px] bg-zinc-700 inline-block"></span>
          Web Developer & UI Enthusiast
        </h2>
        <p className="text-lg text-zinc-400 max-w-2xl leading-relaxed mb-10">
          Saya menciptakan pengalaman web yang cepat, responsif, dan estetis. 
          Mengubah ide menjadi antarmuka yang elegan dan fungsional adalah passion saya.
        </p>
        
        <div className="flex flex-wrap gap-4">
          <motion.a
            href="#projects"
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="flex items-center gap-2 px-6 py-3 bg-white text-zinc-900 font-semibold rounded-full hover:bg-zinc-200 transition-colors"
          >
            Lihat Karya
            <ArrowRight size={18} />
          </motion.a>
          
          <motion.a
            href="https://github.com/NandaRaditya-ctrl" 
            target="_blank"
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="flex items-center gap-2 px-6 py-3 bg-zinc-900 border border-zinc-800 text-white font-semibold rounded-full hover:bg-zinc-800 transition-colors"
          >
            Lihat GitHub
            <Github size={18} />
          </motion.a>
        </div>
      </motion.div>
    </section>
  );
}
