"use client";

import { motion } from "framer-motion";

const skills = [
  "JavaScript", "TypeScript", "React", "Next.js",
  "Tailwind CSS", "Node.js", "Git", "Figma",
  "Framer Motion", "PostgreSQL", "Prisma"
];

export default function Skills() {
  return (
    <section id="skills" className="py-24">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.5 }}
      >
        <h3 className="text-3xl font-bold text-white mb-10 flex items-center gap-4">
          <span className="w-12 h-[2px] bg-cyan-500 rounded-full"></span>
          Skills & Technologies
        </h3>
        
        <div className="flex flex-wrap gap-3">
          {skills.map((skill, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, scale: 0.9 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.05 }}
              whileHover={{ scale: 1.05, borderColor: "rgba(165, 180, 252, 0.5)" }}
              className="px-6 py-3 rounded-xl bg-zinc-900/40 border border-zinc-800 text-zinc-300 font-medium cursor-default transition-colors hover:text-white"
            >
              {skill}
            </motion.div>
          ))}
        </div>
      </motion.div>
    </section>
  );
}
