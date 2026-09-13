import Hero from "./components/Hero";
import Projects from "./components/Projects";
import Skills from "./components/Skills";
import Contact from "./components/Contact";
import BusinessDemos from "./components/BusinessDemos";
import Journey from "./components/Journey";

export default function Home() {
  return (
    <main className="max-w-4xl mx-auto px-6 sm:px-12 pt-20">
      <Hero />
      <BusinessDemos />
      <Journey />
      <Projects />
      <Skills />
      <Contact />
    </main>
  );
}
