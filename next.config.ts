import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  outputFileTracingExcludes: { "/*": ["./.demo-runtime/**/*"] },
  async rewrites() {
    const backend = process.env.DEMO_BACKEND_URL || (process.env.NODE_ENV === "development" ? "http://127.0.0.1:3118" : "");
    if (!backend) return [];
    return ["bulan-4", "bulan-5", "bulan-6", "bulan-7", "bulan-8", "bulan-9", "bulan-10", "bulan-11", "bulan-12", "inventaris", "booking", "pkl", "login", "register", "logout", "shared", "css", "js", "images", "build"].map(prefix => ({ source: `/${prefix}/:path*`, destination: `${backend}/${prefix}/:path*` }));
  },
  async redirects() {
    return [{ source: "/journey", destination: "/journey/index.html", permanent: false }];
  },
};

export default nextConfig;
