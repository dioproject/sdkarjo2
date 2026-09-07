import { Trash2 } from "lucide-react";
import { upsertStat, deleteStat } from "@/app/admin/actions";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { prisma } from "@/lib/prisma";

const iconOptions = [
  "UsersRound", "BookOpenCheck", "MapPin", "School", "GraduationCap",
  "Building", "DoorOpen", "Trophy", "Heart", "Star",
];

async function getStats() {
  try {
    return await prisma.stat.findMany();
  } catch {
    return [];
  }
}

export default async function StatistikPage() {
  const stats = await getStats();

  return (
    <main className="max-w-2xl px-6 py-12">
      <h1 className="mb-6 text-2xl font-bold">Kelola Statistik Beranda</h1>
      <form action={upsertStat} className="mb-6 flex flex-wrap items-end gap-3">
        <div className="grid gap-1">
          <Label htmlFor="id">ID (unik)</Label>
          <Input id="id" name="id" placeholder="guru / literasi / ruangan" required className="w-36" />
        </div>
        <div className="grid gap-1">
          <Label htmlFor="label">Label</Label>
          <Input id="label" name="label" placeholder="Guru dan tendik" required className="w-44" />
        </div>
        <div className="grid gap-1">
          <Label htmlFor="value">Nilai</Label>
          <Input id="value" name="value" placeholder="24+" required className="w-24" />
        </div>
        <div className="grid gap-1">
          <Label htmlFor="icon">Icon</Label>
          <select id="icon" name="icon" className="h-10 rounded-md border bg-background px-3 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring">
            {iconOptions.map((ic) => (
              <option key={ic} value={ic}>{ic}</option>
            ))}
          </select>
        </div>
        <Button type="submit">Simpan</Button>
      </form>
      {stats.length === 0 ? (
        <p className="text-sm text-muted-foreground">Belum ada data statistik.</p>
      ) : (
        <div className="space-y-2">
          {stats.map((s) => (
            <div key={s.id} className="flex items-center justify-between rounded-md border p-3">
              <div>
                <p className="text-sm font-semibold">{s.label}</p>
                <p className="text-xs text-muted-foreground">ID: {s.id} — Nilai: {s.value} — Icon: {s.icon}</p>
              </div>
              <form action={deleteStat}>
                <input type="hidden" name="id" value={s.id} />
                <Button type="submit" variant="ghost" size="icon">
                  <Trash2 className="h-4 w-4 text-red-600" />
                </Button>
              </form>
            </div>
          ))}
        </div>
      )}
    </main>
  );
}
