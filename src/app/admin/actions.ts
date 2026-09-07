"use server";

import { redirect } from "next/navigation";
import { prisma } from "@/lib/prisma";
import { signToken, setAuthCookie, removeAuthCookie } from "@/lib/auth";

function slugify(value: string) {
  return value
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/(^-|-$)+/g, "");
}

async function hashPassword(password: string): Promise<string> {
  const encoder = new TextEncoder();
  const data = encoder.encode(password);
  const hash = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(hash)).map(b => b.toString(16).padStart(2, '0')).join('');
}

export async function loginAdmin(formData: FormData) {
  const email = String(formData.get("email") ?? "");
  const password = String(formData.get("password") ?? "");

  const user = await prisma.user.findUnique({ where: { email } });
  if (!user) redirect("/admin/login?error=1");

  const hashed = await hashPassword(password);
  if (hashed !== user.password) redirect("/admin/login?error=1");

  const token = await signToken({ userId: user.id, email: user.email });
  await setAuthCookie(token);
  redirect("/admin");
}

export async function logoutAdmin() {
  await removeAuthCookie();
  redirect("/admin/login");
}

export async function createAnnouncement(formData: FormData) {
  const title = String(formData.get("title") ?? "");
  const excerpt = String(formData.get("excerpt") ?? "");
  const category = String(formData.get("category") ?? "Informasi");
  const content = JSON.parse(String(formData.get("content") ?? "{}"));
  const isPublished = formData.get("isPublished") === "on";

  await prisma.announcement.create({
    data: {
      title,
      slug: slugify(title),
      excerpt,
      category: category as any,
      content,
      isPublished,
      authorName: "Admin"
    }
  });

  redirect("/admin/pengumuman");
}

export async function deleteAnnouncement(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  await prisma.announcement.delete({ where: { id } });
  redirect("/admin/pengumuman");
}


export async function createTeacher(formData: FormData) {
  const name = String(formData.get("name") ?? "");
  const role = String(formData.get("role") ?? "");
  const photo = String(formData.get("photo") ?? "");

  await prisma.teacher.create({
    data: { name, role, photo }
  });

  redirect("/admin/guru");
}

export async function deleteTeacher(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  await prisma.teacher.delete({ where: { id } });
  redirect("/admin/guru");
}


export async function createGallery(formData: FormData) {
  const title = String(formData.get("title") ?? "");
  const image = String(formData.get("image") ?? "");

  await prisma.gallery.create({ data: { title, image } });
  redirect("/admin/galeri");
}

export async function deleteGallery(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  await prisma.gallery.delete({ where: { id } });
  redirect("/admin/galeri");
}


export async function updateVisiMisi(formData: FormData) {
  const visi = String(formData.get("visi") ?? "");
  const misiRaw = String(formData.get("misi") ?? "");
  const misi = misiRaw.split("\n").map(s => s.trim()).filter(Boolean);

  await prisma.siteConfig.upsert({
    where: { id: "main" },
    update: { visi, misi },
    create: { id: "main", visi, misi },
  });

  redirect("/admin/visi-misi");
}

export async function createAchievement(formData: FormData) {
  const title = String(formData.get("title") ?? "");
  await prisma.achievement.create({ data: { title } });
  redirect("/admin/prestasi");
}

export async function deleteAchievement(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  await prisma.achievement.delete({ where: { id } });
  redirect("/admin/prestasi");
}

export async function upsertStat(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  const label = String(formData.get("label") ?? "");
  const value = String(formData.get("value") ?? "");
  const icon = String(formData.get("icon") ?? "UsersRound");

  await prisma.stat.upsert({
    where: { id },
    update: { label, value, icon },
    create: { id, label, value, icon },
  });

  redirect("/admin/statistik");
}

export async function deleteStat(formData: FormData) {
  const id = String(formData.get("id") ?? "");
  await prisma.stat.delete({ where: { id } });
  redirect("/admin/statistik");
}
