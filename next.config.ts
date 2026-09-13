import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  async redirects() {
    return [{ source: "/journey", destination: "/journey/index.html", permanent: false }];
  },
};

export default nextConfig;
