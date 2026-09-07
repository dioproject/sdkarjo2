"use client";

import { useRef, useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { createGallery } from "@/app/admin/actions";

export function GalleryForm() {
  const formRef = useRef<HTMLFormElement>(null);
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);

    const form = e.currentTarget;
    const title = (form.elements.namedItem("title") as HTMLInputElement).value;
    const fileInput = form.elements.namedItem("file") as HTMLInputElement;
    const file = fileInput.files?.[0];

    if (!file) { setLoading(false); return; }

    const fd = new FormData();
    fd.append("file", file);
    fd.append("folder", "gallery");
    const res = await fetch("/api/upload", { method: "POST", body: fd });
    const data = await res.json();

    const formData = new FormData();
    formData.append("title", title);
    formData.append("image", data.path ?? "");

    await createGallery(formData);
  }

  return (
    <form ref={formRef} onSubmit={handleSubmit} className="mb-6 flex flex-wrap items-end gap-3">
      <Input name="title" placeholder="Judul foto" required className="w-48" />
      <Input name="file" type="file" accept="image/*" required className="w-52" />
      <Button type="submit" disabled={loading}>
        {loading ? "Mengunggah..." : "Tambah Foto"}
      </Button>
    </form>
  );
}
