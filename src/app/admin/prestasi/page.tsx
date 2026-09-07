import { createAchievement, deleteAchievement } from "@/app/admin/actions";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Trash2 } from "lucide-react";
import { prisma } from "@/lib/prisma";

async function getAchievements() {
  try {
    return await prisma.achievement.findMany({ orderBy: { createdAt: 'desc' } });
  } catch {
    return [];
  }
}

export default async function PrestasiPage() {
  const achievements = await getAchievements();

  return (
    <main className="max-w-2xl px-6 py-12">
      <h1 className="text-2xl font-bold">Kelola Prestasi</h1>
      <form action={createAchievement} className="mt-6 flex gap-3">
        <Input name="title" placeholder="Judul prestasi" required className="flex-1" />
        <Button type="submit">Tambah</Button>
      </form>
      <div className="mt-6 space-y-2">
        {achievements.length === 0 ? (
          <p className="text-sm text-muted-foreground">Belum ada data prestasi.</p>
        ) : (
          achievements.map((a) => (
            <div key={a.id} className="flex items-center justify-between rounded-md border p-3">
              <p className="text-sm">{a.title}</p>
              <form action={deleteAchievement}>
                <input type="hidden" name="id" value={a.id} />
                <Button type="submit" variant="ghost" size="icon">
                  <Trash2 className="h-4 w-4 text-red-600" />
                </Button>
              </form>
            </div>
          ))
        )}
      </div>
    </main>
  );
}
