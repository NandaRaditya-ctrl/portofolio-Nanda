import type { Metadata } from "next";
import { notFound } from "next/navigation";
import DemoSite from "./site";

const titles: Record<string, string> = { sejuk: "Sejuk — Demo Website Servis AC", saji: "Saji & Cerita — Demo Website Katering", ruang: "Ruang Karya — Demo Company Profile" };
export function generateStaticParams() { return Object.keys(titles).map(slug => ({ slug })); }
export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }): Promise<Metadata> {
  const { slug } = await params;
  return { title: titles[slug] || "Demo tidak ditemukan", description: "Proyek konsep portofolio Nanda. Merek dan harga fiktif; interaksi simulasi tanpa pengiriman data.", robots: { index: false, follow: true } };
}
export default async function Page({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  if (!Object.hasOwn(titles, slug)) notFound();
  return <DemoSite kind={slug as "sejuk" | "saji" | "ruang"} />;
}
