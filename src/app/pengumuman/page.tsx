import { AnnouncementCard } from "@/components/announcement-card";
import { prisma } from "@/lib/prisma";
import type { Announcement } from "@/lib/types";

async function getPublishedAnnouncements() {
  try {
    const data = await prisma.announcement.findMany({
      where: { isPublished: true },
      orderBy: { publishedAt: 'desc' }
    });
    return data;
  } catch {
    return [];
  }
}

export default async function AnnouncementsPage() {
  const data = await getPublishedAnnouncements();

  const announcements: Announcement[] = data.map((a) => ({
    id: a.id,
    title: a.title,
    slug: a.slug,
    excerpt: a.excerpt,
    content: typeof a.content === 'string' ? a.content : JSON.stringify(a.content),
    category: a.category as Announcement["category"],
    publishedAt: a.publishedAt.toISOString(),
    isPublished: a.isPublished
  }));

  return (
    <main className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
      <div className="mb-8">
        <p className="text-sm font-semibold uppercase tracking-[0.16em] text-primary">Berita dan Informasi</p>
        <h1 className="mt-3 text-4xl font-bold">Pengumuman Sekolah</h1>
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
    </main>
  );
}
