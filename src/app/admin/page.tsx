import { LogOut } from "lucide-react";
import { logoutAdmin } from "@/app/admin/actions";
import { Button } from "@/components/ui/button";
import { schoolProfile } from "@/lib/dummy-data";
import { prisma } from "@/lib/prisma";
import { getSession } from "@/lib/auth";

async function getCounts() {
  try {
    const [announcements, teachers, gallery] = await Promise.all([
      prisma.announcement.count(),
      prisma.teacher.count(),
      prisma.gallery.count(),
    ]);
    return { announcements, teachers, gallery };
  } catch {
    return { announcements: 0, teachers: 0, gallery: 0 };
  }
}

export default async function AdminPage() {
  const session = await getSession();
  const counts = await getCounts();

  return (
    <main className="px-6 py-12">
      <div className="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <h1 className="text-3xl font-bold">Dashboard</h1>
          <p className="mt-2 text-muted-foreground">Kelola informasi resmi {schoolProfile.name}. Login sebagai {session?.email}.</p>
        </div>
        <form action={logoutAdmin}>
          <Button variant="outline">
            <LogOut className="h-4 w-4" />
            Keluar
          </Button>
        </form>
      </div>
      <div className="grid gap-4 sm:grid-cols-3">
        <div className="rounded-lg border bg-card p-5">
          <p className="text-2xl font-bold">{counts.announcements}</p>
          <p className="text-sm text-muted-foreground">Pengumuman</p>
        </div>
        <div className="rounded-lg border bg-card p-5">
          <p className="text-2xl font-bold">{counts.teachers}</p>
          <p className="text-sm text-muted-foreground">Guru & Tendik</p>
        </div>
        <div className="rounded-lg border bg-card p-5">
          <p className="text-2xl font-bold">{counts.gallery}</p>
          <p className="text-sm text-muted-foreground">Foto Galeri</p>
        </div>
      </div>
    </main>
  );
}
