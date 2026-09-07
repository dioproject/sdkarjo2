import { updateVisiMisi } from "@/app/admin/actions";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { prisma } from "@/lib/prisma";

async function getSiteConfig() {
  try {
    return await prisma.siteConfig.findUnique({ where: { id: "main" } });
  } catch {
    return null;
  }
}

export default async function VisiMisiPage() {
  const config = await getSiteConfig();
  const misiList = (config?.misi as string[]) ?? [];

  return (
    <main className="max-w-2xl px-6 py-12">
      <h1 className="text-2xl font-bold">Edit Visi & Misi</h1>
      <form action={updateVisiMisi} className="mt-6 space-y-4">
        <div className="grid gap-2">
          <Label htmlFor="visi">Visi</Label>
          <Input id="visi" name="visi" defaultValue={config?.visi ?? ""} placeholder="Visi sekolah" required />
        </div>
        <div className="grid gap-2">
          <Label htmlFor="misi">Misi (satu per baris)</Label>
          <Textarea
            id="misi"
            name="misi"
            rows={6}
            defaultValue={misiList.join("\n")}
            placeholder="Misi 1&#10;Misi 2&#10;Misi 3"
            required
          />
        </div>
        <Button type="submit">Simpan</Button>
      </form>
    </main>
  );
}
