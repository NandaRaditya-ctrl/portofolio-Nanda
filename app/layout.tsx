import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";
import Navbar from "./components/Navbar";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Nanda | Web Developer Portfolio",
  description: "Portofolio Nanda - Web Developer & UI Enthusiast. Membangun pengalaman web yang modern dan responsif.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id" className="scroll-smooth">
      <body
        className={`${geistSans.variable} ${geistMono.variable} antialiased selection:bg-indigo-500/30 selection:text-indigo-200`}
      >
        <Navbar />
        {children}
        <footer className="py-8 text-center text-zinc-600 text-sm">
          © {new Date().getFullYear()} Nanda. Built with Next.js & Tailwind.
        </footer>
      </body>
    </html>
  );
}
