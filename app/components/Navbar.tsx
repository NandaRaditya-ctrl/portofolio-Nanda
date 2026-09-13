"use client";

import { motion } from "framer-motion";
import Link from "next/link";
import { usePathname } from "next/navigation";

const links = [
  { name: "Demo", href: "/#business-demos" },
  { name: "Projects", href: "/#projects" },
  { name: "Contact", href: "/#contact" },
];

export default function Navbar() {
  const pathname = usePathname();
  if (pathname.startsWith("/demo/")) return null;
  return (
    <motion.nav
      initial={{ y: -100, opacity: 0 }}
      animate={{ y: 0, opacity: 1 }}
      className="fixed top-0 left-0 right-0 z-50 flex justify-center pt-6 px-4"
    >
      <div className="flex items-center gap-6 px-6 py-3 bg-zinc-900/60 backdrop-blur-md rounded-full border border-zinc-800/80 shadow-lg shadow-black/20">
        <Link href="/" className="font-bold text-lg text-white tracking-tighter hover:text-indigo-400 transition-colors">
          N.
        </Link>
        
        <div className="w-px h-4 bg-zinc-800" />
        
        <div className="flex items-center gap-6">
          {links.map((link) => (
            <Link
              key={link.name}
              href={link.href}
              className="text-sm font-medium text-zinc-400 hover:text-white transition-colors"
            >
              {link.name}
            </Link>
          ))}
        </div>
      </div>
    </motion.nav>
  );
}
