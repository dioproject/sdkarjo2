import Link from "next/link";
import { headers } from "next/headers";
import { Newspaper, Users, Image, Eye, Award, LayoutDashboard, BarChart3 } from "lucide-react";

const navItems = [
  { href: "/admin", label: "Dashboard", icon: LayoutDashboard },
  { href: "/admin/pengumuman", label: "Pengumuman", icon: Newspaper },
  { href: "/admin/guru", label: "Guru", icon: Users },
  { href: "/admin/galeri", label: "Galeri", icon: Image },
  { href: "/admin/visi-misi", label: "Visi & Misi", icon: Eye },
  { href: "/admin/prestasi", label: "Prestasi", icon: Award },
  { href: "/admin/statistik", label: "Statistik", icon: BarChart3 },
];

export default async function AdminLayout({ children }: { children: React.ReactNode }) {
  const headersList = await headers();
  const pathname = headersList.get("x-pathname") || "";
  const isLogin = pathname.includes("/login");

  if (isLogin) return <>{children}</>;

  return (
    <div className="flex min-h-[calc(100vh-64px)]">
      <aside className="w-56 shrink-0 border-r bg-card p-4">
        <nav className="space-y-1">
          {navItems.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-muted-foreground hover:bg-secondary hover:text-foreground"
            >
              <item.icon className="h-4 w-4" />
              {item.label}
            </Link>
          ))}
        </nav>
      </aside>
      <div className="flex-1">{children}</div>
    </div>
  );
}
