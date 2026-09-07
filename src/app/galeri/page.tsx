import Image from "next/image";
import { prisma } from "@/lib/prisma";

async function getGallery() {
  try {
    return await prisma.gallery.findMany({ orderBy: { createdAt: 'desc' } });
  } catch {
    return [];
  }
}

export default async function GalleryPage() {
  const gallery = await getGallery();

  return (
    <main className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
      <p className="text-sm font-semibold uppercase tracking-[0.16em] text-primary">Galeri Kegiatan</p>
      <h1 className="mt-3 text-4xl font-bold">Kegiatan Murid</h1>
      {gallery.length === 0 ? (
        <p className="mt-8 text-muted-foreground">Belum ada foto galeri.</p>
      ) : (
        <div className="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {gallery.map((item) => (
            <div key={item.id} className="relative aspect-[4/3] overflow-hidden rounded-lg border bg-card">
              <Image src={item.image} alt={item.title} fill className="object-cover" />
              <div className="absolute inset-x-0 bottom-0 bg-black/50 px-3 py-2">
                <p className="text-sm text-white">{item.title}</p>
              </div>
            </div>
          ))}
        </div>
      )}
    </main>
  );
}
