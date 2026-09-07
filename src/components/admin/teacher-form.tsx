"use client";

import { useRef, useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { createTeacher } from "@/app/admin/actions";

export function TeacherForm() {
  const formRef = useRef<HTMLFormElement>(null);
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);

    const form = e.currentTarget;
    const name = (form.elements.namedItem("name") as HTMLInputElement).value;
    const role = (form.elements.namedItem("role") as HTMLInputElement).value;
    const fileInput = form.elements.namedItem("file") as HTMLInputElement;
    const file = fileInput.files?.[0];

    let photo = "";
    if (file) {
      const fd = new FormData();
      fd.append("file", file);
      fd.append("folder", "teachers");
      const res = await fetch("/api/upload", { method: "POST", body: fd });
      const data = await res.json();
      photo = data.path ?? "";
    }

    const formData = new FormData();
    formData.append("name", name);
    formData.append("role", role);
    formData.append("photo", photo);

    await createTeacher(formData);
  }

  return (
    <form ref={formRef} onSubmit={handleSubmit} className="mb-6 flex flex-wrap items-end gap-3">
      <Input name="name" placeholder="Nama lengkap" required className="w-48" />
      <Input name="role" placeholder="Jabatan" required className="w-48" />
      <Input name="file" type="file" accept="image/*" className="w-52" />
      <Button type="submit" disabled={loading}>
        {loading ? "Mengunggah..." : "Tambah Guru"}
      </Button>
    </form>
  );
}
