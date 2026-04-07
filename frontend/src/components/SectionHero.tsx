interface SectionHeroProps {
  title: string;
  subtitle: string;
  children?: React.ReactNode;
}

export default function SectionHero({ title, subtitle, children }: SectionHeroProps) {
  return (
    <section className="bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white py-20 px-4">
      <div className="max-w-4xl mx-auto text-center">
        <h1 className="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{title}</h1>
        <p className="text-lg md:text-xl text-primary-200 max-w-2xl mx-auto leading-relaxed">{subtitle}</p>
        {children && <div className="mt-8">{children}</div>}
      </div>
    </section>
  );
}
