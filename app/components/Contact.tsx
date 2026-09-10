"use client";
import { motion } from "framer-motion";
import { Github } from "lucide-react";
export default function Contact() { return <section id="contact" className="py-24 border-t border-zinc-900"><h3 className="text-3xl font-bold text-white mb-4">Terhubung di GitHub</h3><p className="text-zinc-400 mb-6">Lihat source code dan perkembangan proyek saya.</p><motion.a whileHover={{ y: -3 }} href="https://github.com/NandaRaditya-ctrl" className="inline-flex items-center gap-3 text-indigo-300"><Github size={24} />NandaRaditya-ctrl</motion.a></section>; }
