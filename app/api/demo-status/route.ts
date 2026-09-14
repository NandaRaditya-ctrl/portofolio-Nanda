export const dynamic = "force-dynamic";
export async function GET() {
  const backend = process.env.DEMO_BACKEND_URL || (process.env.NODE_ENV === "development" ? "http://127.0.0.1:3118" : "");
  if (!backend) return Response.json({ available: false });
  try {
    const result = await fetch(`${backend}/up`, { signal: AbortSignal.timeout(4000), cache: "no-store" });
    return Response.json({ available: result.ok });
  } catch { return Response.json({ available: false }); }
}
