import { schoolProfile } from "@/lib/dummy-data";
import { prisma } from "@/lib/prisma";

async function getTeachers() {
  try {
    return await prisma.teacher.findMany({ orderBy: { order: 'asc' } });
  } catch {
    return [];
  }
}

export default async function ProfilePage() {
  const teachers = await getTeachers();
  return (
    <main className="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
      <p className="text-sm font-semibold uppercase tracking-[0.16em] text-primary">Profil Sekolah</p>
      <h1 className="mt-3 text-4xl font-bold">{schoolProfile.name}</h1>
      <section className="mt-8 rounded-lg border bg-card p-6">
        <h2 className="text-2xl font-semibold">Sejarah Singkat</h2>
        <p className="mt-4 leading-7 text-muted-foreground">
          {schoolProfile.name} berkembang sebagai sekolah dasar negeri yang dekat dengan masyarakat, menjaga budaya
          belajar yang tertib, dan memberi ruang bagi murid untuk tumbuh percaya diri melalui akademik, seni, olahraga,
          serta kepedulian lingkungan.
        </p>
      </section>
      <section className="mt-8">
        <h2 className="text-2xl font-semibold">Struktur dan Guru</h2>
        <div className="mt-5 grid gap-4 sm:grid-cols-3">
          {teachers.map((teacher) => (
            <div key={teacher.name} className="rounded-lg border bg-card p-5">
              <h3 className="font-semibold">{teacher.name}</h3>
              <p className="mt-1 text-sm text-muted-foreground">{teacher.role}</p>
            </div>
          ))}
        </div>
      </section>
    </main>
  );
}
