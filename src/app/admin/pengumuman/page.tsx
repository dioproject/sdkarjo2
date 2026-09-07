import { Trash2, Newspaper } from "lucide-react";
import { createAnnouncement, deleteAnnouncement } from "@/app/admin/actions";
import { TiptapEditor } from "@/components/admin/tiptap-editor";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { prisma } from "@/lib/prisma";

async function getAdminAnnouncements() {
  try {
    return await prisma.announcement.findMany({
      select: { id: true, title: true, category: true, isPublished: true, publishedAt: true },
      orderBy: { publishedAt: 'desc' },
    });
  } catch {
    return [];
  }
}

export default async function AdminPengumumanPage() {
  const announcements = await getAdminAnnouncements();

  return (
    <main className="px-6 py-12">
      <h1 className="mb-6 text-2xl font-bold">Kelola Pengumuman</h1>
      <div className="grid gap-6 lg:grid-cols-[1fr_0.6fr]">
        <Card>
          <CardHeader>
            <CardTitle>Posting Pengumuman Baru</CardTitle>
          </CardHeader>
          <CardContent>
            <form action={createAnnouncement} className="space-y-5">
              <div className="grid gap-2">
                <Label htmlFor="title">Judul</Label>
                <Input id="title" name="title" placeholder="Judul pengumuman" required />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="excerpt">Ringkasan</Label>
                <Textarea id="excerpt" name="excerpt" placeholder="Ringkasan singkat" required />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="category">Kategori</Label>
                <select id="category" name="category" className="h-10 rounded-md border bg-background px-3 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring">
                  <option>Informasi</option>
                  <option>Akademik</option>
                  <option>Kegiatan</option>
                  <option>Prestasi</option>
                </select>
              </div>
              <div className="grid gap-2">
                <Label>Isi Pengumuman</Label>
                <TiptapEditor />
              </div>
              <label className="flex items-center gap-2 text-sm">
                <input name="isPublished" type="checkbox" className="h-4 w-4 rounded border" defaultChecked />
                Publikasikan sekarang
              </label>
              <Button type="submit">
                <Newspaper className="h-4 w-4" />
                Simpan
              </Button>
            </form>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Daftar Pengumuman</CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            {announcements.length === 0 ? (
              <p className="text-sm text-muted-foreground">Belum ada pengumuman.</p>
            ) : (
              announcements.map((a) => (
                <div key={a.id} className="flex items-start justify-between gap-3 rounded-md border p-3">
                  <div>
                    <p className="text-sm font-semibold">{a.title}</p>
                    <p className="text-xs text-muted-foreground">{a.category}</p>
                  </div>
                  <form action={deleteAnnouncement}>
                    <input type="hidden" name="id" value={a.id} />
                    <Button type="submit" variant="ghost" size="icon">
                      <Trash2 className="h-4 w-4 text-red-600" />
                    </Button>
                  </form>
                </div>
              ))
            )}
          </CardContent>
        </Card>
      </div>
    </main>
  );
}
