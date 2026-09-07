import { Trash2 } from "lucide-react";
import { deleteGallery } from "@/app/admin/actions";
import { GalleryForm } from "@/components/admin/gallery-form";
import { Button } from "@/components/ui/button";
import { prisma } from "@/lib/prisma";

async function getGallery() {
  try {
    return await prisma.gallery.findMany({ orderBy: { createdAt: 'desc' } });
  } catch {
    return [];
  }
}

export default async function AdminGaleriPage() {
  const gallery = await getGallery();

  return (
    <main className="px-6 py-12">
      <h1 className="mb-6 text-2xl font-bold">Kelola Galeri</h1>
      <GalleryForm />
      {gallery.length === 0 ? (
        <p className="text-sm text-muted-foreground">Belum ada foto galeri.</p>
      ) : (
        <div className="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
          {gallery.map((g) => (
            <div key={g.id} className="overflow-hidden rounded-md border">
              <img src={g.image} alt={g.title} className="aspect-video w-full object-cover" />
              <div className="flex items-center justify-between p-2">
                <p className="text-xs font-medium truncate">{g.title}</p>
                <form action={deleteGallery}>
                  <input type="hidden" name="id" value={g.id} />
                  <Button type="submit" variant="ghost" size="icon">
                    <Trash2 className="h-4 w-4 text-red-600" />
                  </Button>
                </form>
              </div>
            </div>
          ))}
        </div>
      )}
    </main>
  );
}
