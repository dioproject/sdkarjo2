import { Trash2 } from "lucide-react";
import { deleteTeacher } from "@/app/admin/actions";
import { TeacherForm } from "@/components/admin/teacher-form";
import { Button } from "@/components/ui/button";
import { prisma } from "@/lib/prisma";

async function getTeachers() {
  try {
    return await prisma.teacher.findMany({ orderBy: { order: 'asc' } });
  } catch {
    return [];
  }
}

export default async function AdminGuruPage() {
  const teachers = await getTeachers();

  return (
    <main className="px-6 py-12">
      <h1 className="mb-6 text-2xl font-bold">Kelola Guru & Tenaga Pendidik</h1>
      <TeacherForm />
      {teachers.length === 0 ? (
        <p className="text-sm text-muted-foreground">Belum ada data guru.</p>
      ) : (
        <div className="space-y-2">
          {teachers.map((t) => (
            <div key={t.id} className="flex items-center justify-between rounded-md border p-3">
              <div className="flex items-center gap-3">
                {t.photo && <img src={t.photo} alt={t.name} className="h-10 w-10 rounded-full object-cover" />}
                <div>
                  <p className="text-sm font-semibold">{t.name}</p>
                  <p className="text-xs text-muted-foreground">{t.role}</p>
                </div>
              </div>
              <form action={deleteTeacher}>
                <input type="hidden" name="id" value={t.id} />
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
