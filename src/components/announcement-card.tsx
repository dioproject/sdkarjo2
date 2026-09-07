import Link from "next/link";
import { CalendarDays, ChevronRight } from "lucide-react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import type { Announcement } from "@/lib/types";

const categoryColor: Record<Announcement["category"], string> = {
  Akademik: "bg-primary/10 text-primary",
  Kegiatan: "bg-emerald-700/10 text-emerald-800",
  Prestasi: "bg-accent/20 text-amber-900",
  Informasi: "bg-muted text-muted-foreground"
};

export function AnnouncementCard({ announcement }: { announcement: Announcement }) {
  const date = new Intl.DateTimeFormat("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric"
  }).format(new Date(announcement.publishedAt));

  return (
    <Card className="group h-full overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
      <CardHeader className="gap-4">
        <div className="flex items-center justify-between gap-3">
          <span className={`rounded-full px-3 py-1 text-xs font-semibold ${categoryColor[announcement.category]}`}>
            {announcement.category}
          </span>
          <span className="flex items-center gap-1.5 text-xs text-muted-foreground">
            <CalendarDays className="h-4 w-4" />
            {date}
          </span>
        </div>
        <CardTitle className="text-lg">{announcement.title}</CardTitle>
      </CardHeader>
      <CardContent className="space-y-5">
        <p className="line-clamp-3 text-sm leading-6 text-muted-foreground">{announcement.excerpt}</p>
        <Link
          href={`/pengumuman/${announcement.slug}`}
          className="inline-flex items-center gap-1 text-sm font-semibold text-primary"
        >
          Baca selengkapnya
          <ChevronRight className="h-4 w-4 transition group-hover:translate-x-1" />
        </Link>
      </CardContent>
    </Card>
  );
}
