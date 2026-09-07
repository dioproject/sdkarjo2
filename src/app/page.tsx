import Image from "next/image";
import Link from "next/link";
import { Award } from "lucide-react";
import { AnnouncementCard } from "@/components/announcement-card";
import { StatIcon } from "@/components/stat-icon";
import { Button } from "@/components/ui/button";
import { schoolProfile } from "@/lib/dummy-data";
import { prisma } from "@/lib/prisma";
import type { Announcement } from "@/lib/types";

async function getLatestAnnouncements() {
  try {
    const data = await prisma.announcement.findMany({
      where: { isPublished: true },
      orderBy: { publishedAt: 'desc' },
      take: 3,
    });
    return data.map((a): Announcement => ({
      id: a.id,
      title: a.title,
      slug: a.slug,
      excerpt: a.excerpt,
      content: typeof a.content === 'string' ? a.content : JSON.stringify(a.content),
      category: a.category as Announcement["category"],
      publishedAt: a.publishedAt.toISOString(),
      isPublished: a.isPublished,
    }));
  } catch {
    return [];
  }
}

async function getTeachers() {
  try {
    return await prisma.teacher.findMany({ orderBy: { order: 'asc' } });
  } catch {
    return [];
  }
}

async function getSiteConfig() {
  try {
    return await prisma.siteConfig.findUnique({ where: { id: "main" } });
  } catch {
    return null;
  }
}

async function getAchievements() {
  try {
    return await prisma.achievement.findMany({ orderBy: { createdAt: 'desc' }, take: 3 });
  } catch {
    return [];
  }
}

async function getStats() {
  try {
    return await prisma.stat.findMany();
  } catch {
    return [];
  }
}

export default async function HomePage() {
  const announcements = await getLatestAnnouncements();
  const teachers = await getTeachers();
  const config = await getSiteConfig();
  const achievements = await getAchievements();
  const stats = await getStats();

  const visi = config?.visi || schoolProfile.vision;
  const misi = (config?.misi as string[])?.length ? (config.misi as string[]) : schoolProfile.missions;
  const achievementList = achievements.length ? achievements.map(a => a.title) : schoolProfile.achievements;

  return (
    <main>
      <section className="relative overflow-hidden bg-primary text-primary-foreground">
        <div className="absolute inset-0">
          <Image
            src="https://images.unsplash.com/photo-1580582932707-520aed937b7b"
            alt="Suasana ruang kelas sekolah dasar"
            fill
            priority
            className="object-cover opacity-28"
          />
        </div>
        <div className="relative mx-auto grid min-h-[620px] max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
          <div className="max-w-3xl">
            <p className="mb-4 text-sm font-semibold uppercase tracking-[0.16em] text-accent">
              Sekolah Dasar Negeri
            </p>
            <h1 className="text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">{schoolProfile.name}</h1>
            <p className="mt-6 max-w-2xl text-lg leading-8 text-primary-foreground/88">{schoolProfile.tagline}</p>
            <div className="mt-8 flex flex-wrap gap-3">
              <Button asChild variant="accent">
                <Link href="/pengumuman">Lihat Pengumuman</Link>
              </Button>
              <Button asChild variant="outline" className="border-primary-foreground/40 bg-primary-foreground/10 text-primary-foreground hover:bg-primary-foreground/18">
                <Link href="/profil">Profil Sekolah</Link>
              </Button>
            </div>
          </div>
          <div className="grid gap-4 rounded-lg border border-primary-foreground/20 bg-primary-foreground/10 p-5 backdrop-blur">
            {achievementList.map((achievement) => (
              <div key={achievement} className="flex items-start gap-3">
                <Award className="mt-1 h-5 w-5 flex-none text-accent" />
                <p className="text-sm leading-6 text-primary-foreground/90">{achievement}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
          <div>
            <p className="text-sm font-semibold uppercase tracking-[0.16em] text-primary">Visi dan Misi</p>
            <h2 className="mt-3 text-3xl font-bold">Pendidikan yang hangat, tertib, dan berprestasi.</h2>
            <p className="mt-5 leading-7 text-muted-foreground">{visi}</p>
          </div>
          <div className="grid gap-4 sm:grid-cols-2">
            {misi.map((mission, index) => (
              <div key={index} className="rounded-lg border bg-card p-5">
                <span className="mb-4 flex h-10 w-10 items-center justify-center rounded-md bg-secondary font-bold text-secondary-foreground">
                  {index + 1}
                </span>
                <p className="text-sm leading-6 text-muted-foreground">{mission}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="border-y bg-white">
        <div className="mx-auto grid max-w-7xl gap-6 px-4 py-12 sm:grid-cols-3 sm:px-6 lg:px-8">
          {(stats.length ? stats : [
            { id: "guru", label: "Guru dan tendik", value: "24+", icon: "UsersRound" },
            { id: "literasi", label: "Program literasi aktif", value: "12", icon: "BookOpenCheck" },
            { id: "ruangan", label: "Jumlah ruangan", value: "8", icon: "DoorOpen" },
          ]).slice(0, 3).map((item) => (
            <div key={item.id} className="flex items-center gap-4">
              <span className="flex h-12 w-12 items-center justify-center rounded-md bg-primary text-primary-foreground">
                <StatIcon name={item.icon} className="h-5 w-5" />
              </span>
              <div>
                <p className="text-2xl font-bold">{item.value}</p>
                <p className="text-sm text-muted-foreground">{item.label}</p>
              </div>
            </div>
          ))}
        </div>
      </section>

      <section className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="mb-8 flex items-end justify-between gap-6">
          <div>
            <p className="text-sm font-semibold uppercase tracking-[0.16em] text-primary">Informasi Terbaru</p>
            <h2 className="mt-3 text-3xl font-bold">Pengumuman sekolah</h2>
          </div>
          <Button asChild variant="outline" className="hidden sm:inline-flex">
            <Link href="/pengumuman">Semua pengumuman</Link>
          </Button>
        </div>
        {announcements.length === 0 ? (
          <p className="text-muted-foreground">Belum ada pengumuman.</p>
        ) : (
          <div className="grid gap-5 md:grid-cols-3">
            {announcements.map((announcement) => (
              <AnnouncementCard key={announcement.id} announcement={announcement} />
            ))}
          </div>
        )}
      </section>

      <section className="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div className="mb-8">
          <p className="text-sm font-semibold uppercase tracking-[0.16em] text-primary">Profil</p>
          <h2 className="mt-3 text-3xl font-bold">Pendidik pilihan</h2>
        </div>
        {teachers.length === 0 ? (
          <p className="text-muted-foreground">Belum ada data guru.</p>
        ) : (
          <div className="grid gap-5 md:grid-cols-3">
            {teachers.map((teacher) => (
              <article key={teacher.id} className="overflow-hidden rounded-lg border bg-card">
                {teacher.photo && (
                  <div className="relative aspect-[4/3]">
                    <Image src={teacher.photo} alt={teacher.name} fill className="object-cover" />
                  </div>
                )}
                <div className="p-5">
                  <h3 className="font-semibold">{teacher.name}</h3>
                  <p className="mt-1 text-sm text-muted-foreground">{teacher.role}</p>
                </div>
              </article>
            ))}
          </div>
        )}
      </section>
    </main>
  );
}
